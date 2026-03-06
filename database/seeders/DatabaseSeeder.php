<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ShiftSeeder::class,      // 1. Shift dulu (karena user butuh shift_id)
            UserSeeder::class,       // 2. User (Admin, Leader, Employee)
            LocationSeeder::class,   // 3. Lokasi (butuh leader_id)
            ProjectSeeder::class,    // 4. Project (butuh location_id)
            HolidaySeeder::class,    // 5. Hari Libur
            AttendanceSeeder::class, // 6. Absensi (butuh user, project)
            LeaveSeeder::class,      // 7. Cuti
        ]);
    }
}
