<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Shifts;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\Rules;


class EmployeeController extends Controller
{
    /**
     * Menampilkan daftar karyawan dengan fitur Search & Sort.
     */
    public function index(Request $request)
    {
        // Eager load 'shift' untuk efisiensi query
        $query = User::with('shift'); 

        // Filter Pencarian Dinamis (Nama, Email, atau Posisi)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('position', 'like', '%' . $search . '%'); // Tambahan cari berdasarkan posisi
            });
        }

        // Filter Pengurutan
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'name_asc': $query->orderBy('name', 'asc'); break;
                case 'name_desc': $query->orderBy('name', 'desc'); break;
                case 'newest': $query->orderBy('created_at', 'desc'); break;
                case 'oldest': $query->orderBy('created_at', 'asc'); break;
                default: $query->orderBy('created_at', 'desc'); break;
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $employees = $query->paginate(10);
        $shifts = \App\Models\Shifts::all();

        return view('screens.manageEmployeePage', compact('employees', 'shifts'));
    }

    // FUNGSI EXPORT DATA KARYAWAN KE CSV
    public function export()
    {
        $employees = User::with('shift')->orderBy('name', 'asc')->get();
        $fileName = 'Data_Karyawan_' . date('d-m-Y') . '.csv';

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        // Header Kolom CSV (Disesuaikan dengan Schema Database)
        $columns = array('No', 'Nama', 'Email', 'No HP', 'Posisi/Jabatan', 'Role', 'Status', 'Shift', 'Tanggal Bergabung');

        $callback = function() use($employees, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            $row = 1;
            foreach ($employees as $data) {
                fputcsv($file, array(
                    $row++,
                    $data->name,
                    $data->email,
                    $data->phone ?? '-',
                    $data->position ?? '-',
                    strtoupper($data->role),
                    strtoupper($data->status),
                    $data->shift->name ?? 'Belum ada shift',
                    $data->created_at ? \Carbon\Carbon::parse($data->created_at)->format('d-m-Y') : '-'
                ));
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // FUNGSI IMPORT DATA KARYAWAN DARI CSV
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt'
        ]);

        $file = $request->file('file');
        $handle = fopen($file->getPathname(), "r");
        
        $header = true;
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            if ($header) { $header = false; continue; } // Lewati judul kolom
            
            // Format CSV: 0=Nama, 1=Email, 2=Password, 3=Phone, 4=Position, 5=Role, 6=Status
            User::updateOrCreate(
                ['email' => $data[1]], // Mencegah duplikat data dengan mengecek Email
                [
                    'name' => $data[0],
                    'password' => bcrypt(!empty($data[2]) ? $data[2] : 'password123'),
                    'phone' => $data[3] ?? null,
                    'position' => $data[4] ?? null,
                    'role' => strtolower($data[5] ?? 'employee'),
                    'status' => strtolower($data[6] ?? 'active'),
                    'email_verified_at' => now(), // Otomatis verified saat di-import
                ]
            );
        }
        fclose($handle);

        return redirect()->back()->with('success', 'Data karyawan berhasil diimport secara massal!');
    }

    /**
     * Menyimpan data karyawan baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', Rules\Password::defaults()],
            'phone' => ['nullable', 'string', 'max:20'],
            'position' => ['nullable', 'string', 'max:100'],
            'role' => ['required', 'in:employee,leader,admin'],
            'shift_id' => ['nullable', 'exists:shifts,shift_id'], // Pastikan exists cek ke kolom shift_id
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'position' => $request->position,
            'role' => $request->role,
            'shift_id' => $request->shift_id,
        ]);

        // Kirim Email Verifikasi (Wajib setting .env SMTP)
        // event(new Registered($user));

        return redirect()->route('admin.employees.index')
            ->with('success', 'Karyawan berhasil ditambahkan & email verifikasi dikirim.');
    }

    /**
     * Memperbarui data karyawan.
     */
    public function update(Request $request, $user_id)
    {
        // Cari User berdasarkan user_id (Bukan id)
        $user = User::where('user_id', $user_id)->firstOrFail();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            // Validasi Unique: Ignore email milik user ini sendiri berdasarkan user_id
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$user->user_id.',user_id'],
            'phone' => ['nullable', 'string', 'max:20'],
            'position' => ['nullable', 'string', 'max:100'],
            'role' => ['required', 'in:employee,leader,admin'],
            'shift_id' => ['nullable', 'exists:shifts,shift_id'],
        ]);

        // Ambil semua data input kecuali password
        $data = $request->except(['password']);

        // Jika kolom password diisi, hash password baru
        if ($request->filled('password')) {
            $request->validate(['password' => Rules\Password::defaults()]);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.employees.index')
            ->with('success', 'Data karyawan berhasil diperbarui.');
    }

    /**
     * Menghapus satu karyawan.
     */
    public function destroy($user_id)
    {
        // Cari dan hapus berdasarkan user_id
        $user = User::where('user_id', $user_id)->firstOrFail();
        $user->delete();

        return redirect()->route('admin.employees.index')
            ->with('success', 'Karyawan berhasil dihapus.');
    }

    /**
     * Menghapus banyak karyawan sekaligus (Bulk Delete).
     */
    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids');
        
        // Validasi input
        if (!is_array($ids) || count($ids) === 0) {
            return redirect()->back()->with('error', 'Tidak ada karyawan yang dipilih.');
        }

        // Hapus massal menggunakan whereIn pada kolom user_id
        User::whereIn('user_id', $ids)->delete();

        return redirect()->back()
            ->with('success', count($ids) . ' Karyawan berhasil dihapus.');
    }
}