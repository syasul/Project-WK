<header class="sticky top-0 z-40 flex h-16 w-full bg-white border-b border-slate-200 shadow-sm">
    <div class="flex flex-1 items-center justify-between px-4 sm:px-6">
      
      <div class="flex items-center flex-1">
        
        <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar" type="button" class="inline-flex items-center p-2 text-slate-500 rounded-lg md:hidden hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-slate-200">
            <span class="sr-only">Open sidebar</span>
            <i class="fa-solid fa-bars text-xl"></i>
         </button>

         <div class="hidden md:block w-full max-w-7xl ml-4">
            <div class="relative w-full">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="fa-solid fa-magnifying-glass text-slate-400 text-sm"></i>
                </div>
                <input type="text" 
                       class="block w-full py-2 pl-10 pr-3 text-sm text-slate-900 border border-gray-300 rounded-lg bg-slate-50 focus:outline-none focus:ring-0 focus:border-gray-300 placeholder-slate-400 transition" 
                       placeholder="Cari data karyawan, proyek, atau menu...">
            </div>
        </div>
      </div>

      <div class="flex items-center space-x-3">
          
          <button type="button" class="relative p-2 text-slate-400 rounded-full hover:text-slate-600 hover:bg-slate-100 transition">
            <i class="fa-regular fa-bell text-xl"></i>
            <span class="absolute top-2 right-2 flex h-2.5 w-2.5">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-rose-500 border border-white"></span>
            </span>
          </button>
          
          <div class="h-6 w-px bg-slate-200 mx-2"></div>

          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex items-center gap-2 text-sm font-medium text-rose-600 hover:text-rose-700 border-1.5 bg-rose-50 hover:bg-rose-100 px-3 py-1.5 rounded-lg transition">
                <i class="fa-solid fa-power-off"></i> 
                <span class="hidden sm:inline">Logout</span>
            </button>
        </form>

      </div>
    </div>
</header>