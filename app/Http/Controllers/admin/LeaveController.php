<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Leaves;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LeaveController extends Controller
{
    public function index(Request $request)
    {
        $today = Carbon::today();
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // 1. HITUNG STATISTIK DINAMIS
        $pendingCount = Leaves::where('status', 'pending')->count();
        $todayCount = Leaves::whereDate('start_date', '<=', $today)
                            ->whereDate('end_date', '>=', $today)
                            ->where('status', 'approved')->count();
        $monthCount = Leaves::whereMonth('start_date', $currentMonth)
                            ->whereYear('start_date', $currentYear)->count();

        // 2. QUERY DATA BERSAMA RELASI USER
        $query = Leaves::with('user');

        // Filter Pencarian (Search Dinamis)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                // Cari berdasarkan nama karyawan atau alasan izin
                $q->whereHas('user', function($u) use ($search) {
                    $u->where('name', 'like', '%' . $search . '%');
                })->orWhere('reason', 'like', '%' . $search . '%');
            });
        }

        // Filter Dropdown Status
        if ($request->has('status') && in_array($request->status, ['pending', 'approved', 'rejected'])) {
            $query->where('status', $request->status);
        }

        $leaves = $query->orderBy('created_at', 'desc')->paginate(10);
        $users = User::all();

        return view('screens.manageLeavePage', compact('leaves', 'users', 'pendingCount', 'todayCount', 'monthCount'));
    }

    // FUNGSI UNTUK MENYIMPAN PENGAJUAN IZIN BARU
    public function store(Request $request)
    {
        // 1. Validasi Inputan Form
        $validated = $request->validate([
            'user_id'    => 'required|exists:users,user_id', // Pastikan ID user valid
            'type'       => 'required|in:sick,permit,annual', // Sesuai dengan ENUM database
            'start_date' => 'required|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date', // End date tidak boleh sebelum start date
            'reason'     => 'required|string',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048', // Maksimal 2MB
        ], [
            // Kustomisasi pesan error (opsional biar rapi)
            'end_date.after_or_equal' => 'Tanggal selesai tidak boleh lebih awal dari tanggal mulai.',
            'attachment.max' => 'Ukuran file lampiran maksimal 2MB.',
            'attachment.mimes' => 'Format lampiran harus berupa JPG, PNG, atau PDF.'
        ]);

        // 2. Proses Upload File Lampiran (Jika Ada)
        if ($request->hasFile('attachment')) {
            // File akan disimpan di folder storage/app/public/leaves
            $path = $request->file('attachment')->store('leaves', 'public');
            $validated['attachment'] = $path;
        }

        // 3. Simpan Data ke Database (Status otomatis 'pending' dari default schema)
        Leaves::create($validated);

        // 4. Kembalikan ke halaman sebelumnya dengan pesan sukses
        return back()->with('success', 'Pengajuan izin berhasil ditambahkan dan menunggu persetujuan.');
    }

    // FUNGSI EXPORT CSV
    public function export(Request $request)
    {
        // Ambil data (Bisa disesuaikan dengan filter yang sedang aktif)
        $query = Leaves::with('user')->orderBy('created_at', 'desc');
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        $leaves = $query->get();

        $fileName = 'Data_Cuti_Izin_' . date('d-m-Y') . '.csv';

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('No', 'Nama Karyawan', 'Jenis Izin', 'Tanggal Mulai', 'Tanggal Selesai', 'Alasan', 'Status');

        $callback = function() use($leaves, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            $row = 1;
            foreach ($leaves as $data) {
                fputcsv($file, array(
                    $row++,
                    $data->user->name ?? 'Unknown',
                    strtoupper(str_replace('_', ' ', $data->type)),
                    Carbon::parse($data->start_date)->format('d-m-Y'),
                    Carbon::parse($data->end_date)->format('d-m-Y'),
                    $data->reason,
                    strtoupper($data->status)
                ));
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // FUNGSI UPDATE STATUS: APPROVE
    public function approveLeave($leave_id)
    {
        $leave = Leaves::findOrFail($leave_id);
        $leave->update(['status' => 'approved']);
        return back()->with('success', 'Pengajuan izin berhasil disetujui!');
    }

    // FUNGSI UPDATE STATUS: REJECT
    public function rejectLeave($leave_id)
    {
        $leave = Leaves::findOrFail($leave_id);
        $leave->update(['status' => 'rejected']);
        return back()->with('success', 'Pengajuan izin ditolak.');
    }
}
