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
    public function index()
    {
        $today = Carbon::today();

        // 1. STATISTIK UTAMA
        $stats = [
            // Total Karyawan (Role Employee)
            'total_employees' => User::where('role', 'employee')->count(),
            
            // Verifikasi Pending (User baru yang belum verifikasi email)
            'pending_verifications' => User::whereNull('email_verified_at')->count(),
            
            // Project Aktif (Status Ongoing)
            'projects_active' => Projects::where('status', 'ongoing')->count(),
            
            // Project Belum Lunas (Payment bukan 'paid')
            'projects_unpaid' => Projects::where('payment_status', '!=', 'paid')->count(),
            
            // Kehadiran Hari Ini (Clock In hari ini)
            'attendance_today' => Attendances::whereDate('clock_in_time', $today)->count(),
            
            // Terlambat Hari Ini
            'late_today' => Attendances::whereDate('clock_in_time', $today)
                                    ->where('status_attendance', 'late')
                                    ->count(),
        ];

        // 2. HARI LIBUR TERDEKAT (2 Data ke depan)
        $upcoming_holidays = Holidays::where('holiday_date', '>=', $today)
                                     ->orderBy('holiday_date', 'asc')
                                     ->take(2)
                                     ->get();

        // 3. AKTIVITAS TERBARU (5 Absensi Terakhir hari ini)
        $recent_activities = Attendances::with('user')
                                        ->whereDate('clock_in_time', $today)
                                        ->orderBy('clock_in_time', 'desc')
                                        ->take(5)
                                        ->get();

        return view('screens.dashboardPage', compact('stats', 'upcoming_holidays', 'recent_activities'));
    }
}