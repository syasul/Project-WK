<div id="add-employee-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-2xl max-h-full">
        <div class="relative bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="flex items-center justify-between p-4 md:p-5 border-b border-slate-100 bg-slate-50/50">
                <h3 class="text-lg font-bold text-slate-800">
                    Tambah Karyawan Baru
                </h3>
                <button type="button" class="text-slate-400 bg-transparent hover:bg-slate-100 hover:text-slate-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" data-modal-toggle="add-employee-modal">
                    <i class="fa-solid fa-xmark text-lg"></i>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            
            <form action="{{ route('admin.employees.store') }}" method="POST" class="p-4 md:p-6">
                @csrf
                <div class="grid gap-x-6 gap-y-5 mb-6 grid-cols-2">
                    
                    <div class="col-span-2">
                        <label for="name" class="block mb-2 text-sm font-semibold text-slate-700">Nama Lengkap <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" class="bg-white border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 shadow-sm" placeholder="Contoh: Budi Santoso" required>
                    </div>
                    
                    <div class="col-span-2 sm:col-span-1">
                        <label for="email" class="block mb-2 text-sm font-semibold text-slate-700">Email Login <span class="text-rose-500">*</span></label>
                        <input type="email" name="email" class="bg-white border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 shadow-sm" placeholder="email@perusahaan.com" required>
                    </div>

                    <div class="col-span-2 sm:col-span-1">
                        <label for="phone" class="block mb-2 text-sm font-semibold text-slate-700">No. WhatsApp</label>
                        <input type="text" name="phone" class="bg-white border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 shadow-sm" placeholder="Contoh: 08123456789">
                    </div>

                    <div class="col-span-2">
                        <label for="password" class="block mb-2 text-sm font-semibold text-slate-700">Password Awal <span class="text-rose-500">*</span></label>
                        <input type="password" name="password" class="bg-white border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 shadow-sm" required>
                        <p class="mt-1 text-xs text-slate-400">Minimal 8 karakter.</p>
                    </div>

                    <div class="col-span-2 sm:col-span-1">
                        <label for="position" class="block mb-2 text-sm font-semibold text-slate-700">Jabatan</label>
                        <input type="text" name="position" class="bg-white border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 shadow-sm" placeholder="Contoh: Staff IT">
                    </div>

                    <div class="col-span-2 sm:col-span-1">
                        <label for="role" class="block mb-2 text-sm font-semibold text-slate-700">Role Aplikasi <span class="text-rose-500">*</span></label>
                        <select name="role" class="bg-white border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 shadow-sm">
                            <option value="employee">Employee (Karyawan Biasa)</option>
                            <option value="leader">Leader (Akses Fitur Leader)</option>
                        </select>
                    </div>

                    <div class="col-span-2">
                        <label for="shift_id" class="block mb-2 text-sm font-semibold text-slate-700">Shift Kerja</label>
                        <select name="shift_id" class="bg-white border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 shadow-sm">
                            <option value="">Belum Ada Shift</option>
                            @foreach($shifts as $shift)
                                <option value="{{ $shift->shift_id }}">{{ $shift->name }} ({{ $shift->start_time }} - {{ $shift->end_time }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="flex items-center justify-end space-x-3 border-t border-slate-100 pt-5">
                    <button type="button" data-modal-toggle="add-employee-modal" class="text-slate-700 bg-white border border-slate-300 focus:ring-4 focus:outline-none focus:ring-slate-200 font-medium rounded-lg text-sm px-5 py-2.5 hover:bg-slate-50 hover:text-slate-900 focus:z-10 transition">Batal</button>
                    <button type="submit" class="text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-4 focus:outline-none focus:ring-indigo-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center transition shadow-sm">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>