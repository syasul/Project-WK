<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Holidays;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    public function index(Request $request)
    {
        $query = Holidays::query();

        // Search Filter (Gunakan 'name' sesuai model)
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Sort Filter
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'date_asc': $query->orderBy('holiday_date', 'asc'); break;
                case 'date_desc': $query->orderBy('holiday_date', 'desc'); break;
                case 'national': $query->where('type', 'national'); break;
                case 'common': $query->where('type', 'common_leave'); break;
                default: $query->orderBy('holiday_date', 'desc'); break;
            }
        } else {
            $query->orderBy('holiday_date', 'desc');
        }

        $holidays = $query->paginate(10);
        return view('screens.manageHolidayPage', compact('holidays'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255', // Sesuai model
            'holiday_date' => 'required|date',
            'type' => 'required|in:national,common_leave',
            'description' => 'nullable|string',
        ]);

        Holidays::create($request->all());

        return redirect()->route('admin.holidays.index')->with('success', 'Hari libur berhasil ditambahkan.');
    }

    public function update(Request $request, $holiday_id)
    {
        // Cari berdasarkan holiday_id (Primary Key)
        $holiday = Holidays::where('holiday_id', $holiday_id)->firstOrFail();

        $request->validate([
            'name' => 'required|string|max:255', // Sesuai model
            'holiday_date' => 'required|date',
            'type' => 'required|in:national,common_leave',
            'description' => 'nullable|string',
        ]);

        $holiday->update($request->all());

        return redirect()->route('admin.holidays.index')->with('success', 'Hari libur berhasil diperbarui.');
    }

    public function destroy($holiday_id)
    {
        $holiday = Holidays::where('holiday_id', $holiday_id)->firstOrFail();
        $holiday->delete();

        return redirect()->route('admin.holidays.index')->with('success', 'Hari libur berhasil dihapus.');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids');
        
        if (!is_array($ids) || count($ids) === 0) {
            return redirect()->back()->with('error', 'Tidak ada data yang dipilih.');
        }

        // Hapus massal menggunakan whereIn pada kolom holiday_id
        Holidays::whereIn('holiday_id', $ids)->delete();

        return redirect()->back()->with('success', count($ids) . ' Hari libur berhasil dihapus.');
    }
}