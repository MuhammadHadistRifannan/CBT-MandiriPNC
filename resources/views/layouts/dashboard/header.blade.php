<header class="h-24 bg-gradient-to-r from-[#1E78D0] via-[#5C9CE0] to-white flex items-center justify-between px-8 sm:px-12 relative z-10 shrink-0">
    
    <img src="{{ asset('assets/images/corner.png') }}" 
         alt="Wave Header" 
         class="absolute top-0 left-0 h-full w-auto opacity-40 pointer-events-none transform -rotate-90 object-cover">

    <h1 class="text-3xl sm:text-4xl font-extrabold text-white tracking-wide relative z-10 drop-shadow-sm">
        Beranda
    </h1>

    <div class="relative z-10 flex items-center" x-data="{ open: false }">
        
        <button @click="open = !open" @click.outside="open = false" class="flex items-center bg-[#F3F4F6] hover:bg-[#E5E7EB] transition-colors duration-200 py-1.5 px-2.5 rounded-md border border-gray-200 shadow-sm focus:outline-none focus:ring-2 focus:ring-[#1E78D0]">
            
            <img src="{{ asset('assets/images/photo.png') }}" 
                 alt="Profile Avatar" 
                 class="w-7 h-7 rounded-full object-cover mr-2">
            
            <span class="text-xs font-semibold text-gray-700 mr-2">Profile</span>
            
            <svg :class="{'rotate-180': open}" class="w-3 h-3 text-gray-500 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>
        
        <div x-show="open" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95 transform -translate-y-2"
             x-transition:enter-end="opacity-100 scale-100 transform translate-y-0"
             x-transition:leave="transition ease-in duration-75"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="absolute right-0 top-full mt-2 w-48 bg-white rounded-lg shadow-xl border border-gray-100 py-2 focus:outline-none"
             style="display: none;"> <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#1E78D0] transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                Lihat Profile
            </a>

            <div class="h-px bg-gray-100 my-1 w-full"></div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    Logout
                </button>
            </form>
            
        </div>
        
    </div>
</header>