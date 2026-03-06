@extends('layouts.app')

@section('title', 'Manajemen Cuti & Izin')

@section('content')
    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Pengajuan Cuti & Izin</h1>
            <p class="text-sm text-slate-500 mt-1">Kelola permohonan ketidakhadiran karyawan.</p>
        </div>
        
        <div class="flex items-center gap-3">
             <button type="button" data-modal-target="export-modal" data-modal-toggle="export-modal" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 hover:text-slate-900 focus:ring-4 focus:ring-slate-100 transition shadow-sm">
                <i class="fa-solid fa-file-export mr-2 text-slate-400"></i> Export
            </button>

            <button data-modal-target="add-leave-modal" data-modal-toggle="add-leave-modal" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-300 transition shadow-sm shadow-indigo-100">
                <i class="fa-solid fa-plus mr-2"></i> Ajukan Izin
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        <div class="p-4 bg-white border border-slate-200 rounded-xl shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-slate-500 uppercase">Menunggu Persetujuan</p>
                <h3 class="text-2xl font-bold text-slate-800 mt-1">12</h3>
            </div>
            <div class="w-10 h-10 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-lg border border-amber-100">
                <i class="fa-regular fa-clock"></i>
            </div>
        </div>
        <div class="p-4 bg-white border border-slate-200 rounded-xl shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-slate-500 uppercase">Izin Hari Ini</p>
                <h3 class="text-2xl font-bold text-slate-800 mt-1">4</h3>
            </div>
            <div class="w-10 h-10 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-lg border border-indigo-100">
                <i class="fa-regular fa-calendar-check"></i>
            </div>
        </div>
        <div class="p-4 bg-white border border-slate-200 rounded-xl shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-slate-500 uppercase">Total Bulan Ini</p>
                <h3 class="text-2xl font-bold text-slate-800 mt-1">28</h3>
            </div>
            <div class="w-10 h-10 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center text-lg border border-teal-100">
                <i class="fa-solid fa-chart-pie"></i>
            </div>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden mb-8">
        
        <div class="p-4 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">

            <div class="flex items-center justify-end gap-2 w-full sm:w-auto sm:ml-auto">
                <button id="dropdownSortButton" data-dropdown-toggle="dropdownSort" data-dropdown-placement="bottom-end" class="inline-flex items-center px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 hover:text-indigo-600 focus:z-10 focus:ring-2 focus:ring-indigo-100 transition shadow-sm w-full sm:w-auto justify-center" type="button">
                    <i class="fa-solid fa-arrow-down-short-wide mr-2 text-slate-400"></i>
                    Semua Status
                </button>

                <div id="dropdownSort" class="z-10 hidden bg-white divide-y divide-slate-100 rounded-xl shadow-xl w-44 border border-slate-100">
                    <ul class="py-2 text-sm text-slate-700" aria-labelledby="dropdownSortButton">
                        <li><a href="#" class="block px-4 py-2 hover:bg-indigo-50 hover:text-indigo-600">Semua Status</a></li>
                         <li><a href="{{ route('admin.projects.index', ['sort' => 'pending']) }}" class="block px-4 py-2 hover:bg-indigo-50 hover:text-indigo-600">Menunggu</a></li>
                        <li><a href="{{ route('admin.projects.index', ['sort' => 'approved']) }}" class="block px-4 py-2 hover:bg-indigo-50 hover:text-indigo-600">Disetujui</a></li>
                        <li><a href="{{ route('admin.projects.index', ['sort' => 'rejected']) }}" class="block px-4 py-2 hover:bg-indigo-50 hover:text-indigo-600">Ditolak</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="relative overflow-x-auto">
            <table class="w-full text-sm text-left text-slate-500">
                <thead class="text-xs text-slate-700 uppercase bg-slate-50/80 border-b border-slate-200">
                    <tr>
                        <th scope="col" class="px-6 py-4 font-semibold">Karyawan</th>
                        <th scope="col" class="px-6 py-4 font-semibold">Jenis Izin</th>
                        <th scope="col" class="px-6 py-4 font-semibold">Tanggal & Durasi</th>
                        <th scope="col" class="px-6 py-4 font-semibold">Status</th>
                        <th scope="col" class="px-6 py-4 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($leaves as $leave)
                    <tr class="hover:bg-slate-50/80 transition group/row">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-sm">
                                    {{ substr($leave->user->name, 0, 2) }}
                                </div>
                                <div>
                                    <div class="text-sm font-semibold text-slate-900">{{ $leave->user->name }}</div>
                                    <div class="text-xs text-slate-500">{{ $leave->user->divisi ?? 'Staff' }}</div>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            @if($leave->type == 'sakit')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-rose-50 text-rose-700 border border-rose-100">
                                    <i class="fa-solid fa-notes-medical mr-1.5"></i> Sakit
                                </span>
                            @elseif($leave->type == 'cuti_tahunan')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-sky-50 text-sky-700 border border-sky-100">
                                    <i class="fa-solid fa-umbrella-beach mr-1.5"></i> Cuti Tahunan
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-slate-100 text-slate-700 border border-slate-200">
                                    <i class="fa-regular fa-id-card mr-1.5"></i> Izin Lain
                                </span>
                            @endif
                        </td>

                        <td class="px-6 py-4">
                            <div class="text-slate-900 font-medium text-sm">
                                {{ \Carbon\Carbon::parse($leave->start_date)->format('d M') }} - {{ \Carbon\Carbon::parse($leave->end_date)->format('d M Y') }}
                            </div>
                            <div class="text-xs text-slate-500 mt-0.5 font-mono">
                                {{ \Carbon\Carbon::parse($leave->start_date)->diffInDays(\Carbon\Carbon::parse($leave->end_date)) + 1 }} Hari
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            @if($leave->status == 'pending')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-700 border border-amber-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-2 animate-pulse"></span>
                                    Menunggu
                                </span>
                            @elseif($leave->status == 'approved')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <i class="fa-solid fa-check mr-1.5"></i> Disetujui
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-50 text-red-700 border border-red-200">
                                    <i class="fa-solid fa-xmark mr-1.5"></i> Ditolak
                                </span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-center">
                            <button type="button" 
                                data-modal-target="view-leave-modal" 
                                data-modal-toggle="view-leave-modal"
                                class="inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-slate-500 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 hover:text-indigo-600 focus:z-10 focus:ring-2 focus:ring-indigo-100 transition shadow-sm btn-detail"
                                data-id="{{ $leave->id }}"
                                data-user="{{ $leave->user->name }}"
                                data-type="{{ $leave->type }}"
                                data-reason="{{ $leave->reason }}"
                                data-dates="{{ \Carbon\Carbon::parse($leave->start_date)->format('d M') }} - {{ \Carbon\Carbon::parse($leave->end_date)->format('d M Y') }}"
                                data-status="{{ $leave->status }}"
                                data-attachment="{{ $leave->attachment_url ?? '#' }}">
                                Detail
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center text-slate-400">
                            <i class="fa-regular fa-folder-open text-4xl mb-3 opacity-50"></i>
                            <p>Tidak ada data pengajuan cuti.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="p-4 border-t border-slate-200 bg-white">
            {{ $leaves->links() }}
        </div>
    </div>

    @include('components.modal.modal-leaves.add-leave')
    @include('components.modal.modal-leaves.view-leave')

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const detailButtons = document.querySelectorAll('.btn-detail');
        
        detailButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                // Populate data ke Modal View
                document.getElementById('detail-user').textContent = this.dataset.user;
                document.getElementById('detail-dates').textContent = this.dataset.dates;
                document.getElementById('detail-reason').textContent = this.dataset.reason;
                
                // Handle Action Form URLs
                const id = this.dataset.id;
                document.getElementById('form-approve').action = `/admin/leaves/${id}/approve`;
                document.getElementById('form-reject').action = `/admin/leaves/${id}/reject`;

                // Logic Show/Hide Action Buttons based on status
                const actionContainer = document.getElementById('action-buttons');
                if(this.dataset.status === 'pending') {
                    actionContainer.classList.remove('hidden');
                } else {
                    actionContainer.classList.add('hidden');
                }
            });
        });
    });
</script>
@endpush