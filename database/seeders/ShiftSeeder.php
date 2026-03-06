<?php

namespace Database\Seeders;

use App\Models\Shifts; // Pastikan Model sudah dibuat
use Illuminate\Database\Seeder;

class ShiftSeeder extends Seeder
{
    public function run(): void
    {
        $shifts = [
            [
                'name' => 'Office Hour (Pagi)',
                'start_time' => '08:00:00',
                'end_time' => '17:00:00',
            ],
            [
                'name' => 'Shift Siang',
                'start_time' => '13:00:00',
                'end_time' => '22:00:00',
            ],
            [
                'name' => 'Shift Malam',
                'start_time' => '21:00:00',
                'end_time' => '06:00:00',
            ],
        ];

        foreach ($shifts as $shift) {
            Shifts::create($shift);
        }
    }
}