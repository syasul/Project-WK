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
        $validator = Validator::make($request->all(), [
            'latitude'  => 'required',
            'longitude' => 'required',
            'photo'     => 'required|image|max:2048',
            'project_id' => 'nullable'
        ]);

        if ($validator->fails()) return response()->json($validator->errors(), 422);

        $user = $request->user()->load('shift');
        $now = Carbon::now();

        // CEK: Apakah masih ada absen yang belum Checkout hari ini?
        $stillOnDuty = Attendances::where('user_id', $user->user_id)
            ->whereDate('clock_in_time', $now->toDateString())
            ->whereNull('clock_out_time')
            ->first();

        if ($stillOnDuty) {
            return response()->json(['message' => 'Anda harus Check-out dari lokasi sebelumnya sebelum pindah lokasi!'], 400);
        }

        // CEK SHIFT: Hitung telat hanya jika ini absen pertama hari ini
        $isFirstToday = !Attendances::where('user_id', $user->user_id)
            ->whereDate('clock_in_time', $now->toDateString())
            ->exists();

        $lateMinutes = 0;
        if ($isFirstToday && $user->shift) {
            $startTime = Carbon::parse($now->toDateString() . ' ' . $user->shift->start_time);
            if ($now->gt($startTime)) {
                $lateMinutes = $now->diffInMinutes($startTime);
            }
        }

        // Simpan Foto
        $path = $request->file('photo')->store('attendance', 'public');
        $imageUrl = url('storage/' . $path);

        $attendance = Attendances::create([
            'user_id' => $user->user_id,
            'project_id' => $request->project_id,
            'clock_in_time' => $now,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'image_url' => $imageUrl,
            'late_minutes' => $lateMinutes,
            'status_attendance' => ($lateMinutes > 0) ? 'late' : 'on_duty'
        ]);

        return response()->json(['message' => 'Check-in berhasil di lokasi proyek.', 'data' => $attendance], 201);
    }

    /**
     * CLOCK OUT (Absen Pulang)
     */
    public function clockOut(Request $request)
    {
        $user = $request->user()->load('shift');
        $now = Carbon::now();

        $attendance = Attendances::where('user_id', $user->user_id)
            ->whereNull('clock_out_time')
            ->latest()
            ->first();

        if (!$attendance) return response()->json(['message' => 'Data Check-in tidak ditemukan!'], 404);

        // Perhitungan Pulang Cepat & Lembur (Dibandingkan dengan Shift End)
        $earlyLeave = 0;
        $overtime = 0;
        
        if ($user->shift) {
            $endTime = Carbon::parse($now->toDateString() . ' ' . $user->shift->end_time);
            
            // Anggap ini checkout terakhir jika user mengirim flag 'is_final' dari Flutter
            if ($request->is_final == true) {
                if ($now->lt($endTime)) {
                    $earlyLeave = $now->diffInMinutes($endTime);
                } else {
                    $overtime = $now->diffInMinutes($endTime);
                }
            }
        }

        // Simpan Foto Pulang (Jika ada)
        $imageOutUrl = null;
        if ($request->hasFile('photo')) {
            $pathOut = $request->file('photo')->store('attendance_out', 'public');
            $imageOutUrl = url('storage/' . $pathOut);
        }

        $attendance->update([
            'clock_out_time' => $now,
            'latitude_out' => $request->latitude,
            'longitude_out' => $request->longitude,
            'image_out_url' => $imageOutUrl,
            'early_leave_minutes' => $earlyLeave,
            'overtime_minutes' => $overtime,
        ]);

        return response()->json(['message' => 'Check-out berhasil.', 'data' => $attendance]);
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

    public function leaveStats(Request $request)
    {
        $user = $request->user();
        
        $pending = Leaves::where('user_id', $user->user_id)->where('status', 'pending')->count();
        $approved = Leaves::where('user_id', $user->user_id)->where('status', 'approved')->count();
        $rejected = Leaves::where('user_id', $user->user_id)->where('status', 'rejected')->count();

        return response()->json([
            'message' => 'Statistik Izin',
            'data' => [
                'pending' => $pending,
                'approved' => $approved,
                'rejected' => $rejected,
                'total' => $pending + $approved + $rejected
            ]
        ], 200);
    }
}