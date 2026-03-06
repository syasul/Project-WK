<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Locations;
use App\Models\User;
use Illuminate\Http\Request;

class LocationsController extends Controller
{
    public function index(Request $request)
    {
        $query = Locations::with('leader');

        // Filter Pencarian
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('name', 'like', '%' . $search . '%')
                  ->orWhere('address', 'like', '%' . $search . '%');
        }

        // Filter Sorting
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'name_asc': $query->orderBy('name', 'asc'); break;
                case 'name_desc': $query->orderBy('name', 'desc'); break;
                case 'radius_asc': $query->orderBy('radius', 'asc'); break;
                case 'radius_desc': $query->orderBy('radius', 'desc'); break;
                default: $query->latest(); break;
            }
        } else {
            $query->latest();
        }

        $locations = $query->paginate(10);
        
        // Ambil User Leader untuk dropdown
        $leaders = User::where('role', 'leader')->get(); 

        return view('screens.manageLocationPage', compact('locations', 'leaders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius' => 'nullable|numeric|min:1',
            'leader_id' => 'nullable|exists:users,user_id',
            'address' => 'nullable|string',
        ]);

        Locations::create([
            'name' => $request->name,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'radius' => $request->radius ?? 50,
            'leader_id' => $request->leader_id,
            'address' => $request->address,
        ]);

        return redirect()->route('admin.locations.index')->with('success', 'Lokasi berhasil ditambahkan.');
    }

    public function update(Request $request, $location_id)
    {
        $location = Locations::where('location_id', $location_id)->firstOrFail();

        $request->validate([
            'name' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius' => 'nullable|numeric|min:1',
            'leader_id' => 'nullable|exists:users,user_id',
            'address' => 'nullable|string',
        ]);

        $location->update([
            'name' => $request->name,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'radius' => $request->radius ?? 50,
            'leader_id' => $request->leader_id,
            'address' => $request->address,
        ]);

        return redirect()->route('admin.locations.index')->with('success', 'Data lokasi berhasil diperbarui.');
    }

    public function destroy($location_id)
    {
        $location = Locations::where('location_id', $location_id)->firstOrFail();
        $location->delete();

        return redirect()->route('admin.locations.index')->with('success', 'Lokasi berhasil dihapus.');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids');
        
        if (!is_array($ids) || count($ids) === 0) {
            return redirect()->back()->with('error', 'Tidak ada lokasi yang dipilih.');
        }

        // Hapus berdasarkan array ID
        Locations::whereIn('location_id', $ids)->delete();

        return redirect()->back()->with('success', count($ids) . ' Lokasi berhasil dihapus.');
    }
}