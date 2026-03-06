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

        // Filter Pencarian (Nama atau Email)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
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

        // Pagination 10 item per halaman
        $employees = $query->paginate(10);
        
        // Ambil semua shift untuk dropdown di modal
        $shifts = Shifts::all();

        return view('screens.manageEmployeePage', compact('employees', 'shifts'));
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