@extends('layouts.app')

@section('title', 'Dashboard - Admin Panel')

@section('content')
    {{-- HEADER --}}
    <div class="mb-8 flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">
                Selamat Datang di Dashboard Admin! 👋
            </h1>
            <p class="mt-2 text-sm text-slate-500">
                Berikut adalah ringkasan aktivitas absensi hari ini.
            </p>
        </div>
        
        <div class="flex items-center space-x-3">
            <div class="hidden sm:flex items-center text-sm font-medium text-slate-600 bg-white px-4 py-2.5 rounded-xl shadow-sm border border-slate-200">
                <i class="fa-regular fa-calendar mr-2.5 text-indigo-500"></i>
                {{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}
            </div>
            {{-- Tombol Export (Bisa dikembangkan nanti) --}}
            <button type="button" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-300 transition shadow-sm">
                <i class="fa-solid fa-download mr-2"></i> Export Laporan
            </button>
        </div>
    </div>

    {{-- ALERT: PENDING VERIFICATION --}}
    @if(isset($stats['pending_verifications']) && $stats['pending_verifications'] > 0)
        <div class="p-4 mb-8 rounded-xl bg-amber-50 border-l-4 border-amber-500 text-amber-800 flex items-start shadow-sm" role="alert">
            <i class="fa-solid fa-circle-exclamation mt-0.5 mr-3 text-lg text-amber-500"></i>
            <div class="flex-1 md:flex md:justify-between md:items-center">
                <div>
                    <h3 class="text-sm font-bold">Tindakan Diperlukan: Verifikasi Akun</h3>
                    <div class="mt-1 text-sm">
                        Terdapat <span class="font-bold">{{ $stats['pending_verifications'] }} karyawan baru</span> yang belum memverifikasi email mereka.
                    </div>
                </div>
                <a href="{{ route('admin.employees.index') }}" class="mt-3 md:mt-0 ml-auto whitespace-nowrap text-white bg-amber-600 hover:bg-amber-700 focus:ring-4 focus:ring-amber-300 font-medium rounded-lg text-xs px-4 py-2 inline-flex items-center transition shadow-sm">
                    Lihat Data <i class="fa-solid fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- KOLOM KIRI (STATS & SHORTCUTS) --}}
        <div class="lg:col-span-2 space-y-8">
            
            {{-- GRID STATISTIK --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 hover:border-indigo-200 transition group relative overflow-hidden">
                    <div class="flex justify-between items-start relative z-10">
                        <div>
                            <p class="text-sm font-medium text-slate-500">Hadir Hari Ini</p>
                            <h3 class="text-4xl font-extrabold text-slate-900 mt-3">{{ $stats['attendance_today'] ?? 0 }}</h3>
                            <div class="mt-3 flex items-center text-xs font-medium text-emerald-600 bg-emerald-50 w-fit px-2 py-1 rounded-full border border-emerald-100">
                                <i class="fa-solid fa-check mr-1"></i> Absensi Masuk
                            </div>
                        </div>
                        <div class="p-3 rounded-xl bg-emerald-50 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition">
                            <i class="fa-solid fa-user-check text-2xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 hover:border-rose-200 transition group relative overflow-hidden">
                    <div class="flex justify-between items-start relative z-10">
                        <div>
                            <p class="text-sm font-medium text-slate-500">Terlambat</p>
                            <h3 class="text-4xl font-extrabold text-slate-900 mt-3">{{ $stats['late_today'] ?? 0 }}</h3>
                            <div class="mt-3 flex items-center text-xs font-medium text-rose-600 bg-rose-50 w-fit px-2 py-1 rounded-full border border-rose-100">
                                Butuh Perhatian
                            </div>
                        </div>
                        <div class="p-3 rounded-xl bg-rose-50 text-rose-600 group-hover:bg-rose-600 group-hover:text-white transition">
                            <i class="fa-solid fa-user-clock text-2xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 hover:border-blue-200 transition group relative overflow-hidden">
                    <div class="flex justify-between items-start relative z-10">
                        <div>
                            <p class="text-sm font-medium text-slate-500">Total Karyawan</p>
                            <h3 class="text-4xl font-extrabold text-slate-900 mt-3">{{ $stats['total_employees'] ?? 0 }}</h3>
                             <p class="mt-3 text-xs text-slate-400 font-medium">Aktif & Terdaftar</p>
                        </div>
                        <div class="p-3 rounded-xl bg-blue-50 text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition">
                            <i class="fa-solid fa-users text-2xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 hover:border-indigo-200 transition group relative overflow-hidden">
                    <div class="flex justify-between items-start relative z-10">
                        <div>
                            <p class="text-sm font-medium text-slate-500">Proyek Berjalan</p>
                            <h3 class="text-4xl font-extrabold text-slate-900 mt-3">{{ $stats['projects_active'] ?? 0 }}</h3>
                            <div class="mt-3 flex items-center text-xs font-medium text-indigo-600 bg-indigo-50 w-fit px-2 py-1 rounded-full border border-indigo-100">
                                Status Ongoing
                            </div>
                        </div>
                        <div class="p-3 rounded-xl bg-indigo-50 text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition">
                            <i class="fa-solid fa-briefcase text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SHORTCUTS --}}
            <div>
                <h3 class="text-lg font-bold text-slate-800 mb-4">Akses Cepat</h3>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    
                    <a href="{{ route('admin.employees.index') }}" class="group flex flex-col items-center justify-center p-5 bg-white rounded-2xl shadow-sm border-2 border-dashed border-slate-200 hover:border-indigo-400 hover:bg-indigo-50/50 transition cursor-pointer">
                        <div class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center mb-3 group-hover:scale-110 group-hover:bg-indigo-200 transition">
                            <i class="fa-solid fa-user-plus text-indigo-600"></i>
                        </div>
                        <span class="text-sm font-semibold text-slate-600 group-hover:text-indigo-700">Tambah User</span>
                    </a>
                    
                    <a href="{{ route('admin.attendances.map') }}" class="group flex flex-col items-center justify-center p-5 bg-white rounded-2xl shadow-sm border-2 border-dashed border-slate-200 hover:border-blue-400 hover:bg-blue-50/50 transition cursor-pointer">
                        <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center mb-3 group-hover:scale-110 group-hover:bg-blue-200 transition">
                            <i class="fa-solid fa-map-location-dot text-blue-600"></i>
                        </div>
                        <span class="text-sm font-semibold text-slate-600 group-hover:text-blue-700">Live Maps</span>
                    </a>

                    <a href="{{ route('admin.projects.index') }}" class="group flex flex-col items-center justify-center p-5 bg-white rounded-2xl shadow-sm border-2 border-dashed border-slate-200 hover:border-emerald-400 hover:bg-emerald-50/50 transition cursor-pointer">
                        <div class="w-12 h-12 rounded-full bg-emerald-100 flex items-center justify-center mb-3 group-hover:scale-110 group-hover:bg-emerald-200 transition">
                            <i class="fa-solid fa-file-circle-plus text-emerald-600"></i>
                        </div>
                        <span class="text-sm font-semibold text-slate-600 group-hover:text-emerald-700">Proyek Baru</span>
                    </a>

                     <a href="{{ route('admin.shifts.index') }}" class="group flex flex-col items-center justify-center p-5 bg-white rounded-2xl shadow-sm border-2 border-dashed border-slate-200 hover:border-purple-400 hover:bg-purple-50/50 transition cursor-pointer">
                        <div class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center mb-3 group-hover:scale-110 group-hover:bg-purple-200 transition">
                            <i class="fa-regular fa-clock text-purple-600"></i>
                        </div>
                        <span class="text-sm font-semibold text-slate-600 group-hover:text-purple-700">Atur Shift</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN (STATUS, HOLIDAYS, RECENT ACTIVITY) --}}
        <div class="space-y-8">
            
            {{-- SYSTEM STATUS --}}
            <div class="rounded-3xl bg-slate-800 p-6 text-white relative overflow-hidden shadow-xl">
                <div class="relative z-10">
                    <h3 class="text-lg font-bold">System Status</h3>
                    <p class="text-slate-300 text-sm mb-6 opacity-90">Layanan berjalan normal.</p>

                    <div class="space-y-5">
                        <div>
                            <div class="flex justify-between items-center text-sm mb-2">
                                <span class="text-slate-300 font-medium">Server Load</span>
                                <span class="font-bold">Stabil (24%)</span>
                            </div>
                            <div class="w-full bg-slate-600 rounded-full h-2">
                                <div class="bg-emerald-400 h-2 rounded-full shadow-[0_0_10px_rgba(52,211,153,0.6)]" style="width: 24%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- HARI LIBUR TERDEKAT --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-bold text-slate-800 text-sm">Hari Libur Terdekat</h3>
                    <a href="{{ route('admin.holidays.index') }}" class="text-xs text-indigo-600 font-medium hover:underline">Lihat Semua</a>
                </div>
                
                <div class="space-y-3">
                    @forelse($upcoming_holidays as $holiday)
                    <div class="flex items-center gap-4 p-3 bg-slate-50 rounded-xl border border-slate-100">
                        <div class="flex-col items-center justify-center text-center w-10 shrink-0">
                            <span class="text-[10px] font-bold text-rose-500 uppercase block">
                                {{ \Carbon\Carbon::parse($holiday->holiday_date)->format('M') }}
                            </span>
                            <span class="text-xl font-bold text-slate-800 block leading-none">
                                {{ \Carbon\Carbon::parse($holiday->holiday_date)->format('d') }}
                            </span>
                        </div>
                        <div class="h-8 w-px bg-slate-200"></div>
                        <div>
                            <h4 class="text-sm font-bold text-slate-800">{{ $holiday->name }}</h4>
                            <p class="text-xs text-slate-500">{{ $holiday->type == 'national' ? 'Libur Nasional' : 'Cuti Bersama' }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4 text-xs text-slate-400">
                        Tidak ada hari libur terdekat.
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- AKTIVITAS TERBARU --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center">
                    <h3 class="font-bold text-slate-800">Absensi Terbaru</h3>
                    <a href="{{ route('admin.attendances.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-700 hover:underline">Lihat Semua</a>
                </div>
                <div class="relative overflow-x-auto">
                    <table class="w-full text-sm text-left text-slate-500">
                        <thead class="text-xs text-slate-700 uppercase bg-slate-50">
                            <tr>
                                <th scope="col" class="px-6 py-3">Karyawan</th>
                                <th scope="col" class="px-6 py-3">Jam Masuk</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($recent_activities as $activity)
                            <tr class="bg-white hover:bg-slate-50 transition">
                                <td class="px-6 py-4 font-medium text-slate-900 flex items-center">
                                    <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs font-bold mr-3 border border-indigo-200">
                                        {{ substr($activity->user->name, 0, 1) }}
                                    </div>
                                    <div class="flex flex-col">
                                        <span>{{ $activity->user->name }}</span>
                                        <span class="text-[10px] text-slate-400 font-normal">
                                            {{ \Carbon\Carbon::parse($activity->clock_in_time)->format('H:i') }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    @if($activity->status_attendance == 'late')
                                        <span class="inline-flex items-center bg-rose-100 text-rose-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                            Terlambat
                                        </span>
                                    @else
                                        <span class="inline-flex items-center bg-emerald-100 text-emerald-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                            Tepat Waktu
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="text-center py-6 text-xs text-slate-400">Belum ada aktivitas absensi hari ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection