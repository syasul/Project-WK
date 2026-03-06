<?php

namespace Database\Seeders;

use App\Models\Attendances;
use App\Models\User;
use App\Models\Shifts;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AttendanceSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil semua karyawan aktif (bukan admin)
        $employees = User::where('role', '!=', 'admin')->get();
        $leader = User::where('role', 'leader')->first();
        
        // Loop 7 hari ke belakang
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            
            // Skip hari minggu (opsional)
            if ($date->isSunday()) continue;

            foreach ($employees as $emp) {
                // Randomisasi: 80% Masuk, 20% Tidak Masuk/Alpha/Izin
                if (rand(1, 100) > 20) {
                    
                    $shift = Shifts::find($emp->shift_id) ?? Shifts::first();
                    $shiftStart = Carbon::parse($date->format('Y-m-d') . ' ' . $shift->start_time);
                    
                    // Randomisasi jam masuk (Ada yang telat, ada yang ontime)
                    // 70% Ontime (30 menit sblm - 5 menit ssdh), 30% Telat
                    if (rand(1, 100) <= 70) {
                        $clockIn = $shiftStart->copy()->subMinutes(rand(0, 30));
                        $status = 'on_time';
                        $lateMinutes = 0;
                    } else {
                        $lateMinutes = rand(1, 120); // Telat 1 - 120 menit
                        $clockIn = $shiftStart->copy()->addMinutes($lateMinutes);
                        $status = 'late';
                    }

                    // Jam Pulang (Random setelah jam kerja selesai)
                    $clockOut = $clockIn->copy()->addHours(9); // Kerja 9 jam

                    Attendances::create([
                        'user_id' => $emp->user_id,
                        'leader_id' => $leader ? $leader->user_id : 1, // Assign ke leader default
                        'project_id' => 1, // Default project 1
                        'clock_in_time' => $clockIn,
                        'clock_out_time' => $clockOut,
                        'late_minutes' => $lateMinutes,
                        'early_leave_minutes' => 0,
                        'overtime_minutes' => 0,
                        'status_attendance' => $status,
                        'latitude' => '-6.2088',
                        'longitude' => '106.8456',
                    ]);
                }
            }
        }
    }
}