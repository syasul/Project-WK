<?php

namespace Database\Seeders;

use App\Models\Attendances;
use App\Models\User;
use App\Models\Shifts;
use App\Models\Projects; // Pastikan import Projects jika ada
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AttendanceSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil semua karyawan aktif (bukan admin)
        $employees = User::where('role', '!=', 'admin')->get();
        $leader = User::where('role', 'leader')->first();
        
        // Ambil satu project default agar tidak error relasi
        $project = Projects::first();
        
        // Loop 7 hari ke belakang
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            
            // Skip hari minggu
            if ($date->isSunday()) continue;

            foreach ($employees as $emp) {
                // Randomisasi: 80% Masuk, 20% Tidak Masuk/Izin
                if (rand(1, 100) > 20) {
                    
                    $shift = Shifts::find($emp->shift_id) ?? Shifts::first();
                    $shiftStart = Carbon::parse($date->format('Y-m-d') . ' ' . $shift->start_time);
                    
                    // Randomisasi jam masuk
                    if (rand(1, 100) <= 70) {
                        $clockIn = $shiftStart->copy()->subMinutes(rand(0, 30));
                        $status = 'on_time';
                        $lateMinutes = 0;
                    } else {
                        $lateMinutes = rand(1, 120); 
                        $clockIn = $shiftStart->copy()->addMinutes($lateMinutes);
                        $status = 'late';
                    }

                    // Jam Pulang (Kerja sekitar 9 jam)
                    $clockOut = $clockIn->copy()->addHours(9); 

                    Attendances::create([
                        'user_id'             => $emp->user_id,
                        'leader_id'           => $leader ? $leader->user_id : null,
                        'project_id'          => $project ? $project->project_id : null,
                        'clock_in_time'       => $clockIn,
                        'clock_out_time'      => $clockOut,
                        'latitude'            => '-6.2088',
                        'longitude'           => '106.8456',
                        'latitude_out'        => '-6.2090',
                        'longitude_out'       => '106.8460',
                        // Solusi Error: Tambahkan image dummy
                        'image_url'           => 'https://ui-avatars.com/api/?name=' . urlencode($emp->name) . '&background=random&size=128',
                        'image_out_url'       => 'https://ui-avatars.com/api/?name=' . urlencode($emp->name) . '&background=0D8ABC&color=fff&size=128',
                        'late_minutes'        => $lateMinutes,
                        'early_leave_minutes' => 0,
                        'overtime_minutes'    => rand(0, 60), // Random lembur dikit
                        'status_attendance'   => $status,
                    ]);
                }
            }
        }
    }
}