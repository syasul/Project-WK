<div id="view-location-modal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative w-full max-w-lg max-h-full">
        <div class="relative bg-white rounded-2xl shadow-xl border border-slate-200">
            <div class="flex items-center justify-between p-4 border-b border-slate-100 rounded-t bg-slate-50/50">
                <h3 class="text-lg font-bold text-slate-900" id="view-name">Detail Lokasi</h3>
                <button type="button" class="text-slate-400 bg-transparent hover:bg-slate-100 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center" data-modal-hide="view-location-modal"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>
            
            <div class="p-6 space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-slate-400 uppercase font-bold">Leader / PIC</p>
                        <p class="text-sm font-semibold text-slate-800" id="view-leader">-</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-slate-400 uppercase font-bold">Koordinat</p>
                        <p class="text-xs font-mono text-slate-600" id="view-coords">-</p>
                    </div>
                </div>
                
                <div>
                    <div id="map-view" class="h-56 w-full rounded-xl border border-slate-300 relative z-0 overflow-hidden"></div>
                </div>

                <div>
                    <p class="text-xs text-slate-400 uppercase font-bold mb-1">Alamat Lengkap</p>
                    <p class="text-sm text-slate-600 leading-relaxed" id="view-address">-</p>
                </div>
            </div>
            
            <div class="flex items-center justify-end p-4 border-t border-slate-100 bg-slate-50 rounded-b-2xl">
                <button data-modal-hide="view-location-modal" type="button" class="text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 font-medium rounded-xl text-sm px-5 py-2.5">Tutup</button>
            </div>
        </div>
    </div>
</div>