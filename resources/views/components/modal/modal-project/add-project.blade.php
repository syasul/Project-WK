<div id="add-project-modal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative w-full max-w-2xl max-h-full">
        <div class="relative bg-white rounded-2xl shadow-xl border border-slate-200">
            <div class="flex items-start justify-between p-4 border-b border-slate-100 rounded-t bg-slate-50/50">
                <div>
                    <h3 class="text-lg font-bold text-slate-900">Tambah Project Baru</h3>
                    <p class="text-xs text-slate-500">Lengkapi informasi project di bawah ini.</p>
                </div>
                <button type="button" class="text-slate-400 bg-transparent hover:bg-slate-100 hover:text-slate-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center" data-modal-hide="add-project-modal">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
            
            <form action="{{ route('admin.projects.store') }}" method="POST">
                @csrf
                <div class="p-6 overflow-y-auto max-h-[70vh]">
                    
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Informasi Utama</h4>
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block mb-1.5 text-sm font-medium text-slate-700">Kode Project <span class="text-red-500">*</span></label>
                            <input type="text" name="project_code" class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" placeholder="PRJ-2025-001" required>
                        </div>
                        <div>
                            <label class="block mb-1.5 text-sm font-medium text-slate-700">Nama Project <span class="text-red-500">*</span></label>
                            <input type="text" name="name" class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" placeholder="Contoh: Web App" required>
                        </div>
                        <div class="col-span-2">
                            <label class="block mb-1.5 text-sm font-medium text-slate-700">Nama Klien</label>
                            <input type="text" name="client_name" class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" placeholder="Nama Client / PT">
                        </div>
                    </div>

                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Operasional</h4>
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block mb-1.5 text-sm font-medium text-slate-700">Tanggal Mulai <span class="text-red-500">*</span></label>
                            <input type="date" name="start_date" class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" required>
                        </div>
                        <div>
                            <label class="block mb-1.5 text-sm font-medium text-slate-700">Tanggal Selesai</label>
                            <input type="date" name="end_date" class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5">
                        </div>
                        <div>
                            <label class="block mb-1.5 text-sm font-medium text-slate-700">Status</label>
                            <select name="status" class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5">
                                <option value="ongoing">Ongoing</option>
                                <option value="planned">Planned</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                        <div>
                            <label class="block mb-1.5 text-sm font-medium text-slate-700">Nilai (Rp)</label>
                            <input type="number" name="project_value" class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" placeholder="0">
                        </div>
                    </div>

                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Lokasi & Penempatan</h4>
                    <div class="grid grid-cols-1 gap-4">
                        
                        <div>
                            <label class="block mb-1.5 text-sm font-medium text-slate-700">Pilih Lokasi Kantor/Project <span class="text-red-500">*</span></label>
                            <select name="location_id" class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" required>
                                <option value="" selected disabled>-- Pilih Lokasi Master --</option>
                                @foreach($locations as $location)
                                    <option value="{{ $location->location_id }}">
                                        {{ $location->name }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-[11px] text-slate-400 mt-1">
                                Koordinat akan mengikuti data Master Lokasi.
                            </p>
                        </div>

                        <div>
                            <label class="block mb-1.5 text-sm font-medium text-slate-700">Deskripsi Project</label>
                            <textarea name="description" rows="3" class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" placeholder="Keterangan tambahan project..."></textarea>
                        </div>
                    </div>

                </div>
                <div class="flex items-center p-6 space-x-2 border-t border-slate-100 rounded-b bg-slate-50">
                    <button type="submit" class="text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-4 focus:outline-none focus:ring-indigo-300 font-medium rounded-xl text-sm px-5 py-2.5 text-center shadow-sm">Simpan</button>
                    <button data-modal-hide="add-project-modal" type="button" class="text-slate-500 bg-white hover:bg-slate-100 focus:ring-4 focus:outline-none focus:ring-indigo-100 rounded-xl border border-slate-200 text-sm font-medium px-5 py-2.5 hover:text-slate-900 focus:z-10">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>