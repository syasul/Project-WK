<div id="delete-location-modal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-2xl shadow-xl border border-slate-200">
            <div class="p-6 text-center">
                <div class="w-14 h-14 rounded-full bg-red-50 text-red-500 flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-triangle-exclamation text-2xl"></i>
                </div>
                <h3 class="mb-2 text-lg font-bold text-slate-900">Hapus Lokasi Ini?</h3>
                <p class="mb-5 text-sm text-slate-500">Data lokasi dan riwayat project terkait mungkin akan terpengaruh. Tindakan ini tidak bisa dibatalkan.</p>
                
                <form id="delete-location-form" method="POST" class="inline-flex">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-white bg-red-600 hover:bg-red-700 font-medium rounded-xl text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
                        Ya, Hapus
                    </button>
                </form>
                <button data-modal-hide="delete-location-modal" type="button" class="text-slate-500 bg-white hover:bg-slate-100 rounded-xl border border-slate-200 text-sm font-medium px-5 py-2.5">Batal</button>
            </div>
        </div>
    </div>
</div>