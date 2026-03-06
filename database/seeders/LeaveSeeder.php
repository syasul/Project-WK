<?php

namespace Database\Seeders;

use App\Models\Leaves;
use App\Models\User;
use Illuminate\Database\Seeder;

class LeaveSeeder extends Seeder
{
    public function run(): void
    {
        $employee = User::where('role', 'employee')->first();

        if ($employee) {
            // Contoh Cuti Sakit (Approved)
            Leaves::create([
                'user_id' => $employee->user_id,
                'type' => 'sick',
                'start_date' => now()->subDays(10),
                'end_date' => now()->subDays(8),
                'reason' => 'Demam tinggi',
                'status' => 'approved',
                'admin_note' => 'Cepat sembuh',
            ]);

            // Contoh Cuti Tahunan (Pending)
            Leaves::create([
                'user_id' => $employee->user_id,
                'type' => 'annual',
                'start_date' => now()->addDays(5),
                'end_date' => now()->addDays(7),
                'reason' => 'Liburan keluarga',
                'status' => 'pending',
            ]);
        }
    }
}