@extends('layouts.app')

@section('title', 'Manajemen Hari Libur')

@section('content')
    {{-- HEADER --}}
    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Hari Libur & Cuti Bersama</h1>
            <p class="text-sm text-slate-500 mt-1">Atur jadwal tanggal merah dan cuti bersama perusahaan.</p>
        </div>
        
        <div class="flex items-center gap-3">
            <button data-modal-target="add-holiday-modal" data-modal-toggle="add-holiday-modal" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-300 transition shadow-sm shadow-indigo-100">
                <i class="fa-solid fa-calendar-plus mr-2"></i> Tambah Libur
            </button>
        </div>
    </div>

    {{-- CARD WRAPPER --}}
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden mb-8">
        
        {{-- TOOLBAR --}}
        <div class="p-4 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            
            <div class="flex items-center gap-2">
                <button id="bulkDeleteBtn" type="button" 
                    data-modal-target="bulk-delete-modal" 
                    data-modal-toggle="bulk-delete-modal"
                    class="hidden inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-red-600 border border-rose-700 rounded-xl hover:bg-rose-700 focus:z-10 focus:ring-2 focus:ring-rose-100 transition shadow-sm w-full sm:w-auto justify-center">
                    <i class="fa-regular fa-trash-can mr-2"></i>
                    Hapus Terpilih (<span id="count-display">0</span>)
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
                        <li><a href="{{ route('admin.holidays.index', ['sort' => 'date_asc']) }}" class="block px-4 py-2 hover:bg-indigo-50 hover:text-indigo-600">Terdekat</a></li>
                        <li><a href="{{ route('admin.holidays.index', ['sort' => 'date_desc']) }}" class="block px-4 py-2 hover:bg-indigo-50 hover:text-indigo-600">Terjauh</a></li>
                        <li><a href="{{ route('admin.holidays.index', ['sort' => 'national']) }}" class="block px-4 py-2 hover:bg-indigo-50 hover:text-indigo-600">Libur Nasional</a></li>
                        <li><a href="{{ route('admin.holidays.index', ['sort' => 'common']) }}" class="block px-4 py-2 hover:bg-indigo-50 hover:text-indigo-600">Cuti Bersama</a></li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- TABLE --}}
        <div class="relative overflow-x-auto">
            <table class="w-full text-sm text-left text-slate-500">
                <thead class="text-xs text-slate-700 uppercase bg-slate-50/80 border-b border-slate-200">
                    <tr>
                        <th scope="col" class="w-6 px-6 py-4">
                            <input id="select-all" type="checkbox" class="w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 rounded focus:ring-indigo-500 focus:ring-2">
                        </th>
                        <th scope="col" class="px-6 py-4 font-semibold">Nama Hari Libur</th>
                        <th scope="col" class="px-6 py-4 font-semibold">Tanggal</th>
                        <th scope="col" class="px-6 py-4 font-semibold">Jenis</th>
                        <th scope="col" class="px-6 py-4 font-semibold">Keterangan</th>
                        <th scope="col" class="px-6 py-4 font-semibold text-center w-[120px]">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($holidays as $holiday)
                    <tr class="hover:bg-slate-50/80 transition group/row" id="row-{{ $holiday->holiday_id }}">
                        <td class="px-6 py-4">
                            <input type="checkbox" name="ids[]" value="{{ $holiday->holiday_id }}" class="bulk-item w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 rounded focus:ring-indigo-500 focus:ring-2">
                        </td>
                        
                        <td class="px-6 py-4 font-medium text-slate-900">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-rose-50 border border-rose-100 text-rose-500 flex items-center justify-center text-lg shrink-0 group-hover/row:bg-rose-100 transition">
                                    <i class="fa-regular fa-calendar-check"></i>
                                </div>
                                <span class="font-bold text-[15px]">{{ $holiday->name }}</span>
                            </div>
                        </td>
                        
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="font-semibold text-slate-700">
                                    {{ \Carbon\Carbon::parse($holiday->holiday_date)->isoFormat('D MMMM Y') }}
                                </span>
                                <span class="text-xs text-slate-400">
                                    {{ \Carbon\Carbon::parse($holiday->holiday_date)->isoFormat('dddd') }}
                                </span>
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            @if($holiday->type == 'national')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-50 text-rose-700 border border-rose-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Nasional
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-50 text-amber-700 border border-amber-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Cuti Bersama
                                </span>
                            @endif
                        </td>

                        <td class="px-6 py-4">
                            <span class="text-slate-500 truncate max-w-xs block">
                                {{ $holiday->description ?? '-' }}
                            </span>
                        </td>

                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-end gap-1 opacity-80 group-hover/row:opacity-100 transition-opacity">
                                
                                <button type="button" 
                                    data-modal-target="view-holiday-modal" 
                                    data-modal-toggle="view-holiday-modal"
                                    class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-full transition btn-view"
                                    data-name="{{ $holiday->name }}"
                                    data-date="{{ \Carbon\Carbon::parse($holiday->holiday_date)->isoFormat('D MMMM Y') }}"
                                    data-day="{{ \Carbon\Carbon::parse($holiday->holiday_date)->isoFormat('dddd') }}"
                                    data-type="{{ $holiday->type == 'national' ? 'Libur Nasional' : 'Cuti Bersama' }}"
                                    data-desc="{{ $holiday->description ?? 'Tidak ada keterangan tambahan.' }}">
                                    <i class="fa-regular fa-eye"></i>
                                </button>

                                {{-- EDIT BUTTON: PERHATIKAN BAGIAN DATA-DATE --}}
                                <button type="button"
                                    data-modal-target="edit-holiday-modal" 
                                    data-modal-toggle="edit-holiday-modal"
                                    class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-full transition btn-edit"
                                    data-id="{{ $holiday->holiday_id }}"
                                    data-name="{{ $holiday->name }}"
                                    {{-- FIX: Format tanggal harus Y-m-d agar terbaca input type="date" --}}
                                    data-date="{{ \Carbon\Carbon::parse($holiday->holiday_date)->format('Y-m-d') }}"
                                    data-type="{{ $holiday->type }}"
                                    data-desc="{{ $holiday->description }}">
                                    <i class="fa-solid fa-pen-to-square text-[13px]"></i>
                                </button>

                                <button type="button"
                                    data-modal-target="delete-holiday-modal" 
                                    data-modal-toggle="delete-holiday-modal"
                                    class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-full transition btn-delete"
                                    data-id="{{ $holiday->holiday_id }}">
                                    <i class="fa-regular fa-trash-can text-[13px]"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-8 text-slate-500">Belum ada data libur.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($holidays->hasPages())
        <div class="p-4 border-t border-slate-200 bg-white">
            {{ $holidays->links() }}
        </div>
        @endif
    </div>

    @include('components.modal.modal-holiday.add-holiday')
    @include('components.modal.modal-holiday.edit-holiday')
    @include('components.modal.modal-holiday.view-holiday')
    @include('components.modal.modal-holiday.delete-holiday')
    @include('components.modal.modal-holiday.bulk-holiday')

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // --- LOGIC EDIT ---
        const editButtons = document.querySelectorAll('.btn-edit');
        const editForm = document.getElementById('edit-form');
        
        if(editForm) {
            editButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    editForm.action = `/admin/holidays/${id}`;
                    
                    document.getElementById('edit-name').value = this.getAttribute('data-name');
                    document.getElementById('edit-date').value = this.getAttribute('data-date'); // Value sudah Y-m-d
                    document.getElementById('edit-type').value = this.getAttribute('data-type');
                    document.getElementById('edit-desc').value = this.getAttribute('data-desc');
                });
            });
        }

        // ... logic delete, view, bulk delete sama seperti sebelumnya ...
        // (Pastikan logic lainnya tetap ada)

        const deleteButtons = document.querySelectorAll('.btn-delete');
        const deleteForm = document.getElementById('delete-form');
        if(deleteForm) {
            deleteButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    deleteForm.action = `/admin/holidays/${id}`;
                });
            });
        }

        const viewButtons = document.querySelectorAll('.btn-view');
        viewButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('view-name').textContent = this.getAttribute('data-name');
                document.getElementById('view-date').textContent = this.getAttribute('data-date');
                document.getElementById('view-day').textContent = this.getAttribute('data-day');
                document.getElementById('view-desc').textContent = this.getAttribute('data-desc');
                
                const typeSpan = document.getElementById('view-type');
                if(this.getAttribute('data-type') === 'Libur Nasional') {
                    typeSpan.className = "inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-50 text-rose-700 border border-rose-200";
                    typeSpan.textContent = "Libur Nasional";
                } else {
                    typeSpan.className = "inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-50 text-amber-700 border border-amber-200";
                    typeSpan.textContent = "Cuti Bersama";
                }
            });
        });

        const selectAll = document.getElementById('select-all');
        const checkboxes = document.querySelectorAll('.bulk-item');
        const bulkBtn = document.getElementById('bulkDeleteBtn');
        const countDisplay = document.getElementById('count-display');
        const bulkInputsContainer = document.getElementById('bulk-delete-inputs');
        const bulkCountSpanModal = document.getElementById('bulk-count');

        function updateBulkState() {
            const checkedBoxes = document.querySelectorAll('.bulk-item:checked');
            const count = checkedBoxes.length;
            if(count > 0) {
                bulkBtn.classList.remove('hidden');
                countDisplay.textContent = count;
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