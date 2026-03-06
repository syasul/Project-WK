<div id="view-employee-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="h-24 bg-gradient-to-r from-indigo-500 to-blue-500 relative">
                <button type="button" class="absolute top-3 right-3 text-white/80 bg-transparent hover:bg-white/20 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center transition" data-modal-toggle="view-employee-modal">
                    <i class="fa-solid fa-xmark text-lg"></i>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            
            <div class="px-6 pb-6 relative">
                <div class="w-20 h-20 rounded-full bg-white p-1 absolute -top-10 left-6 shadow-md">
                    <div class="w-full h-full rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-3xl font-bold">
                        <i class="fa-regular fa-user"></i>
                    </div>
                </div>

                <div class="mt-12 mb-6">
                    <h4 class="text-xl font-bold text-slate-900" id="view-name">-</h4>
                    <p class="text-sm text-slate-500 font-medium mb-2" id="view-position">-</p>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-600 border border-slate-200" id="view-id">
                        -
                    </span>
                </div>

                <div class="grid grid-cols-1 gap-4 text-sm border-t border-slate-100 pt-6">
                    
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-lg bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-400 shrink-0">
                            <i class="fa-regular fa-envelope text-lg"></i>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 font-medium">Email Login</p>
                            <p class="font-semibold text-slate-800" id="view-email">-</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-lg bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-400 shrink-0">
                            <i class="fa-brands fa-whatsapp text-lg"></i>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 font-medium">No. WhatsApp</p>
                            <p class="font-semibold text-slate-800" id="view-phone">-</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-lg bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-400 shrink-0">
                            <i class="fa-solid fa-shield-halved text-lg"></i>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 font-medium">Role Aplikasi</p>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold" id="view-role">
                                -
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-lg bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-400 shrink-0">
                            <i class="fa-regular fa-clock text-lg"></i>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 font-medium">Shift Kerja Saat Ini</p>
                            <p class="font-semibold text-slate-800" id="view-shift">-</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>