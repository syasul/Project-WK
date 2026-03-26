<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Locations;
use App\Models\Projects;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        // Eager load 'location' agar hemat query
        $query = Projects::with('location');

        // Filter Pencarian
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('name', 'like', '%' . $search . '%')
                  ->orWhere('project_code', 'like', '%' . $search . '%')
                  ->orWhere('client_name', 'like', '%' . $search . '%');
        }

        // Filter Sorting
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'name': $query->orderBy('name', 'asc'); break;
                case 'date': $query->orderBy('start_date', 'desc'); break;
                case 'value': $query->orderBy('project_value', 'desc'); break;
                default: $query->latest(); break;
            }
        } else {
            $query->latest();
        }

        $projects = $query->paginate(10);
        $locations = Locations::all(); // Untuk dropdown di modal

        return view('screens.manageProjectPage', compact('projects', 'locations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'project_code' => 'required|unique:projects,project_code',
            'name' => 'required|string|max:255',
            'client_name' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'location_id' => 'required|exists:locations,location_id', // Validasi ke tabel locations
            'status' => 'required',
            'project_value' => 'nullable|numeric',
        ]);

        Projects::create($request->all());

        return redirect()->route('admin.projects.index')->with('success', 'Project berhasil ditambahkan.');
    }

    public function update(Request $request, $project_id)
    {
        $project = Projects::where('project_id', $project_id)->firstOrFail();

        $request->validate([
            // Unique ignore current ID
            'project_code' => 'required|unique:projects,project_code,' . $project->project_id . ',project_id',
            'name' => 'required|string|max:255',
            'client_name' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'location_id' => 'required|exists:locations,location_id',
            'status' => 'required',
            'project_value' => 'nullable|numeric',
        ]);

        $project->update($request->all());

        return redirect()->route('admin.projects.index')->with('success', 'Project berhasil diperbarui.');
    }

    public function destroy($project_id)
    {
        $project = Projects::where('project_id', $project_id)->firstOrFail();
        $project->delete();

        return redirect()->route('admin.projects.index')->with('success', 'Project berhasil dihapus.');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids');
        
        if (!is_array($ids) || count($ids) === 0) {
            return redirect()->back()->with('error', 'Tidak ada project yang dipilih.');
        }

        // Hapus berdasarkan array ID (Primary Key: project_id)
        Projects::whereIn('project_id', $ids)->delete();

        return redirect()->back()->with('success', count($ids) . ' Project berhasil dihapus.');
    }

    public function export(Request $request)
    {
        $query = Projects::with('location');

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('client_name', 'like', '%' . $search . '%')
                  ->orWhere('project_code', 'like', '%' . $search . '%');
            });
        }

        $projects = $query->orderBy('created_at', 'desc')->get();
        $fileName = 'Data_Proyek_' . date('d-m-Y') . '.csv';

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['No', 'Kode Proyek', 'Nama Proyek', 'Klien', 'Lokasi (Master)', 'Alamat Lengkap', 'Nilai Proyek (Rp)', 'Status Proyek', 'Status Pembayaran', 'Mulai', 'Selesai'];

        $callback = function() use($projects, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            $row = 1;
            foreach ($projects as $data) {
                fputcsv($file, [
                    $row++,
                    $data->project_code,
                    $data->name,
                    $data->client_name ?? '-',
                    $data->location->name ?? '-',
                    $data->address ?? '-',
                    $data->project_value ?? '0',
                    strtoupper($data->status),
                    strtoupper($data->payment_status),
                    $data->start_date ? Carbon::parse($data->start_date)->format('d-m-Y') : '-',
                    $data->end_date ? Carbon::parse($data->end_date)->format('d-m-Y') : '-'
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}