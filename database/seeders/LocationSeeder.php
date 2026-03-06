<?php

namespace Database\Seeders;

use App\Models\Locations;
use App\Models\User;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil user dengan role leader
        $leader = User::where('role', 'leader')->first();

        $locations = [
            [
                'name' => 'Head Office Jakarta',
                'latitude' => '-6.2088',
                'longitude' => '106.8456',
                'radius' => 50,
                'address' => 'Jl. Jend. Sudirman, Jakarta Pusat',
                'leader_id' => $leader ? $leader->user_id : 1,
            ],
            [
                'name' => 'Gudang Bekasi',
                'latitude' => '-6.2383',
                'longitude' => '106.9756',
                'radius' => 100,
                'address' => 'Kawasan Industri MM2100, Bekasi',
                'leader_id' => $leader ? $leader->user_id : 1,
            ],
        ];

        foreach ($locations as $loc) {
            Locations::create($loc);
        }
    }
}