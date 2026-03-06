<div id="add-leave-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full backdrop-blur-sm bg-slate-900/40">
    <div class="relative p-4 w-full max-w-lg max-h-full">
        <div class="relative bg-white rounded-2xl shadow-2xl ring-1 ring-slate-900/5">
            
            <div class="flex items-center justify-between p-4 md:p-5 border-b border-slate-100 rounded-t-2xl bg-slate-50/50">
                <h3 class="text-lg font-bold text-slate-800">
                    Form Pengajuan Izin
                </h3>
                <button type="button" class="text-slate-400 bg-transparent hover:bg-slate-100 hover:text-slate-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" data-modal-toggle="add-leave-modal">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form action="{{ route('admin.leaves.store') }}" method="POST" enctype="multipart/form-data" class="p-4 md:p-5 space-y-4">
                @csrf
                
                <div>
                    <label class="block mb-2 text-sm font-semibold text-slate-700">Nama Karyawan</label>
                    <select name="user_id" class="bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5">
                        <option selected>Pilih Karyawan...</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block mb-2 text-sm font-semibold text-slate-700">Jenis Izin</label>
                        <select name="type" class="bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5">
                            <option value="sakit">Sakit</option>
                            <option value="cuti_tahunan">Cuti Tahunan</option>
                            <option value="izin">Izin Lainnya</option>
                        </select>
                    </div>
                    <div>
                         <label class="block mb-2 text-sm font-semibold text-slate-700">Bukti (Opsional)</label>
                         <input type="file" name="attachment" class="block w-full text-xs text-slate-500
                            file:mr-2 file:py-2 file:px-4
                            file:rounded-full file:border-0
                            file:text-xs file:font-semibold
                            file:bg-indigo-50 file:text-indigo-700
                            hover:file:bg-indigo-100
                          "/>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block mb-2 text-sm font-semibold text-slate-700">Mulai Tanggal</label>
                        <input type="date" name="start_date" class="bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" required>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-semibold text-slate-700">Sampai Tanggal</label>
                        <input type="date" name="end_date" class="bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" required>
                    </div>
                </div>

                <div>
                    <label class="block mb-2 text-sm font-semibold text-slate-700">Keterangan / Alasan</label>
                    <textarea name="reason" rows="3" class="bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" placeholder="Tuliskan alasan pengajuan..."></textarea>
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" data-modal-toggle="add-leave-modal" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-50">Batal</button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 shadow-sm shadow-indigo-100">Kirim Pengajuan</button>
                </div>
            </form>
        </div>
    </div>
</div>