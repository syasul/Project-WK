<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Attendances;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil tanggal dari request atau default hari ini
        $date = $request->input('date', Carbon::today()->format('Y-m-d'));
        $search = $request->input('search');

        // 2. Query Utama dengan Eager Loading
        $query = Attendances::with(['user', 'location'])
                            ->whereDate('clock_in_time', $date);

        // 3. Filter Pencarian Nama Karyawan
        if ($search) {
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        // 4. Eksekusi Query
        $attendances = $query->orderBy('clock_in_time', 'desc')->get();

        // 5. Data Khusus untuk Live Map (Hanya yang punya lat/long)
        // Kita gunakan values() untuk reset index array agar JSON di JS rapi
        $mapData = $attendances->whereNotNull('latitude')
                               ->whereNotNull('longitude')
                               ->map(function($item){
                                   return [
                                       'name' => $item->user->name ?? 'Unknown',
                                       'time' => Carbon::parse($item->clock_in_time)->format('H:i'),
                                       'status' => $item->status_attendance, // Pastikan nama kolom di DB benar ('status_attendance' atau 'status')
                                       'lat' => $item->latitude,
                                       'lng' => $item->longitude,
                                       'photo' => $item->image_url // Opsional untuk popup map
                                   ];
                               })->values(); 

        return view('screens.manageAttendancePage', compact('attendances', 'date', 'mapData'));
    }
}