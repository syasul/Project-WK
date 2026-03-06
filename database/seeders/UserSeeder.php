<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Admin
        User::create([
            'shift_id' => 1,
            'name' => 'Administrator',
            'email' => 'admin@demo.com',
            'password' => Hash::make('password'),
            'phone' => '081234567890',
            'position' => 'Super Admin',
            'role' => 'admin',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        // 2. Buat Leader
        User::create([
            'shift_id' => 1,
            'name' => 'Budi Santoso (Leader)',
            'email' => 'leader@demo.com',
            'password' => Hash::make('password'),
            'phone' => '081234567891',
            'position' => 'Project Manager',
            'role' => 'leader',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        // 3. Buat 10 Karyawan Dummy
        \App\Models\User::factory(10)->create(); 
        // Note: Jika belum punya Factory, gunakan loop manual di bawah ini:
        
        $faker = \Faker\Factory::create('id_ID');
        for($i=1; $i<=10; $i++){
            User::create([
                'shift_id' => rand(1, 3), // Random shift
                'name' => $faker->name,
                'email' => 'karyawan'.$i.'@demo.com',
                'password' => Hash::make('password'),
                'phone' => $faker->phoneNumber,
                'position' => 'Staff',
                'role' => 'employee',
                'status' => 'active',
                'email_verified_at' => now(),
            ]);
        }
        
    }
}