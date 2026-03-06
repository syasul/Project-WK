<div id="edit-employee-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-2xl max-h-full">
        <div class="relative bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="flex items-center justify-between p-4 md:p-5 border-b border-slate-100 bg-slate-50/50">
                <h3 class="text-lg font-bold text-slate-800">
                    Edit Data Karyawan
                </h3>
                <button type="button" class="text-slate-400 bg-transparent hover:bg-slate-100 hover:text-slate-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" data-modal-toggle="edit-employee-modal">
                    <i class="fa-solid fa-xmark text-lg"></i>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            
            <form id="edit-form" method="POST" class="p-4 md:p-6">
                @csrf
                @method('PUT')
                
                <div class="grid gap-x-6 gap-y-5 mb-6 grid-cols-2">
                    
                    <div class="col-span-2">
                        <label for="edit-name" class="block mb-2 text-sm font-semibold text-slate-700">Nama Lengkap <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" id="edit-name" class="bg-white border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 shadow-sm" required>
                    </div>
                    
                    <div class="col-span-2 sm:col-span-1">
                        <label for="edit-email" class="block mb-2 text-sm font-semibold text-slate-700">Email Login <span class="text-rose-500">*</span></label>
                        <input type="email" name="email" id="edit-email" class="bg-white border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 shadow-sm" required>
                    </div>

                    <div class="col-span-2 sm:col-span-1">
                        <label for="edit-phone" class="block mb-2 text-sm font-semibold text-slate-700">No. WhatsApp</label>
                        <input type="text" name="phone" id="edit-phone" class="bg-white border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 shadow-sm">
                    </div>

                    <div class="col-span-2">
                        <label for="password" class="block mb-2 text-sm font-semibold text-slate-700">Password Baru</label>
                        <input type="password" name="password" class="bg-white border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 shadow-sm" placeholder="Kosongkan jika tidak ingin mengubah password">
                    </div>

                    <div class="col-span-2 sm:col-span-1">
                        <label for="edit-position" class="block mb-2 text-sm font-semibold text-slate-700">Jabatan</label>
                        <input type="text" name="position" id="edit-position" class="bg-white border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 shadow-sm">
                    </div>

                    <div class="col-span-2 sm:col-span-1">
                        <label for="edit-role" class="block mb-2 text-sm font-semibold text-slate-700">Role Aplikasi <span class="text-rose-500">*</span></label>
                        <select name="role" id="edit-role" class="bg-white border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 shadow-sm">
                            <option value="employee">Employee</option>
                            <option value="leader">Leader</option>
                        </select>
                    </div>

                    <div class="col-span-2">
                        <label for="edit-shift" class="block mb-2 text-sm font-semibold text-slate-700">Shift Kerja</label>
                        <select name="shift_id" id="edit-shift" class="bg-white border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 shadow-sm">
                            <option value="">Belum Ada Shift</option>
                            @foreach($shifts as $shift)
                                <option value="{{ $shift->shift_id }}">{{ $shift->name }} ({{ $shift->start_time }} - {{ $shift->end_time }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="flex items-center justify-end space-x-3 border-t border-slate-100 pt-5">
                    <button type="button" data-modal-toggle="edit-employee-modal" class="text-slate-700 bg-white border border-slate-300 focus:ring-4 focus:outline-none focus:ring-slate-200 font-medium rounded-lg text-sm px-5 py-2.5 hover:bg-slate-50 hover:text-slate-900 focus:z-10 transition">Batal</button>
                    <button type="submit" class="text-white bg-yellow-600 hover:bg-yellow-700 focus:ring-4 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center transition shadow-sm">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>