<div id="edit-project-modal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative w-full max-w-2xl max-h-full">
        <div class="relative bg-white rounded-2xl shadow-xl border border-slate-200">
            <div class="flex items-start justify-between p-4 border-b border-slate-100 rounded-t bg-slate-50/50">
                <div>
                    <h3 class="text-lg font-bold text-slate-900">Edit Data Project</h3>
                </div>
                <button type="button" class="text-slate-400 bg-transparent hover:bg-slate-100 hover:text-slate-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center" data-modal-hide="edit-project-modal">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
            
            <form id="edit-project-form" method="POST">
                @csrf
                @method('PUT')
                <div class="p-6 overflow-y-auto max-h-[70vh]">
                    
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block mb-1.5 text-sm font-medium text-slate-700">Kode Project</label>
                            <input type="text" name="project_code" id="edit-project_code" class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-amber-500 focus:border-amber-500 block w-full p-2.5" required>
                        </div>
                        <div>
                            <label class="block mb-1.5 text-sm font-medium text-slate-700">Nama Project</label>
                            <input type="text" name="name" id="edit-name" class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-amber-500 focus:border-amber-500 block w-full p-2.5" required>
                        </div>
                        <div class="col-span-2">
                            <label class="block mb-1.5 text-sm font-medium text-slate-700">Nama Klien</label>
                            <input type="text" name="client_name" id="edit-client_name" class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-amber-500 focus:border-amber-500 block w-full p-2.5">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block mb-1.5 text-sm font-medium text-slate-700">Tanggal Mulai</label>
                            <input type="date" name="start_date" id="edit-start_date" class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-amber-500 focus:border-amber-500 block w-full p-2.5" required>
                        </div>
                        <div>
                            <label class="block mb-1.5 text-sm font-medium text-slate-700">Tanggal Selesai</label>
                            <input type="date" name="end_date" id="edit-end_date" class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-amber-500 focus:border-amber-500 block w-full p-2.5">
                        </div>
                        <div>
                            <label class="block mb-1.5 text-sm font-medium text-slate-700">Status Project</label>
                            <select name="status" id="edit-status" class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-amber-500 focus:border-amber-500 block w-full p-2.5">
                                <option value="planned">Planned</option>
                                <option value="ongoing">Ongoing</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                        <div>
                            <label class="block mb-1.5 text-sm font-medium text-slate-700">Status Pembayaran</label>
                            <select name="payment_status" id="edit-payment_status" class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-amber-500 focus:border-amber-500 block w-full p-2.5">
                                <option value="unpaid">Unpaid</option>
                                <option value="partial">Partial</option>
                                <option value="paid">Paid</option>
                            </select>
                        </div>
                        <div class="col-span-2">
                            <label class="block mb-1.5 text-sm font-medium text-slate-700">Nilai Project (Rp)</label>
                            <input type="number" name="project_value" id="edit-project_value" class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-amber-500 focus:border-amber-500 block w-full p-2.5">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block mb-1.5 text-sm font-medium text-slate-700">Lokasi Project</label>
                            <select name="location_id" id="edit-location_id" class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-amber-500 focus:border-amber-500 block w-full p-2.5" required>
                                <option value="">-- Pilih Lokasi --</option>
                                @foreach($locations as $location)
                                    <option value="{{ $location->location_id }}">
                                        {{ $location->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block mb-1.5 text-sm font-medium text-slate-700">Deskripsi</label>
                            <textarea name="description" id="edit-description" rows="3" class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-amber-500 focus:border-amber-500 block w-full p-2.5"></textarea>
                        </div>
                    </div>

                </div>
                <div class="flex items-center p-6 space-x-2 border-t border-slate-100 rounded-b bg-slate-50">
                    <button type="submit" class="text-white bg-yellow-500 hover:bg-yellow-600 focus:ring-4 focus:outline-none focus:ring-yellow-300 font-medium rounded-xl text-sm px-5 py-2.5 text-center shadow-sm">Update Project</button>
                    <button data-modal-hide="edit-project-modal" type="button" class="text-slate-500 bg-white hover:bg-slate-100 focus:ring-4 focus:outline-none focus:ring-indigo-100 rounded-xl border border-slate-200 text-sm font-medium px-5 py-2.5 hover:text-slate-900 focus:z-10">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>