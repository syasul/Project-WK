<div id="view-project-modal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative w-full max-w-2xl max-h-full">
        <div class="relative bg-white rounded-2xl shadow-xl border border-slate-200">
            <div class="flex items-start justify-between p-4 border-b border-slate-100 rounded-t bg-slate-50/50">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl shrink-0">
                        <i class="fa-regular fa-folder-open"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900" id="view-name">Nama Project</h3>
                        <div class="flex items-center gap-2 mt-0.5">
                            <span class="text-xs font-mono bg-slate-100 border border-slate-200 px-1.5 py-0.5 rounded text-slate-500" id="view-code">CODE</span>
                            <span id="view-status-badge" class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wide border">Status</span>
                        </div>
                    </div>
                </div>
                <button type="button" class="text-slate-400 bg-transparent hover:bg-slate-100 hover:text-slate-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center" data-modal-hide="view-project-modal">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
            
            <div class="p-6 overflow-y-auto max-h-[70vh] space-y-6">
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-4 bg-slate-50 rounded-xl border border-slate-100">
                    <div>
                        <p class="text-xs text-slate-400 font-medium uppercase tracking-wider mb-1">Klien</p>
                        <p class="text-sm font-semibold text-slate-800" id="view-client">-</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-medium uppercase tracking-wider mb-1">Periode</p>
                        <div class="flex items-center gap-2 text-sm text-slate-700">
                            <i class="fa-regular fa-calendar text-slate-400"></i>
                            <span id="view-dates">-</span>
                        </div>
                    </div>
                </div>

                <div>
                    <h4 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-2 mb-3">Informasi Keuangan</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-slate-500 mb-1">Nilai Project</p>
                            <p class="text-lg font-bold text-emerald-600" id="view-value">Rp 0</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 mb-1">Status Pembayaran</p>
                            <span id="view-payment-badge" class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium border">-</span>
                        </div>
                    </div>
                </div>

                <div>
                    <h4 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-2 mb-3">Lokasi & Alamat</h4>
                    <div class="space-y-3">
                        <div>
                            <p class="text-xs text-slate-500 mb-1">Nama Lokasi (Master)</p>
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-map-pin text-rose-500"></i>
                                <span class="text-sm font-bold text-slate-800" id="view-location-name">-</span>
                            </div>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 mb-1">Alamat</p>
                            <p class="text-sm text-slate-700 leading-relaxed" id="view-location-address">-</p>
                        </div>
                        
                        {{-- Container Peta untuk View Project --}}
                        <div id="view-map-container" class="h-48 w-full rounded-xl border border-slate-300 relative z-0 overflow-hidden hidden"></div>
                    </div>
                </div>

                <div>
                    <h4 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-2 mb-3">Deskripsi Project</h4>
                    <p class="text-sm text-slate-600 leading-relaxed" id="view-description">Tidak ada deskripsi.</p>
                </div>

            </div>
            
            <div class="flex items-center justify-end p-4 border-t border-slate-100 rounded-b bg-slate-50/50">
                <button data-modal-hide="view-project-modal" type="button" class="text-slate-700 bg-white hover:bg-slate-50 focus:ring-4 focus:outline-none focus:ring-slate-200 rounded-xl border border-slate-300 text-sm font-medium px-5 py-2.5 hover:text-slate-900 focus:z-10 shadow-sm transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>