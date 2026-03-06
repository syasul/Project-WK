<?php

namespace Database\Seeders;

use App\Models\Holidays;
use Illuminate\Database\Seeder;

class HolidaySeeder extends Seeder
{
    public function run(): void
    {
        $year = date('Y');
        Holidays::create([
            'name' => 'Tahun Baru Masehi',
            'holiday_date' => "$year-01-01",
            'type' => 'national',
            'description' => 'Libur Nasional Tahun Baru',
        ]);

        Holidays::create([
            'name' => 'Hari Raya Idul Fitri',
            'holiday_date' => "$year-04-10",
            'type' => 'national',
            'description' => 'Libur Lebaran',
        ]);
        
        Holidays::create([
            'name' => 'Cuti Bersama Lebaran',
            'holiday_date' => "$year-04-11",
            'type' => 'common_leave', // Cuti Bersama
            'description' => 'Cuti Bersama Pemerintah',
        ]);
    }
}