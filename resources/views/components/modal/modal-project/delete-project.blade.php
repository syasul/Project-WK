<div id="delete-project-modal" tabindex="-1" class="fixed top-0 left-0 right-0 z-50 hidden p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-2xl shadow-xl border border-slate-200">
            <button type="button" class="absolute top-3 right-2.5 text-slate-400 bg-transparent hover:bg-slate-100 hover:text-slate-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center" data-modal-hide="delete-project-modal">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
            <div class="p-6 text-center">
                <div class="w-14 h-14 rounded-full bg-red-50 text-red-500 flex items-center justify-center mx-auto mb-4 border border-red-100">
                    <i class="fa-solid fa-triangle-exclamation text-2xl"></i>
                </div>
                <h3 class="mb-2 text-lg font-bold text-slate-900">Hapus Project Ini?</h3>
                <p class="mb-6 text-sm text-slate-500 leading-relaxed">
                    Anda yakin ingin menghapus data project ini? <br>
                    Tindakan ini mungkin mempengaruhi riwayat absensi yang terkait dan <span class="font-bold text-red-600">tidak dapat dibatalkan</span>.
                </p>
                
                <form id="delete-project-form" method="POST" class="inline-flex w-full justify-center gap-2">
                    @csrf
                    @method('DELETE')
                    
                    <button data-modal-hide="delete-project-modal" type="button" class="text-slate-700 bg-white hover:bg-slate-50 focus:ring-4 focus:outline-none focus:ring-slate-200 rounded-xl border border-slate-300 text-sm font-medium px-5 py-2.5 hover:text-slate-900 focus:z-10 w-full shadow-sm transition">
                        Batal
                    </button>

                    <button type="submit" class="text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-xl text-sm px-5 py-2.5 text-center shadow-sm shadow-red-100 w-full transition">
                        Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>