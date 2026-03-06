@extends('layouts.app')

@section('title', 'Master Lokasi')

@push('style')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
    .leaflet-pane { z-index: 10 !important; } 
    .leaflet-top, .leaflet-bottom { z-index: 20 !important; }
</style>
@endpush

@section('content')
    <div class="sm:flex sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Master Lokasi</h1>
            <p class="mt-1 text-sm text-slate-500">Atur titik koordinat kantor, gudang, atau site project.</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <button id="btn-add-location" data-modal-target="add-location-modal" data-modal-toggle="add-location-modal" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 transition shadow-sm focus:ring-4 focus:ring-indigo-300">
                <i class="fa-solid fa-plus mr-2"></i> Tambah Lokasi
            </button>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
          
        <div class="p-4 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <button id="bulkDeleteBtn" type="button" 
                    data-modal-target="bulk-delete-modal" 
                    data-modal-toggle="bulk-delete-modal"
                    class="hidden inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-red-600 border border-rose-700 rounded-xl hover:bg-rose-700 focus:z-10 focus:ring-2 focus:ring-rose-100 transition shadow-sm w-full sm:w-auto justify-center cursor-not-allowed" disabled>
                    <i class="fa-regular fa-trash-can mr-2"></i>
                    Hapus Terpilih
                </button>
            </div>

            <div class="flex items-center justify-end gap-2 w-full sm:w-auto sm:ml-auto">
                <button id="dropdownSortButton" data-dropdown-toggle="dropdownSort" data-dropdown-placement="bottom-end" class="inline-flex items-center px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 hover:text-indigo-600 focus:z-10 focus:ring-2 focus:ring-indigo-100 transition shadow-sm w-full sm:w-auto justify-center" type="button">
                    <i class="fa-solid fa-arrow-down-short-wide mr-2 text-slate-400"></i>
                    Urutkan
                    <svg class="w-2.5 h-2.5 ms-2.5 text-slate-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                    </svg>
                </button>

                <div id="dropdownSort" class="z-10 hidden bg-white divide-y divide-slate-100 rounded-xl shadow-xl w-44 border border-slate-100">
                    <ul class="py-2 text-sm text-slate-700" aria-labelledby="dropdownSortButton">
                        <li><a href="{{ route('admin.locations.index', ['sort' => 'name_asc']) }}" class="block px-4 py-2 hover:bg-indigo-50 hover:text-indigo-600">Nama (A-Z)</a></li>
                        <li><a href="{{ route('admin.locations.index', ['sort' => 'radius_desc']) }}" class="block px-4 py-2 hover:bg-indigo-50 hover:text-indigo-600">Radius Terbesar</a></li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- TABEL --}}
        <div class="relative overflow-x-auto">
            <table class="w-full text-sm text-left text-slate-500">
                <thead class="text-xs text-slate-700 uppercase bg-slate-50/50 border-b border-slate-200">
                    <tr>
                        <th class="w-6 px-6 py-4">
                            <input id="selectAllCheckbox" type="checkbox" class="w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 rounded focus:ring-indigo-500 focus:ring-2">
                        </th>
                        <th class="px-6 py-4 font-semibold">Nama Lokasi</th>
                        <th class="px-6 py-4 font-semibold">Koordinat & Radius</th>
                        <th class="px-6 py-4 font-semibold">Leader / PIC</th>
                        <th class="px-6 py-4 font-semibold text-center w-[120px]">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($locations as $location)
                    <tr class="hover:bg-slate-50/50 transition bg-white group/row" id="row-{{ $location->location_id }}">
                        <td class="px-6 py-4">
                            <input type="checkbox" name="ids[]" value="{{ $location->location_id }}" class="bulk-item w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 rounded focus:ring-indigo-500 focus:ring-2">
                        </td>
                        
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-900">{{ $location->name }}</div>
                            <div class="text-xs text-slate-500 mt-1 truncate max-w-xs" title="{{ $location->address }}">
                                {{Str::limit($location->address ?? '-', 40) }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col gap-1">
                                <span class="text-xs font-mono bg-slate-100 px-2 py-0.5 rounded border border-slate-200 w-fit text-slate-600">
                                    {{ $location->latitude }}, {{ $location->longitude }}
                                </span>
                                <span class="text-xs text-slate-500">
                                    Radius: <b class="text-slate-700">{{ $location->radius }}m</b>
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($location->leader)
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs font-bold border border-indigo-200">
                                        {{ substr($location->leader->name, 0, 1) }}
                                    </div>
                                    <div class="text-sm text-slate-700 font-medium">{{ $location->leader->name }}</div>
                                </div>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-500">- Tidak ada -</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            {{-- BUTTON GROUP YANG SUDAH DIRAPIKAN --}}
                            <div class="flex items-center justify-end gap-1 opacity-80 group-hover/row:opacity-100 transition-opacity">
                                
                                {{-- VIEW BUTTON --}}
                                <button type="button" 
                                    class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-full transition btn-view"
                                    data-modal-target="view-location-modal" 
                                    data-modal-toggle="view-location-modal"
                                    data-name="{{ $location->name }}"
                                    data-address="{{ $location->address }}"
                                    data-radius="{{ $location->radius }}"
                                    data-lat="{{ $location->latitude }}"
                                    data-long="{{ $location->longitude }}"
                                    data-leader="{{ $location->leader->name ?? '-' }}">
                                    <i class="fa-regular fa-eye"></i>
                                </button>

                                {{-- EDIT BUTTON --}}
                                <button type="button" 
                                    class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-full transition btn-edit"
                                    data-modal-target="edit-location-modal" 
                                    data-modal-toggle="edit-location-modal"
                                    data-id="{{ $location->location_id }}"
                                    data-name="{{ $location->name }}"
                                    data-address="{{ $location->address }}"
                                    data-radius="{{ $location->radius }}"
                                    data-lat="{{ $location->latitude }}"
                                    data-long="{{ $location->longitude }}"
                                    data-leader-id="{{ $location->leader_id }}">
                                    <i class="fa-solid fa-pen-to-square text-[13px]"></i>
                                </button>

                                {{-- DELETE BUTTON --}}
                                <button type="button"
                                    class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-full transition btn-delete"
                                    data-modal-target="delete-location-modal" 
                                    data-modal-toggle="delete-location-modal"
                                    data-id="{{ $location->location_id }}">
                                    <i class="fa-regular fa-trash-can text-[13px]"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center py-12 text-slate-500">Data lokasi tidak ditemukan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="p-4 border-t border-slate-200 bg-slate-50 rounded-b-2xl">
            {{ $locations->appends(request()->query())->links() }}
        </div>
    </div>

    {{-- INCLUDE MODALS --}}
    @include('components.modal.modal-location.add-location')
    @include('components.modal.modal-location.edit-location')
    @include('components.modal.modal-location.view-location')
    @include('components.modal.modal-location.delete-location')
    @include('components.modal.modal-location.bulk-location')

@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const defaultLat = -6.200000; 
        const defaultLng = 106.816666;
        let mapAdd, markerAdd, circleAdd;
        let mapEdit, markerEdit, circleEdit;
        let mapView, markerView, circleView;

        function fixMapSize(mapInstance) {
            if(!mapInstance) return;
            setTimeout(() => mapInstance.invalidateSize(), 10);
            setTimeout(() => mapInstance.invalidateSize(), 300);
        }

        // --- 1. MODAL ADD ---
        const btnAdd = document.getElementById('btn-add-location');
        if(btnAdd) {
            btnAdd.addEventListener('click', function() {
                setTimeout(() => {
                    if (!mapAdd) {
                        mapAdd = L.map('map-add').setView([defaultLat, defaultLng], 13);
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(mapAdd);
                        
                        mapAdd.on('click', function(e) {
                            const lat = e.latlng.lat.toFixed(6); 
                            const lng = e.latlng.lng.toFixed(6);
                            const r = parseInt(document.getElementById('add-radius').value) || 50;

                            if (markerAdd) mapAdd.removeLayer(markerAdd);
                            markerAdd = L.marker(e.latlng).addTo(mapAdd);
                            
                            if (circleAdd) mapAdd.removeLayer(circleAdd);
                            circleAdd = L.circle(e.latlng, {radius: r}).addTo(mapAdd);

                            document.getElementById('add-latitude').value = lat;
                            document.getElementById('add-longitude').value = lng;
                        });
                    }
                    fixMapSize(mapAdd);
                }, 300);
            });
        }

        // --- 2. MODAL EDIT ---
        document.querySelectorAll('.btn-edit').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.dataset.id;
                const lat = this.dataset.lat;
                const lng = this.dataset.long;
                const radius = this.dataset.radius;

                document.getElementById('edit-location-form').action = `/admin/locations/${id}`;
                document.getElementById('edit-name').value = this.dataset.name;
                document.getElementById('edit-address').value = this.dataset.address;
                document.getElementById('edit-radius').value = radius;
                document.getElementById('edit-leader_id').value = this.dataset.leaderId;
                document.getElementById('edit-latitude').value = lat;
                document.getElementById('edit-longitude').value = lng;

                setTimeout(() => {
                    const curLat = parseFloat(lat) || defaultLat;
                    const curLng = parseFloat(lng) || defaultLng;

                    if (!mapEdit) {
                        mapEdit = L.map('map-edit').setView([curLat, curLng], 15);
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(mapEdit);
                        
                        mapEdit.on('click', function(e) {
                            const r = parseInt(document.getElementById('edit-radius').value) || 50;
                            if (markerEdit) mapEdit.removeLayer(markerEdit);
                            markerEdit = L.marker(e.latlng).addTo(mapEdit);
                            if (circleEdit) mapEdit.removeLayer(circleEdit);
                            circleEdit = L.circle(e.latlng, {radius: r}).addTo(mapEdit);
                            
                            document.getElementById('edit-latitude').value = e.latlng.lat.toFixed(6);
                            document.getElementById('edit-longitude').value = e.latlng.lng.toFixed(6);
                        });
                    } else {
                        mapEdit.setView([curLat, curLng], 15);
                    }
                    
                    if (markerEdit) mapEdit.removeLayer(markerEdit);
                    if (circleEdit) mapEdit.removeLayer(circleEdit);
                    
                    if(lat && lng) {
                        markerEdit = L.marker([curLat, curLng]).addTo(mapEdit);
                        circleEdit = L.circle([curLat, curLng], {radius: radius}).addTo(mapEdit);
                    }
                    fixMapSize(mapEdit);
                }, 300);
            });
        });

        // --- 3. MODAL VIEW ---
        document.querySelectorAll('.btn-view').forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('view-name').textContent = this.dataset.name;
                document.getElementById('view-address').textContent = this.dataset.address || '-';
                document.getElementById('view-leader').textContent = this.dataset.leader;
                document.getElementById('view-coords').textContent = `${this.dataset.lat}, ${this.dataset.long}`;

                setTimeout(() => {
                    const lat = parseFloat(this.dataset.lat);
                    const lng = parseFloat(this.dataset.long);
                    const radius = this.dataset.radius;

                    if (!mapView) {
                        mapView = L.map('map-view').setView([lat, lng], 15);
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(mapView);
                    } else {
                        mapView.setView([lat, lng], 15);
                    }
                    
                    if (markerView) mapView.removeLayer(markerView);
                    if (circleView) mapView.removeLayer(circleView);

                    markerView = L.marker([lat, lng]).addTo(mapView);
                    circleView = L.circle([lat, lng], {radius: radius, color: 'green'}).addTo(mapView);
                    
                    fixMapSize(mapView);
                }, 300);
            });
        });

        // --- 4. LOGIC DELETE SINGLE ---
        document.querySelectorAll('.btn-delete').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.dataset.id;
                document.getElementById('delete-location-form').action = `/admin/locations/${id}`;
            });
        });

        // --- 5. LOGIC BULK DELETE ---
        const selectAll = document.getElementById('selectAllCheckbox');
        const checkboxes = document.querySelectorAll('.bulk-item');
        const bulkBtn = document.getElementById('bulkDeleteBtn');
        const countDisplay = document.getElementById('count-display');
        const bulkInputsContainer = document.getElementById('bulk-delete-inputs');
        const bulkCountSpanModal = document.getElementById('bulk-count');

        function updateBulkState() {
            const checkedBoxes = document.querySelectorAll('.bulk-item:checked');
            const count = checkedBoxes.length;
            if(count > 0) {
                bulkBtn.classList.remove('hidden', 'cursor-not-allowed');
                bulkBtn.removeAttribute('disabled');
                if(countDisplay) countDisplay.textContent = count;
                bulkBtn.innerHTML = `<i class="fa-regular fa-trash-can mr-2"></i> Hapus ${count} Terpilih`;
            } else {
                bulkBtn.classList.add('hidden');
            }
        }

        if(selectAll) {
            selectAll.addEventListener('change', function() {
                checkboxes.forEach(cb => cb.checked = this.checked);
                updateBulkState();
            });
        }

        checkboxes.forEach(cb => {
            cb.addEventListener('change', updateBulkState);
        });

        if(bulkBtn) {
            bulkBtn.addEventListener('click', function() {
                const checkedBoxes = document.querySelectorAll('.bulk-item:checked');
                const ids = Array.from(checkedBoxes).map(cb => cb.value);
                
                if(bulkCountSpanModal) bulkCountSpanModal.textContent = ids.length;
                if(bulkInputsContainer) {
                    bulkInputsContainer.innerHTML = '';
                    ids.forEach(id => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'ids[]';
                        input.value = id;
                        bulkInputsContainer.appendChild(input);
                    });
                }
            });
        }
    });
</script>
@endpush