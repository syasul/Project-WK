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

    public function export(Request $request)
    {
        $query = Attendances::with('user');

        // Terapkan filter yang sama saat export agar data yang diunduh sesuai dengan tampilan tabel
        if ($request->has('status') && $request->status != '') {
            $query->where('status_attendance', $request->status);
        }
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            });
        }
        if ($request->has('date') && $request->date != '') {
            $query->whereDate('clock_in_time', $request->date);
        }

        $attendances = $query->orderBy('clock_in_time', 'desc')->get();
        $fileName = 'Laporan_Absensi_' . date('d-m-Y_H-i') . '.csv';

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('No', 'Nama Karyawan', 'Tanggal', 'Jam Masuk', 'Jam Pulang', 'Status', 'Titik Koordinat');

        $callback = function() use($attendances, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            $row = 1;
            foreach ($attendances as $data) {
                fputcsv($file, array(
                    $row++,
                    $data->user->name ?? 'Unknown',
                    $data->clock_in_time ? Carbon::parse($data->clock_in_time)->format('d-m-Y') : '-',
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
}