<div id="add-holiday-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-lg max-h-full">
        <div class="relative bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="flex items-center justify-between p-4 md:p-5 border-b border-slate-100 bg-slate-50/50">
                <h3 class="text-lg font-bold text-slate-800">
                    Tambah Hari Libur
                </h3>
                <button type="button" class="text-slate-400 bg-transparent hover:bg-slate-100 hover:text-slate-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" data-modal-toggle="add-holiday-modal">
                    <i class="fa-solid fa-xmark text-lg"></i>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            
            <form action="{{ route('admin.holidays.store') }}" method="POST" class="p-4 md:p-6">
                @csrf
                <div class="grid gap-y-5">
                    
                    <div>
                        <label for="name" class="block mb-2 text-sm font-semibold text-slate-700">Nama Hari Libur <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" class="bg-white border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 shadow-sm" placeholder="Contoh: Tahun Baru Imlek" required>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="holiday_date" class="block mb-2 text-sm font-semibold text-slate-700">Tanggal <span class="text-rose-500">*</span></label>
                            <input type="date" name="holiday_date" class="bg-white border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 shadow-sm" required>
                        </div>
                        <div>
                            <label for="type" class="block mb-2 text-sm font-semibold text-slate-700">Jenis <span class="text-rose-500">*</span></label>
                            <select name="type" class="bg-white border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 shadow-sm">
                                <option value="national">Libur Nasional</option>
                                <option value="common_leave">Cuti Bersama</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="description" class="block mb-2 text-sm font-semibold text-slate-700">Keterangan (Opsional)</label>
                        <textarea name="description" rows="3" class="bg-white border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 shadow-sm" placeholder="Tambahkan catatan jika perlu..."></textarea>
                    </div>

                </div>
                
                <div class="flex items-center justify-end space-x-3 border-t border-slate-100 pt-5 mt-2">
                    <button type="button" data-modal-toggle="add-holiday-modal" class="text-slate-700 bg-white border border-slate-300 focus:ring-4 focus:outline-none focus:ring-slate-200 font-medium rounded-lg text-sm px-5 py-2.5 hover:bg-slate-50 hover:text-slate-900 focus:z-10 transition">Batal</button>
                    <button type="submit" class="text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-4 focus:outline-none focus:ring-indigo-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center transition shadow-sm">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>