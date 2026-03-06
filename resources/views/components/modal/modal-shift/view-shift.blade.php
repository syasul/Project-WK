<div id="view-shift-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-2xl shadow-2xl overflow-hidden border border-slate-100">
            
            <div class="flex items-center justify-between p-4 border-b border-slate-100 bg-slate-50/50">
                <h3 class="text-lg font-bold text-slate-800">Detail Shift Kerja</h3>
                <button type="button" class="text-slate-400 bg-transparent hover:bg-slate-100 hover:text-slate-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center transition" data-modal-toggle="view-shift-modal">
                    <i class="fa-solid fa-xmark text-lg"></i>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            
            <div class="p-6">
                
                <div class="text-center mb-8">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-indigo-50 text-indigo-600 mb-4 shadow-sm ring-4 ring-indigo-50/50">
                        <i class="fa-regular fa-clock text-4xl"></i>
                    </div>
                    <h4 class="text-2xl font-bold text-slate-900 tracking-tight" id="view-name">-</h4>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 mt-2 rounded-full text-xs font-semibold bg-slate-100 text-slate-500 border border-slate-200">
                        Shift Reguler
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-6 relative">
                    <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-8 h-8 bg-white rounded-full flex items-center justify-center border border-slate-100 text-slate-300 z-10 shadow-sm">
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </div>

                    <div class="flex flex-col items-center justify-center p-4 bg-emerald-50/50 rounded-2xl border border-emerald-100 hover:border-emerald-200 transition">
                        <span class="text-[10px] font-bold text-emerald-600/70 uppercase tracking-wider mb-1">Jam Masuk</span>
                        <span class="text-2xl font-bold text-emerald-600 font-mono" id="view-start">--:--</span>
                    </div>

                    <div class="flex flex-col items-center justify-center p-4 bg-rose-50/50 rounded-2xl border border-rose-100 hover:border-rose-200 transition">
                        <span class="text-[10px] font-bold text-rose-600/70 uppercase tracking-wider mb-1">Jam Pulang</span>
                        <span class="text-2xl font-bold text-rose-600 font-mono" id="view-end">--:--</span>
                    </div>
                </div>

                <div class="flex items-center justify-between bg-slate-800 text-white p-4 rounded-xl shadow-lg shadow-slate-200">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-white/10 flex items-center justify-center text-white backdrop-blur-sm">
                            <i class="fa-solid fa-hourglass-half"></i>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[10px] uppercase font-bold text-slate-400">Total Durasi</span>
                            <span class="font-medium text-sm text-slate-200">Jam Kerja Efektif</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-xl font-bold text-white tracking-tight" id="view-duration">-</span>
                    </div>
                </div>

            </div>

            <div class="flex items-center justify-end p-4 border-t border-slate-100 bg-slate-50">
                <button data-modal-hide="view-shift-modal" type="button" class="text-slate-700 bg-white hover:bg-slate-100 focus:ring-4 focus:outline-none focus:ring-slate-200 rounded-xl border border-slate-200 text-sm font-medium px-5 py-2.5 focus:z-10 transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>