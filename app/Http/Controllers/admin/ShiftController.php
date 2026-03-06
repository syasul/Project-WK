<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Shifts;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    public function index(Request $request)
    {
        $query = Shifts::query();

        // Filter berdasarkan pencarian nama (opsional, jika nanti ada fitur search)
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter Dropdown Sort
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('name', 'desc');
                    break;
                case 'start_time_asc':
                    $query->orderBy('start_time', 'asc');
                    break;
                case 'duration_desc':
                    // Mengurutkan berdasarkan durasi (perlu raw query atau calculated column, 
                    // disini contoh sederhana pakai raw untuk mysql)
                    $query->orderByRaw('TIMESTAMPDIFF(MINUTE, start_time, end_time) DESC');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
                    break;
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $shifts = $query->paginate(10);
        return view('screens.manageShiftPage', compact('shifts'));
    }

public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        Shifts::create($request->all());

        return redirect()->route('admin.shifts.index')->with('success', 'Shift berhasil ditambahkan.');
    }

    // Perhatikan parameter $id di sini (Laravel Resource default mengirim parameter route)
    public function update(Request $request, $shift_id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        // Gunakan findOrFail dengan $shift_id yang diterima dari route
        $shift = Shifts::findOrFail($shift_id);
        $shift->update($request->all());

        return redirect()->route('admin.shifts.index')->with('success', 'Shift berhasil diperbarui.');
    }

    public function destroy($shift_id)
    {
        $shift = Shifts::findOrFail($shift_id);
        $shift->delete();

        return redirect()->route('admin.shifts.index')->with('success', 'Shift berhasil dihapus.');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids');
        
        if (!is_array($ids) || count($ids) === 0) {
            return redirect()->route('admin.shifts.index')->with('error', 'Tidak ada shift yang dipilih.');
        }

        // FIX: Gunakan 'shift_id' bukan 'id' karena primary key Anda shift_id
        Shifts::whereIn('shift_id', $ids)->delete();

        return redirect()->route('admin.shifts.index')->with('success', count($ids) . ' Shift berhasil dihapus.');
    }
}