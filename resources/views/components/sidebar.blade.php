<aside id="logo-sidebar" class="fixed top-0 left-0 z-50 w-64 h-screen transition-transform -translate-x-full md:!translate-x-0 bg-white border-r border-slate-200 flex flex-col shadow-xl md:shadow-none" aria-label="Sidebar">
    
    <div class="h-16 flex items-center px-6 border-b border-slate-100 shrink-0 bg-white">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
            <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center text-white font-bold text-lg shadow-md shadow-indigo-200">
               A
            </div>
            <span class="self-center text-lg font-bold text-slate-800 tracking-tight">
                Absensi<span class="text-indigo-600">Pro</span>
            </span>
        </a>
    </div>

    <div class="flex-1 px-3 py-4 overflow-y-auto no-scrollbar bg-white">
       <ul class="space-y-1 font-medium">
          
          <li>
             <a href="{{ route('admin.dashboard') }}" 
                class="flex items-center p-2.5 text-sm rounded-lg group transition-all duration-200 border border-transparent
                {{ request()->routeIs('admin.dashboard') 
                    ? 'bg-indigo-50 text-indigo-700 font-semibold' 
                    : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50' }}">
                <i class="fa-solid fa-chart-pie w-5 h-5 text-center transition duration-75 
                    {{ request()->routeIs('admin.dashboard') ? 'text-indigo-600' : 'text-slate-400 group-hover:text-slate-600' }}"></i>
                <span class="ml-3">Dashboard</span>
             </a>
          </li>
 
          <li class="pt-5 pb-2 px-3">
              <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                  Manajemen SDM
              </span>
          </li>
 
          <li>
             <a href="{{ route('admin.employees.index') }}" class="flex items-center p-2.5 text-sm text-slate-500 rounded-lg hover:text-slate-900 hover:bg-slate-50 group transition-all duration-200 border border-transparent
             {{ request()->routeIs('admin.employees.*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : '' }}">
                <i class="fa-solid fa-users w-5 h-5 text-center text-slate-400 group-hover:text-slate-600 transition"></i>
                <span class="flex-1 ml-3 text-left whitespace-nowrap">Data Karyawan</span>
             </a>
          </li>

          <li>
             <a href="{{ route('admin.leaves.index') }}" class="flex items-center p-2.5 text-sm text-slate-500 rounded-lg hover:text-slate-900 hover:bg-slate-50 group transition-all duration-200 border border-transparent
             {{ request()->routeIs('admin.leaves.*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : '' }}">
                <i class="fa-solid fa-envelope-open-text w-5 h-5 text-center transition duration-75 
                {{ request()->routeIs('admin.leaves.*') ? 'text-indigo-600' : 'text-slate-400 group-hover:text-slate-600' }}"></i>
                <span class="ml-3">Cuti & Izin</span>
             </a>
          </li>

          <li class="pt-5 pb-2 px-3">
              <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                  Operasional
              </span>
          </li>

          <li>
             <a href="{{ route('admin.shifts.index') }}" class="flex items-center p-2.5 text-sm text-slate-500 rounded-lg hover:text-slate-900 hover:bg-slate-50 group transition-all duration-200 border border-transparent
             {{ request()->routeIs('admin.shifts.*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : '' }}">
                <i class="fa-regular fa-clock w-5 h-5 text-center transition duration-75 
                {{ request()->routeIs('admin.shifts.*') ? 'text-indigo-600' : 'text-slate-400 group-hover:text-slate-600' }}"></i>
                <span class="ml-3">Shift Kerja</span>
             </a>
          </li>

          <li>
             <a href="{{ route('admin.holidays.index') }}" class="flex items-center p-2.5 text-sm text-slate-500 rounded-lg hover:text-slate-900 hover:bg-slate-50 group transition-all duration-200 border border-transparent
             {{ request()->routeIs('admin.holidays.*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : '' }}">
                <i class="fa-solid fa-calendar-day w-5 h-5 text-center transition duration-75 
                {{ request()->routeIs('admin.holidays.*') ? 'text-indigo-600' : 'text-slate-400 group-hover:text-slate-600' }}"></i>
                <span class="ml-3">Hari Libur</span>
             </a>
          </li>

          <li>
             <a href="{{ route('admin.locations.index') }}" class="flex items-center p-2.5 text-sm text-slate-500 rounded-lg hover:text-slate-900 hover:bg-slate-50 group transition-all duration-200 border border-transparent
             {{ request()->routeIs('admin.locations.*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : '' }}">
                <i class="fa-solid fa-map-pin w-5 h-5 text-center transition duration-75 
                {{ request()->routeIs('admin.locations.*') ? 'text-indigo-600' : 'text-slate-400 group-hover:text-slate-600' }}"></i>
                <span class="ml-3">Lokasi</span>
             </a>
          </li>
          
          <li>
             <a href="{{ route('admin.projects.index') }}" class="flex items-center p-2.5 text-sm text-slate-500 rounded-lg hover:text-slate-900 hover:bg-slate-50 group transition-all duration-200 border border-transparent
             {{ request()->routeIs('admin.projects.*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : '' }}">
                <i class="fa-solid fa-briefcase w-5 h-5 text-center transition duration-75
                {{ request()->routeIs('admin.projects.*') ? 'text-indigo-600' : 'text-slate-400 group-hover:text-slate-600' }}"></i>
                <span class="ml-3">Daftar Proyek</span>
             </a>
          </li>
          
          <li>
             <a href="{{ route('admin.attendances.index') }}" class="flex items-center p-2.5 text-sm text-slate-500 rounded-lg hover:text-slate-900 hover:bg-slate-50 group transition-all duration-200 border border-transparent">
                <i class="fa-solid fa-map-location-dot w-5 h-5 text-center text-slate-400 group-hover:text-slate-600"></i>
                <span class="ml-3">Live Absensi</span>
             </a>
          </li>

       </ul>
    </div>

    <div class="shrink-0 p-4 border-t border-slate-100 bg-white">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center text-indigo-600 font-bold text-sm">
                {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
            </div>
            <div class="overflow-hidden">
                <p class="text-sm font-semibold text-slate-700 truncate">
                    {{ Auth::user()->name ?? 'Admin' }}
                </p>
                <p class="text-xs text-slate-500 truncate">Administrator</p>
            </div>
        </div>
    </div>
 </aside>