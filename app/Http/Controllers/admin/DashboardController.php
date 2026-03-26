<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

// Models
use App\Models\User;
use App\Models\Projects;
use App\Models\Attendances;
use App\Models\Holidays; // Pastikan model Holidays sudah ada

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $today = Carbon::today();
        $search = $request->input('search'); // Menangkap inputan pencarian

        // 1. STATISTIK UTAMA
        $stats = [
            'total_employees' => User::where('role', 'employee')->count(),
            'pending_verifications' => User::whereNull('email_verified_at')->count(),
            'projects_active' => Projects::where('status', 'ongoing')->count(),
            'projects_unpaid' => Projects::where('payment_status', '!=', 'paid')->count(),
            'attendance_today' => Attendances::whereDate('clock_in_time', $today)->count(),
            'late_today' => Attendances::whereDate('clock_in_time', $today)
                                    ->where('status_attendance', 'late')
                                    ->count(),
        ];

        // 2. HARI LIBUR TERDEKAT
        $upcoming_holidays = Holidays::where('holiday_date', '>=', $today)
                                     ->orderBy('holiday_date', 'asc')
                                     ->take(2)
                                     ->get();

        // 3. AKTIVITAS TERBARU (Dengan Fitur Pencarian)
        $query = Attendances::with('user')
                            ->whereDate('clock_in_time', $today)
                            ->orderBy('clock_in_time', 'desc');

        // Jika ada pencarian nama karyawan
        if ($search) {
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $recent_activities = $query->take(5)->get();

        return view('screens.dashboardPage', compact('stats', 'upcoming_holidays', 'recent_activities', 'search'));
    }

    // FUNGSI UNTUK EXPORT LAPORAN HARI INI KE CSV/EXCEL
    public function exportToday()
    {
        $today = Carbon::today();
        $attendances = Attendances::with('user')
                            ->whereDate('clock_in_time', $today)
                            ->orderBy('clock_in_time', 'asc')
                            ->get();

        $fileName = 'Laporan_Absensi_' . date('d-m-Y') . '.csv';

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('No', 'Nama Karyawan', 'Role', 'Jam Masuk', 'Jam Pulang', 'Status', 'Lokasi (Lat, Lng)');

        $callback = function() use($attendances, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            $row = 1;
            foreach ($attendances as $data) {
                fputcsv($file, array(
                    $row++,
                    $data->user->name ?? 'Unknown',
                    strtoupper($data->user->role ?? 'employee'),
                    $data->clock_in_time ? Carbon::parse($data->clock_in_time)->format('H:i:s') : '-',
                    $data->clock_out_time ? Carbon::parse($data->clock_out_time)->format('H:i:s') : 'Belum Pulang',
                    strtoupper($data->status_attendance),
                    $data->latitude . ', ' . $data->longitude
                ));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportProjects()
    {
        // Ambil semua data proyek, diurutkan dari yang terbaru
        $projects = Projects::orderBy('created_at', 'desc')->get();

        $fileName = 'Daftar_Proyek_' . date('d-m-Y') . '.csv';

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        // Header kolom di Excel (Bisa disesuaikan dengan nama kolom di database kamu)
        $columns = array('No', 'Nama Proyek', 'Status Proyek', 'Status Pembayaran', 'Tanggal Dibuat');

        $callback = function() use($projects, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            $row = 1;
            foreach ($projects as $data) {
                fputcsv($file, array(
                    $row++,
                    $data->name ?? '-', // Pastikan kolom 'name' ada di tabel projects
                    strtoupper($data->status ?? '-'),
                    strtoupper($data->payment_status ?? '-'),
                    $data->created_at ? Carbon::parse($data->created_at)->format('d-m-Y') : '-'
                ));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}