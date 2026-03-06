@extends('layouts.app')

@section('title', 'Live Absensi')

@push('style')
{{-- CSS Leaflet & Fix Z-Index --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
    /* Layering Fix agar peta tidak menutupi modal/dropdown */
    .leaflet-pane { z-index: 0 !important; }
    .leaflet-top, .leaflet-bottom { z-index: 1 !important; }
    
    /* Container Peta Utama */
    #live-map-container { 
        height: 400px; 
        width: 100%; 
        border-radius: 0.75rem; 
        overflow: hidden; 
        z-index: 0; 
        background: #f1f5f9; /* Slate-100 placeholder */
    }
</style>
@endpush

@section('content')

    {{-- HEADER & FILTER --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Live Absensi</h1>
            <p class="text-sm text-slate-500 mt-1">Pantau kehadiran karyawan secara real-time hari ini.</p>
        </div>
        
        <div class="flex flex-col sm:flex-row gap-3">
            {{-- Form Filter Tanggal --}}
            <form action="{{ route('admin.attendances.index') }}" method="GET" class="flex items-center gap-2">
                <div class="relative">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                        <i class="fa-regular fa-calendar text-slate-400"></i>
                    </div>
                    <input type="date" name="date" value="{{ $date }}" onchange="this.form.submit()"
                        class="bg-white border border-slate-300 text-slate-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full ps-10 p-2.5 shadow-sm font-medium text-slate-600">
                </div>
            </form>

            <button class="text-white bg-green-600 hover:bg-green-700 font-medium rounded-xl text-sm px-5 py-2.5 shadow-sm transition flex items-center justify-center gap-2">
                <i class="fa-solid fa-file-excel"></i> Export
            </button>
        </div>
    </div>

    {{-- STATISTIK CARDS (Ringkasan Cepat) --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="p-4 bg-white rounded-2xl border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-users"></i>
            </div>
            <div>
                <p class="text-xs text-slate-500 font-bold uppercase tracking-wider">Hadir</p>
                <h3 class="text-xl font-extrabold text-slate-900">{{ $attendances->count() }}</h3>
            </div>
        </div>
        <div class="p-4 bg-white rounded-2xl border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shrink-0">
                <i class="fa-regular fa-circle-check"></i>
            </div>
            <div>
                <p class="text-xs text-slate-500 font-bold uppercase tracking-wider">On Time</p>
                <h3 class="text-xl font-extrabold text-slate-900">{{ $attendances->where('status_attendance', 'on_time')->count() }}</h3>
            </div>
        </div>
        <div class="p-4 bg-white rounded-2xl border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-rose-50 text-rose-600 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-person-running"></i>
            </div>
            <div>
                <p class="text-xs text-slate-500 font-bold uppercase tracking-wider">Telat</p>
                <h3 class="text-xl font-extrabold text-slate-900">{{ $attendances->where('status_attendance', 'late')->count() }}</h3>
            </div>
        </div>
        {{-- Card Izin (Opsional, jika ada logic izin) --}}
        <div class="p-4 bg-white rounded-2xl border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-umbrella-beach"></i>
            </div>
            <div>
                <p class="text-xs text-slate-500 font-bold uppercase tracking-wider">Cuti/Izin</p>
                <h3 class="text-xl font-extrabold text-slate-900">-</h3> 
            </div>
        </div>
    </div>

    {{-- LIVE MAP SECTION --}}
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm mb-8 overflow-hidden">
        <div class="p-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
            <h3 class="font-bold text-slate-800 flex items-center gap-2">
                <i class="fa-solid fa-map-location-dot text-indigo-500"></i>
                Pantauan Lokasi ({{ \Carbon\Carbon::parse($date)->isoFormat('D MMMM Y') }})
            </h3>
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-100">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                Live Data
            </span>
        </div>
        <div class="p-1">
            <div id="live-map-container"></div>
        </div>
    </div>

    {{-- TABEL DATA ABSENSI --}}
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden mb-12">
        
        {{-- Toolbar Tabel (Search) --}}
        <div class="p-4 border-b border-slate-100 flex flex-col sm:flex-row gap-4 justify-between items-center bg-white">
            <form action="{{ route('admin.attendances.index') }}" method="GET" class="relative w-full sm:w-80">
                <input type="hidden" name="date" value="{{ $date }}">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="fa-solid fa-magnifying-glass text-slate-400"></i>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" 
                    class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 p-2.5" 
                    placeholder="Cari nama karyawan...">
            </form>
        </div>

        <div class="relative overflow-x-auto">
            <table class="w-full text-sm text-left text-slate-500">
                <thead class="text-xs text-slate-700 uppercase bg-slate-50/80 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Karyawan</th>
                        <th class="px-6 py-4 font-semibold">Jam Masuk</th>
                        <th class="px-6 py-4 font-semibold">Jam Pulang</th>
                        <th class="px-6 py-4 font-semibold">Status</th>
                        <th class="px-6 py-4 font-semibold">Lokasi & Foto</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($attendances as $attendance)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-sm border border-indigo-200">
                                    {{ substr($attendance->user->name ?? 'U', 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-bold text-slate-900">{{ $attendance->user->name ?? 'User Terhapus' }}</div>
                                    <div class="text-xs text-slate-500 font-mono">ID: EMP-{{ $attendance->user_id }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-mono text-slate-700 font-bold bg-slate-100 px-2 py-1 rounded border border-slate-200">
                                {{ \Carbon\Carbon::parse($attendance->clock_in_time)->format('H:i') }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($attendance->clock_out_time)
                                <span class="font-mono text-slate-700 font-bold bg-slate-100 px-2 py-1 rounded border border-slate-200">
                                    {{ \Carbon\Carbon::parse($attendance->clock_out_time)->format('H:i') }}
                                </span>
                            @else
                                <span class="text-xs text-slate-400 italic font-medium">-- Belum Pulang --</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($attendance->status_attendance == 'late')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-50 text-rose-700 border border-rose-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Terlambat
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Tepat Waktu
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                {{-- Tombol Lihat Lokasi --}}
                                @if($attendance->latitude)
                                    <button type="button" 
                                        class="btn-map flex items-center justify-center w-8 h-8 rounded-lg text-blue-600 bg-blue-50 border border-blue-100 hover:bg-blue-100 hover:border-blue-200 transition shadow-sm"
                                        data-lat="{{ $attendance->latitude }}"
                                        data-lng="{{ $attendance->longitude }}"
                                        data-name="{{ $attendance->user->name ?? 'Karyawan' }}"
                                        title="Lihat Peta">
                                        <i class="fa-solid fa-map-location-dot"></i>
                                    </button>
                                @else
                                    <span class="text-xs text-slate-400 italic">No Loc</span>
                                @endif
                                
                                {{-- Tombol Lihat Foto --}}
                                <button type="button" 
                                    class="btn-photo flex items-center justify-center w-8 h-8 rounded-lg text-indigo-600 bg-indigo-50 border border-indigo-100 hover:bg-indigo-100 hover:border-indigo-200 transition shadow-sm"
                                    data-photo="{{ $attendance->image_url ?? asset('assets/images/no-image.png') }}"
                                    data-name="{{ $attendance->user->name ?? 'Karyawan' }}"
                                    title="Lihat Selfie">
                                    <i class="fa-solid fa-camera"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-12">
                            <div class="flex flex-col items-center justify-center text-slate-400">
                                <i class="fa-regular fa-folder-open text-4xl mb-3 text-slate-200"></i>
                                <p class="text-sm font-medium text-slate-500">Belum ada data absensi hari ini.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- MODAL: MAP DETAIL (POPUP) --}}
    <div id="map-modal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative w-full max-w-2xl max-h-full">
            <div class="relative bg-white rounded-2xl shadow-xl border border-slate-200">
                <div class="flex items-center justify-between p-4 border-b border-slate-100 bg-slate-50/50 rounded-t-2xl">
                    <h3 class="text-lg font-bold text-slate-900" id="map-modal-title">Lokasi Absen</h3>
                    <button type="button" data-modal-hide="map-modal" class="text-slate-400 bg-transparent hover:bg-slate-100 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>
                <div class="p-4">
                    <div id="detail-map" style="height: 350px; width: 100%;" class="rounded-xl border border-slate-200 overflow-hidden relative z-0"></div>
                    <div class="mt-3 flex items-center text-xs text-slate-500 bg-slate-50 p-2 rounded-lg border border-slate-100">
                        <i class="fa-solid fa-location-crosshairs mr-2 text-indigo-500"></i>
                        <span id="map-modal-coords" class="font-mono">-</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL: PHOTO DETAIL --}}
    <div id="photo-modal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-2xl shadow-xl border border-slate-200">
                <div class="flex items-center justify-between p-4 border-b border-slate-100 bg-slate-50/50 rounded-t-2xl">
                    <h3 class="text-lg font-bold text-slate-900" id="photo-modal-title">Bukti Selfie</h3>
                    <button type="button" data-modal-hide="photo-modal" class="text-slate-400 bg-transparent hover:bg-slate-100 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>
                <div class="p-6 flex justify-center bg-slate-50 rounded-b-2xl">
                    <img id="photo-modal-img" src="" alt="Selfie" class="rounded-xl shadow-sm max-h-[60vh] w-full object-cover border border-slate-200 bg-white">
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // --- 1. INISIALISASI LIVE MAP (Peta Besar) ---
        const liveData = @json($mapData ?? []);
        
        // Default Center (Jakarta / Indonesia Tengah)
        // Jika ada data, center ke data pertama
        const defaultCenter = liveData.length > 0 ? [liveData[0].lat, liveData[0].lng] : [-6.200000, 106.816666]; 
        
        const liveMap = L.map('live-map-container').setView(defaultCenter, 10);
        
        // Gunakan Tile CartoDB (Bersih & Cepat)
        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
            attribution: '© OpenStreetMap, © CARTO',
            subdomains: 'abcd',
            maxZoom: 19
        }).addTo(liveMap);

        // Tambahkan Marker
        if(liveData.length > 0) {
            const bounds = [];
            liveData.forEach(item => {
                if(item.lat && item.lng) {
                    const color = item.status === 'late' ? '#e11d48' : '#10b981'; // Rose-600 / Emerald-500
                    
                    const marker = L.circleMarker([item.lat, item.lng], {
                        radius: 8,
                        fillColor: color,
                        color: "#fff",
                        weight: 2,
                        opacity: 1,
                        fillOpacity: 0.9
                    }).addTo(liveMap);
                    
                    marker.bindPopup(`
                        <div class="text-center font-sans">
                            <div class="font-bold text-slate-800 text-sm mb-1">${item.name}</div>
                            <div class="text-xs text-slate-500 bg-slate-100 px-2 py-0.5 rounded inline-block mb-1">
                                <i class="fa-regular fa-clock mr-1"></i> ${item.time} WIB
                            </div>
                            <div class="text-[10px] uppercase font-bold ${item.status === 'late' ? 'text-rose-600' : 'text-emerald-600'}">
                                ${item.status === 'late' ? 'Terlambat' : 'Tepat Waktu'}
                            </div>
                        </div>
                    `);
                    bounds.push([item.lat, item.lng]);
                }
            });
            // Auto zoom fit bounds
            if(bounds.length > 0) {
                liveMap.fitBounds(bounds, {padding: [50, 50], maxZoom: 16});
            }
        }

        // --- 2. MODAL MAP DETAIL (Popup Per User) ---
        let detailMap;
        const mapModalEl = document.getElementById('map-modal');
        const mapModal = new Modal(mapModalEl);

        document.querySelectorAll('.btn-map').forEach(btn => {
            btn.addEventListener('click', function() {
                const lat = parseFloat(this.dataset.lat);
                const lng = parseFloat(this.dataset.lng);
                const name = this.dataset.name;

                document.getElementById('map-modal-title').textContent = "Lokasi: " + name;
                document.getElementById('map-modal-coords').textContent = `${lat}, ${lng}`;
                
                mapModal.show();

                // Fix Map Render Issue
                setTimeout(() => {
                    if(!detailMap) {
                        detailMap = L.map('detail-map').setView([lat, lng], 15);
                        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png').addTo(detailMap);
                    } else {
                        detailMap.setView([lat, lng], 15);
                    }
                    
                    // Clear previous markers
                    detailMap.eachLayer((layer) => {
                        if (layer instanceof L.Marker) detailMap.removeLayer(layer);
                    });

                    L.marker([lat, lng]).addTo(detailMap)
                        .bindPopup(`<span class="font-bold text-sm">${name}</span>`).openPopup();

                    detailMap.invalidateSize();
                }, 300);
            });
        });

        // Manual Close Handlers (Karena JS Flowbite kadang bentrok)
        document.querySelectorAll('[data-modal-hide="map-modal"]').forEach(btn => {
            btn.addEventListener('click', () => mapModal.hide());
        });


        // --- 3. MODAL PHOTO DETAIL ---
        const photoModalEl = document.getElementById('photo-modal');
        const photoModal = new Modal(photoModalEl);

        document.querySelectorAll('.btn-photo').forEach(btn => {
            btn.addEventListener('click', function() {
                const src = this.dataset.photo;
                const name = this.dataset.name;
                
                document.getElementById('photo-modal-title').textContent = "Selfie: " + name;
                
                // Handle Broken Image
                const img = document.getElementById('photo-modal-img');
                img.src = src;
                img.onerror = function() {
                    this.src = 'https://via.placeholder.com/400x300?text=Gambar+Tidak+Ditemukan';
                };
                
                photoModal.show();
            });
        });
        
        document.querySelectorAll('[data-modal-hide="photo-modal"]').forEach(btn => {
            btn.addEventListener('click', () => photoModal.hide());
        });

    });
</script>
@endpush