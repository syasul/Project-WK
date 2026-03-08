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
        $date = $request->input('date', Carbon::today()->format('Y-m-d'));
        $search = $request->input('search');

        // Perbaikan: Menghapus 'location' dari with() karena relasi tersebut tidak ada
        $query = Attendances::with(['user', 'project'])
                            ->whereDate('clock_in_time', $date);

        if ($search) {
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $attendances = $query->orderBy('clock_in_time', 'desc')->get();

        $mapData = $attendances->whereNotNull('latitude')
                               ->whereNotNull('longitude')
                               ->map(function($item){
                                   return [
                                       'name' => $item->user->name ?? 'Unknown',
                                       'time' => $item->clock_in_time ? $item->clock_in_time->format('H:i') : '-',
                                       'status' => $item->status_attendance,
                                       'lat' => $item->latitude,
                                       'lng' => $item->longitude,
                                       'photo' => $item->image_url
                                   ];
                               })->values(); 

        return view('screens.manageAttendancePage', compact('attendances', 'date', 'mapData'));
    }

    public function map(Request $request)
    {
        $date = $request->input('date', Carbon::today()->format('Y-m-d'));

        $attendances = Attendances::with('user')
                            ->whereDate('clock_in_time', $date)
                            ->whereNotNull('latitude')
                            ->whereNotNull('longitude')
                            ->get();

        $mapData = $attendances->map(function($item){
            return [
                'name' => $item->user->name ?? 'Unknown',
                'time' => $item->clock_in_time ? $item->clock_in_time->format('H:i') : '-',
                'status' => $item->status_attendance,
                'lat' => $item->latitude,
                'lng' => $item->longitude,
                'photo' => $item->image_url 
            ];
        })->values();

        return view('screens.manageAttendancePage', compact('mapData', 'date', 'attendances'));
    }
}