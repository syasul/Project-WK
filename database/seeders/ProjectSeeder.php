<?php

namespace Database\Seeders;

use App\Models\Projects;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        Projects::create([
            'project_code' => 'PRJ-2023-001',
            'name' => 'Pengembangan Website E-Gov',
            'client_name' => 'Dinas Kominfo',
            'description' => 'Pembuatan portal satu data',
            'address' => 'Jakarta',
            'location_id' => 1, // Head Office
            'project_value' => 150000000,
            'payment_status' => 'partial',
            'status' => 'ongoing',
            'start_date' => '2023-01-10',
            'end_date' => '2023-06-10',
        ]);

        Projects::create([
            'project_code' => 'PRJ-2023-002',
            'name' => 'Instalasi Jaringan Gudang',
            'client_name' => 'PT. Logistik Maju',
            'description' => 'Setup CCTV dan Wifi',
            'address' => 'Bekasi',
            'location_id' => 2, // Gudang Bekasi
            'project_value' => 75000000,
            'payment_status' => 'paid',
            'status' => 'completed',
            'start_date' => '2023-02-01',
            'end_date' => '2023-03-01',
        ]);
    }
}