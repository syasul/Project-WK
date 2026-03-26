<header class="sticky top-0 z-40 flex h-16 w-full bg-white border-b border-slate-200 shadow-sm" x-data="{ showSearch: true }">
    <div class="flex flex-1 items-center justify-between px-4 sm:px-6">
      
      <div class="flex items-center flex-1">
        
        <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar" type="button" class="inline-flex items-center p-2 text-slate-500 rounded-lg md:hidden hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-slate-200">
            <span class="sr-only">Open sidebar</span>
            <i class="fa-solid fa-bars text-xl"></i>
        </button>

         <div class="hidden md:block w-full max-2w-xl ml-4" x-show="showSearch">
            <form method="GET" :action="window.location.pathname" class="flex items-center bg-slate-100 rounded-lg w-full mx-auto overflow-hidden border border-slate-200 focus-within:ring-2 focus-within:ring-indigo-100 focus-within:border-indigo-400 transition-all">

                <div class="pl-3 text-slate-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>

                <input type="text" name="search" placeholder="Search" x-ref="searchInput"
    :value="new URLSearchParams(window.location.search).get('search') || ''"
    class="w-full px-3 py-2 bg-transparent text-sm border-0 focus:border-0 focus:ring-0 focus:outline-none focus:shadow-none" />
    
                <button type="button" @click="$refs.searchInput.value = ''; $el.closest('form').submit();" class="p-2 text-slate-400 hover:text-rose-500 transition-colors" title="Hapus Pencarian">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <button type="submit" class="hidden">Submit</button>
            </form>
        </div>
      </div>

      <div class="flex items-center space-x-3">
          
          <button type="button" data-dropdown-toggle="notification-dropdown" class="relative p-2 text-slate-400 rounded-full hover:text-slate-600 hover:bg-slate-100 transition">
            <i class="fa-regular fa-bell text-xl"></i>
            @if(auth()->user() && auth()->user()->unreadNotifications->count() > 0)
                <span class="absolute top-2 right-2 flex h-2.5 w-2.5">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-rose-500 border border-white"></span>
                </span>
            @endif
          </button>

          <div id="notification-dropdown" class="hidden z-50 my-4 max-w-sm w-full text-base list-none bg-white rounded-lg divide-y divide-slate-100 shadow-xl border border-slate-100">
              <div class="block py-3 px-4 text-sm font-bold text-center text-slate-700 bg-slate-50 rounded-t-lg">
                  Notifikasi Baru
              </div>
              <div class="max-h-80 overflow-y-auto">
                  @if(auth()->user())
                      @forelse(auth()->user()->unreadNotifications->take(5) as $notification)
                          <a href="#" class="flex py-3 px-4 border-b border-slate-100 hover:bg-slate-50 transition">
                              <div class="flex-shrink-0">
                                  <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center">
                                      <i class="fa-solid fa-circle-info text-sm"></i>
                                  </div>
                              </div>
                              <div class="pl-3 w-full">
                                  <div class="text-slate-600 text-sm mb-1.5">{{ $notification->data['message'] ?? 'Ada pemberitahuan baru' }}</div>
                                  <div class="text-xs text-slate-400">{{ $notification->created_at->diffForHumans() }}</div>
                              </div>
                          </a>
                      @empty
                          <div class="py-6 px-4 text-center text-sm text-slate-500">
                              <i class="fa-regular fa-bell-slash text-2xl text-slate-300 mb-2 block"></i>
                              Tidak ada notifikasi baru.
                          </div>
                      @endforelse
                  @endif
              </div>
              <a href="#" class="block py-2 text-sm font-medium text-center text-indigo-600 bg-slate-50 hover:bg-slate-100 rounded-b-lg transition">
                  Lihat semua notifikasi
              </a>
          </div>
          
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