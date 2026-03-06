<div id="bulk-delete-modal" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-2xl shadow-xl">
            <button type="button" class="absolute top-3 end-2.5 text-slate-400 bg-transparent hover:bg-slate-100 hover:text-slate-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" data-modal-hide="bulk-delete-modal">
                <i class="fa-solid fa-xmark text-lg"></i>
                <span class="sr-only">Close modal</span>
            </button>
            <div class="p-6 text-center">
                <div class="w-16 h-16 rounded-full bg-rose-100 p-3 flex items-center justify-center mx-auto mb-4 ">
                    <i class="fa-regular fa-trash-can text-rose-500 text-3xl"></i>
                </div>
                <h3 class="mb-2 text-lg font-bold text-slate-800">Hapus Karyawan Terpilih?</h3>
                <p class="mb-6 text-sm text-slate-500">Anda akan menghapus <span id="bulk-count" class="font-bold text-slate-900">0</span> karyawan yang dipilih. Tindakan ini tidak dapat dibatalkan.</p>
                
                <form id="bulk-delete-form" action="{{ route('admin.employees.bulkDestroy') }}" method="POST" class="flex items-center justify-center gap-3">
                    @csrf
                    @method('DELETE')
                    <div id="bulk-delete-inputs"></div>
                    
                    <button data-modal-hide="bulk-delete-modal" type="button" class="text-slate-700 bg-white hover:bg-slate-50 focus:ring-4 focus:outline-none focus:ring-slate-200 rounded-lg border border-slate-300 text-sm font-medium px-5 py-2.5 focus:z-10 transition">
                        Batal
                    </button>
                    <button type="submit" class="text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center transition shadow-sm shadow-red-200">
                        Ya, Hapus Semua
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>