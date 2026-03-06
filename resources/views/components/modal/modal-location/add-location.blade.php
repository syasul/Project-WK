<div id="add-location-modal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative w-full max-w-2xl max-h-full">
        <div class="relative bg-white rounded-2xl shadow-xl border border-slate-200">
            <div class="flex items-start justify-between p-4 border-b border-slate-100 bg-slate-50/50 rounded-t-2xl">
                <h3 class="text-lg font-bold text-slate-900">Tambah Lokasi Baru</h3>
                <button type="button" class="text-slate-400 bg-transparent hover:bg-slate-100 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center" data-modal-hide="add-location-modal"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>
            
            <form action="{{ route('admin.locations.store') }}" method="POST">
                @csrf
                <div class="p-6 space-y-4 overflow-y-auto max-h-[70vh]">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block mb-1.5 text-sm font-medium text-slate-700">Nama Lokasi <span class="text-rose-500">*</span></label>
                            <input type="text" name="name" class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" placeholder="Contoh: Gudang Cikupa" required>
                        </div>
                        <div>
                            <label class="block mb-1.5 text-sm font-medium text-slate-700">Leader / PIC</label>
                            <select name="leader_id" class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5">
                                <option value="">-- Pilih Leader --</option>
                                @foreach($leaders as $leader)
                                    <option value="{{ $leader->user_id }}">{{ $leader->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block mb-1.5 text-sm font-medium text-slate-700">Radius Toleransi (Meter)</label>
                        <input type="number" name="radius" id="add-radius" value="50" class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" placeholder="50">
                        <p class="text-xs text-slate-400 mt-1">Jarak maksimal karyawan bisa absen dari titik pusat.</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block mb-1.5 text-sm font-medium text-slate-700">Latitude</label>
                            <input type="text" name="latitude" id="add-latitude" class="bg-slate-100 text-slate-500 border border-slate-300 text-sm rounded-lg block w-full p-2.5" readonly required>
                        </div>
                        <div>
                            <label class="block mb-1.5 text-sm font-medium text-slate-700">Longitude</label>
                            <input type="text" name="longitude" id="add-longitude" class="bg-slate-100 text-slate-500 border border-slate-300 text-sm rounded-lg block w-full p-2.5" readonly required>
                        </div>
                        <div class="col-span-2">
                            <label class="block mb-1.5 text-sm font-medium text-slate-700">Pilih Titik di Peta</label>
                            <div id="map-add" style="height: 350px; width: 100%;" class="rounded-xl border border-slate-300 overflow-hidden relative z-0"></div>
                        </div>
                    </div>

                    <div>
                        <label class="block mb-1.5 text-sm font-medium text-slate-700">Alamat Lengkap</label>
                        <textarea name="address" rows="2" class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg block w-full p-2.5"></textarea>
                    </div>
                </div>

                <div class="flex items-center p-6 space-x-2 border-t border-slate-100 rounded-b bg-slate-50">
                    <button type="submit" class="text-white bg-indigo-600 hover:bg-indigo-700 font-medium rounded-xl text-sm px-5 py-2.5 text-center shadow-sm">Simpan Lokasi</button>
                    <button data-modal-hide="add-location-modal" type="button" class="text-slate-500 bg-white hover:bg-slate-100 rounded-xl border border-slate-200 text-sm font-medium px-5 py-2.5">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>