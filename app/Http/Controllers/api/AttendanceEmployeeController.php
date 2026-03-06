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
        // Kita cari lokasi terdekat yang valid (Masuk dalam radius)
        $validLocation = null;
        $locations = Locations::all(); // Ambil semua master lokasi

        foreach ($locations as $loc) {
            $distance = $this->calculateDistance(
                $request->latitude, 
                $request->longitude, 
                $loc->latitude, 
                $loc->longitude
            );

            // Jika jarak user <= radius lokasi, maka valid
            if ($distance <= $loc->radius) {
                $validLocation = $loc;
                break; // Ketemu lokasi, stop loop
            }
        }

        if (!$validLocation) {
            return response()->json(['message' => 'Anda berada di luar jangkauan lokasi kantor/project.'], 403);
        }

        // 4. Hitung Keterlambatan (Berdasarkan Shift User)
        $shift = Shifts::find($user->shift_id);
        $status = 'on_time';
        $lateMinutes = 0;

        if ($shift) {
            $clockInTime = Carbon::now();
            // Gabungkan tanggal hari ini dengan jam masuk shift
            $shiftStartTime = Carbon::parse($today . ' ' . $shift->start_time);

            // Jika absen lebih dari jam masuk + toleransi (misal 0 menit)
            if ($clockInTime->gt($shiftStartTime)) {
                $status = 'late';
                $lateMinutes = $shiftStartTime->diffInMinutes($clockInTime);
            }
        }

        // 5. Upload Foto Selfie
        $imagePath = null;
        if ($request->hasFile('photo')) {
            // Simpan ke storage/app/public/attendance
            $file = $request->file('photo');
            $filename = 'att_' . $user->user_id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('public/attendance', $filename);
            
            // Generate URL agar bisa diakses public (perlu: php artisan storage:link)
            $imagePath = url('storage/attendance/' . $filename);
        }

        // 6. Simpan Data
        try {
            $attendance = Attendances::create([
                'user_id'           => $user->user_id,
                'leader_id'         => $validLocation->leader_id ?? 1, // Default ke admin/id 1 jika null
                'project_id'        => null, // Bisa dikembangkan jika lokasi terikat project
                'clock_in_time'     => Carbon::now(),
                'status_attendance' => $status,
                'late_minutes'      => $lateMinutes,
                'latitude'          => $request->latitude,
                'longitude'         => $request->longitude,
                'image_url'         => $imagePath, // Tambahkan kolom ini di migration jika belum ada (atau pakai attachment)
            ]);

            return response()->json([
                'message' => 'Absen masuk berhasil!',
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

        // Cari absen hari ini yang belum clock out
        $attendance = Attendances::where('user_id', $user->user_id)
                                 ->whereDate('clock_in_time', $today)
                                 ->first();

        if (!$attendance) {
            return response()->json(['message' => 'Anda belum melakukan absen masuk hari ini.'], 400);
        }

        if ($attendance->clock_out_time) {
            return response()->json(['message' => 'Anda sudah absen pulang sebelumnya.'], 400);
        }

        // Validasi Lokasi saat pulang (Opsional, biasanya pulang bebas atau harus di kantor juga)
        // Disini kita asumsikan pulang juga harus di lokasi, copy logic Geofencing di atas jika perlu.
        // Untuk simpelnya, kita izinkan pulang dimana saja atau validasi lat/long required.

        $validator = Validator::make($request->all(), [
            'latitude'  => 'required',
            'longitude' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Lokasi diperlukan.', 'errors' => $validator->errors()], 422);
        }

        // Hitung Early Leave / Overtime (Opsional Logic)
        $shift = Shifts::find($user->shift_id);
        $earlyMinutes = 0;
        $overtimeMinutes = 0;
        
        if ($shift) {
            $now = Carbon::now();
            $shiftEndTime = Carbon::parse($today . ' ' . $shift->end_time);

            if ($now->lt($shiftEndTime)) {
                $earlyMinutes = $now->diffInMinutes($shiftEndTime);
                $attendance->status_attendance = 'early_leave'; // Update status jika pulang cepat
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
        
        // Filter Bulan & Tahun (Default bulan ini)
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
     * FUNGSI BANTUAN: Menghitung Jarak (Haversine Formula)
     * Mengembalikan jarak dalam satuan Meter
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // Radius bumi dalam meter

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        $distance = $earthRadius * $c;

        return $distance; // Hasil dalam meter
    }
}