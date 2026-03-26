<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

// Models
use App\Models\Attendances;
use App\Models\Locations;
use App\Models\Shifts;
use App\Models\User;
use App\Models\Leaves;

class AttendanceEmployeeController extends Controller
{
    /**
     * CLOCK IN (Absen Masuk)
     */
    public function clockIn(Request $request)
    {
        // 1. Validasi Input
        $validator = Validator::make($request->all(), [
            'latitude'  => 'required',
            'longitude' => 'required',
            'photo'     => 'required|image|max:2048', // Max 2MB
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Data tidak lengkap/invalid', 'errors' => $validator->errors()], 422);
        }

        $user = $request->user();
        $today = Carbon::now()->format('Y-m-d');

        // 2. Cek apakah sudah absen hari ini?
        $check = Attendances::where('user_id', $user->user_id)
                            ->whereDate('clock_in_time', $today)
                            ->first();

        if ($check) {
            return response()->json(['message' => 'Anda sudah melakukan absen masuk hari ini.'], 400);
        }

        // 3. Cek Lokasi (Geofencing)
        $validLocation = null;
        $locations = Locations::all(); 

        foreach ($locations as $loc) {
            $distance = $this->calculateDistance(
                $request->latitude, 
                $request->longitude, 
                $loc->latitude, 
                $loc->longitude
            );

            if ($distance <= $loc->radius) {
                $validLocation = $loc;
                break; 
            }
        }

        if (!$validLocation) {
            return response()->json(['message' => 'Anda berada di luar jangkauan lokasi kantor/project.'], 403);
        }

        // 4. Hitung Keterlambatan
        $shift = Shifts::find($user->shift_id);
        $status = 'on_time';
        $lateMinutes = 0;

        if ($shift) {
            $clockInTime = Carbon::now();
            $shiftStartTime = Carbon::parse($today . ' ' . $shift->start_time);

            if ($clockInTime->gt($shiftStartTime)) {
                $status = 'late';
                $lateMinutes = $shiftStartTime->diffInMinutes($clockInTime);
            }
        }

        // 5. Upload Foto Selfie
        $imagePath = null;
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = 'att_' . $user->user_id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('public/attendance', $filename);
            $imagePath = url('storage/attendance/' . $filename);
        }

        // 6. Simpan Data Absen Karyawan
        try {
            $attendance = Attendances::create([
                'user_id'           => $user->user_id,
                'leader_id'         => $validLocation->leader_id ?? 1, 
                'project_id'        => null, 
                'clock_in_time'     => Carbon::now(),
                'status_attendance' => $status,
                'late_minutes'      => $lateMinutes,
                'latitude'          => $request->latitude,
                'longitude'         => $request->longitude,
                'image_url'         => $imagePath,
            ]);

            // =======================================================
            // 7. LOGIKA BARU: OTOMATIS ABSENKAN LEADER 
            // =======================================================
            $leaderId = $validLocation->leader_id ?? 1; // Ambil ID leader dari lokasi

            // Pastikan yang sedang absen saat ini BUKAN si leader itu sendiri
            if ($user->user_id != $leaderId) {
                // Cek apakah leader sudah absen hari ini?
                $leaderAttendance = Attendances::where('user_id', $leaderId)
                                        ->whereDate('clock_in_time', $today)
                                        ->first();

                // Jika leader belum absen, buatkan data otomatis!
                if (!$leaderAttendance) {
                    Attendances::create([
                        'user_id'           => $leaderId,
                        'leader_id'         => $leaderId, // Dia leadernya sendiri
                        'project_id'        => null,
                        'clock_in_time'     => Carbon::now(),
                        'status_attendance' => 'on_time', // Leader selalu dianggap on_time
                        'late_minutes'      => 0,
                        'latitude'          => $request->latitude, // Pake lokasi karyawan
                        'longitude'         => $request->longitude,
                        'image_url'         => $imagePath, // Pake foto karyawan pertama sebagai bukti
                    ]);
                }
            }
            // =======================================================

            return response()->json([
                'message' => 'Absen masuk berhasil! Leader juga otomatis terabsen.',
                'data' => $attendance
            ], 201);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal menyimpan data.', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * CLOCK OUT (Absen Pulang)
     */
    public function clockOut(Request $request)
    {
        $user = $request->user();
        $today = Carbon::now()->format('Y-m-d');

        $attendance = Attendances::where('user_id', $user->user_id)
                                 ->whereDate('clock_in_time', $today)
                                 ->first();

        if (!$attendance) {
            return response()->json(['message' => 'Anda belum melakukan absen masuk hari ini.'], 400);
        }

        if ($attendance->clock_out_time) {
            return response()->json(['message' => 'Anda sudah absen pulang sebelumnya.'], 400);
        }

        $validator = Validator::make($request->all(), [
            'latitude'  => 'required',
            'longitude' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Lokasi diperlukan.', 'errors' => $validator->errors()], 422);
        }

        $shift = Shifts::find($user->shift_id);
        $earlyMinutes = 0;
        $overtimeMinutes = 0;
        
        if ($shift) {
            $now = Carbon::now();
            $shiftEndTime = Carbon::parse($today . ' ' . $shift->end_time);

            if ($now->lt($shiftEndTime)) {
                $earlyMinutes = $now->diffInMinutes($shiftEndTime);
                $attendance->status_attendance = 'early_leave'; 
            } else {
                $overtimeMinutes = $now->diffInMinutes($shiftEndTime);
            }
        }

        $attendance->update([
            'clock_out_time'      => Carbon::now(),
            'early_leave_minutes' => $earlyMinutes,
            'overtime_minutes'    => $overtimeMinutes,
        ]);

        return response()->json([
            'message' => 'Absen pulang berhasil. Hati-hati di jalan!',
            'data' => $attendance
        ]);
    }

    /**
     * HISTORY (Riwayat Absensi)
     */
    public function history(Request $request)
    {
        $user = $request->user();
        
        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));

        $history = Attendances::where('user_id', $user->user_id)
                              ->whereMonth('clock_in_time', $month)
                              ->whereYear('clock_in_time', $year)
                              ->orderBy('clock_in_time', 'desc')
                              ->get();

        return response()->json([
            'message' => 'Data riwayat absensi',
            'data' => $history
        ]);
    }

    /**
     * FUNGSI BANTUAN
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; 

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    public function applyLeave(Request $request)
    {
        // 1. Validasi Input
        $validator = Validator::make($request->all(), [
            'type'       => 'required|in:sick,permit,annual',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'reason'     => 'required|string',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048', // Max 2MB
        ], [
            'end_date.after_or_equal' => 'Tanggal selesai tidak boleh lebih awal dari tanggal mulai.',
            'attachment.max' => 'Ukuran file maksimal 2MB.',
            'attachment.mimes' => 'Format file harus JPG, PNG, atau PDF.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal', 
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();

        // 2. Cek apakah di rentang tanggal tersebut user sudah pernah mengajukan izin
        $existingLeave = Leaves::where('user_id', $user->user_id)
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                      ->orWhereBetween('end_date', [$request->start_date, $request->end_date]);
            })
            ->first();

        if ($existingLeave) {
            return response()->json([
                'message' => 'Anda sudah memiliki pengajuan izin pada tanggal tersebut.'
            ], 400);
        }

        // 3. Proses Upload File Lampiran (Jika Ada)
        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            // Disimpan di folder storage/app/public/leaves
            $attachmentPath = $request->file('attachment')->store('leaves', 'public');
        }

        // 4. Simpan ke Database
        try {
            $leave = Leaves::create([
                'user_id'    => $user->user_id,
                'type'       => $request->type,
                'start_date' => $request->start_date,
                'end_date'   => $request->end_date,
                'reason'     => $request->reason,
                'attachment' => $attachmentPath,
                'status'     => 'pending', // Default status
            ]);

            return response()->json([
                'message' => 'Pengajuan izin berhasil dikirim dan menunggu persetujuan.',
                'data' => $leave
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal menyimpan pengajuan izin.', 
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function leaveHistory(Request $request)
    {
        $user = $request->user();
        
        // Ambil semua data izin milik user ini, urutkan dari yang terbaru
        $leaves = Leaves::where('user_id', $user->user_id)
                        ->orderBy('created_at', 'desc')
                        ->get();

        return response()->json([
            'message' => 'Data riwayat pengajuan izin berhasil diambil',
            'data' => $leaves
        ], 200);
    }
}