<aside x-data="{ expanded: true, mobileOpen: false }" class="relative z-50">

    {{-- MOBILE OVERLAY --}}
    <div x-show="mobileOpen" x-transition.opacity class="fixed inset-0 bg-black/50 lg:hidden"
        @click="mobileOpen = false">
    </div>

    {{-- MOBILE BUTTON --}}
    <button @click="mobileOpen = true"
        class="fixed top-4 left-4 z-50 lg:hidden bg-[#0F4C81] text-white p-3 rounded-xl shadow-lg">

        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>

    {{-- SIDEBAR --}}
    <div :class="{
            'translate-x-0': mobileOpen,
            '-translate-x-full lg:translate-x-0': !mobileOpen,
            'w-64': expanded,
            'w-20': !expanded
        }"
        class="fixed lg:static inset-y-0 left-0 bg-[#0F4C81] border-r border-white/10 flex flex-col transition-all duration-300 shrink-0 h-screen">

        {{-- HEADER --}}
        <div class="relative px-5 py-6 border-b border-white/10">

            {{-- EXPAND BUTTON --}}
            <button @click="expanded = !expanded"
                class="hidden lg:flex absolute -right-4 top-7 bg-white text-[#0F4C81] w-9 h-9 items-center justify-center rounded-xl shadow-lg hover:scale-105 transition">

                <svg :class="expanded ? '' : 'rotate-180'" class="w-5 h-5 transition duration-300" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">

                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                </svg>
            </button>

            <div class="flex items-center gap-4">

                <img src="{{ asset('assets/images/pnc-logo.png') }}" class="w-12 h-12 object-contain shrink-0">

                <div x-show="expanded" x-transition.opacity>
                    <h1 class="text-white font-bold text-sm tracking-wide">
                        CBT MANDIRI
                    </h1>

                    <p class="text-slate-300 text-xs">
                        Politeknik Negeri Cilacap
                    </p>
                </div>
            </div>
        </div>

        {{-- MENU --}}
        <div class="flex-1 overflow-y-auto py-6 px-3 space-y-8 no-scrollbar">

            {{-- MENU UTAMA --}}
            <div>

                <p x-show="expanded" class="text-slate-300 text-xs uppercase font-semibold px-3 mb-3">
                    Menu Utama
                </p>

                <div class="space-y-2">

                    {{-- DASHBOARD --}}
                    <a href="{{ route('home') }}" class="flex items-center gap-3 rounded-xl px-4 py-3 transition-all duration-200
                        {{ request()->routeIs('home')
    ? 'bg-white/15 text-white border border-white/10'
    : 'text-slate-200 hover:bg-white/10 hover:text-white' }}">

                        {{-- HOME ICON --}}
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 10.5L12 3l9 7.5V20a1 1 0 01-1 1h-5v-6H9v6H4a1 1 0 01-1-1v-9.5z" />
                        </svg>

                        <span x-show="expanded" class="text-sm font-medium">
                            Dashboard
                        </span>
                    </a>

                    {{-- PRODI --}}
                    <a href="{{ route('prodi.pilih') }}" class="flex items-center gap-3 rounded-xl px-4 py-3 transition-all duration-200
                        {{ request()->routeIs('prodi')
    ? 'bg-white/15 text-white border border-white/10'
    : 'text-slate-200 hover:bg-white/10 hover:text-white' }}">

                        {{-- BUILDING ICON --}}
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 21h16M7 21V7l5-4 5 4v14M9 10h.01M15 10h.01M9 14h.01M15 14h.01" />
                        </svg>

                        <span x-show="expanded" class="text-sm font-medium">
                            Pilih Prodi
                        </span>
                    </a>

                </div>
            </div>

            {{-- UJIAN --}}
            <div>

                <p x-show="expanded" class="text-slate-300 text-xs uppercase font-semibold px-3 mb-3">
                    Ujian
                </p>

                <div class="space-y-4">

                    {{-- MULAI TES --}}
                    <a href="{{ route('portal.ujian') }}"
                        class="flex items-center gap-3 rounded-xl px-4 py-3 bg-amber-500 hover:bg-amber-600 text-white font-semibold transition">

                        {{-- PENCIL ICON --}}
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5h2m-1 0v14m-7-7h14" />
                        </svg>

                        <span x-show="expanded" class="text-sm">
                            Mulai Tes
                        </span>
                    </a>
                    <div class="mb-2">

                        <p x-show="expanded" class="text-slate-300 text-xs uppercase font-semibold px-3 mb-4 mt-2">
                            Dokumen
                        </p>

                        {{-- CETAK KARTU --}}
                        {{-- NAV : UPLOAD DOKUMEN --}}
<a href="{{ route('dokumen.index') }}"
    class="flex items-center gap-3 rounded-xl px-4 py-3 text-slate-200 
    hover:bg-white/10 hover:text-white transition">

    {{-- ICON FILE --}}
    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">

        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M7 16V4a2 2 0 012-2h6l4 4v10a2 2 0 01-2 2H7a2 2 0 01-2-2zm8-10v4h4m-7 5l2 2 4-4" />
    </svg>

    {{-- TEXT --}}
    <div x-show="expanded" class="flex flex-col min-w-0">

        <span class="text-sm font-medium leading-none">
            Upload Dokumen
        </span>

        <span class="text-xs text-slate-400 truncate mt-1">
            Persyaratan Ujian Mandiri
        </span>

    </div>

    {{-- STATUS --}}
    <div x-show="expanded" class="ml-auto">

        @if($dokumenLengkap ?? false)
            <span
                class="bg-green-500/20 text-green-300 text-[10px] px-2 py-1 rounded-full font-semibold">
                Lengkap
            </span>
        @else
            <span
                class="bg-yellow-500/20 text-yellow-300 text-[10px] px-2 py-1 rounded-full font-semibold">
                Pending
            </span>
        @endif

    </div>

</a>
                        <a href="{{ route('cetak.identitas') }}"
                            class="flex items-center gap-3 rounded-xl px-4 py-3 text-slate-200 hover:bg-white/10 hover:text-white transition">

                            {{-- PRINTER ICON --}}
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 9V4h12v5M6 18h12v2H6v-2zm-2-8h16a2 2 0 012 2v4H2v-4a2 2 0 012-2z" />
                            </svg>

                            <span x-show="expanded" class="text-sm">
                                Cetak Kartu
                            </span>
                        </a>

                        

                        {{-- PROFILE --}}
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 rounded-xl px-4 py-3 transition-all duration-200
    {{ request()->routeIs('profile.edit')
    ? 'bg-white/15 text-white border border-white/10'
    : 'text-slate-200 hover:bg-white/10 hover:text-white' }}">

                            {{-- USER ICON --}}
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5.121 17.804A9 9 0 1118.88 17.8M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>

                            <span x-show="expanded" class="text-sm font-medium">
                                Profile
                            </span>
                        </a>


                        {{-- HELP DESK --}}
                        <a href="{{ route('helpdesk') }}" class="flex items-center gap-3 rounded-xl px-4 py-3 transition-all duration-200
    {{ request()->routeIs('helpdesk')
    ? 'bg-white/15 text-white border border-white/10'
    : 'text-slate-200 hover:bg-white/10 hover:text-white' }}">

                            {{-- SUPPORT ICON --}}
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M18.364 5.636A9 9 0 105.636 18.364M9 10h.01M15 10h.01M9 15h6" />
                            </svg>

                            <span x-show="expanded" class="text-sm font-medium">
                                Help Desk
                            </span>
                        </a>
                    </div>

                </div>
            </div>

        </div>

        {{-- USER --}}
        <div class="border-t border-white/10 p-4">

            <div class="flex items-center gap-3">

                <img src="{{ auth()->user()->foto ? asset('storage/' . auth()->user()->foto) : asset('assets/images/photo.png') }}"
                    class="w-10 h-10 rounded-lg object-cover">

                <div x-show="expanded" class="overflow-hidden">
                    <p class="text-white text-sm font-semibold truncate">
                        {{ Auth::user()->name }}
                    </p>

                    <p class="text-slate-300 text-xs">
                        Peserta CBT
                    </p>
                </div>
            </div>

            {{-- LOGOUT --}}
            <form method="POST" action="{{ route('logout') }}" class="mt-4">
                @csrf

                <button type="submit"
                    class="w-full flex items-center justify-center gap-3 rounded-xl bg-red-500 hover:bg-red-600 text-white py-3 transition">

                    {{-- LOGOUT ICON --}}
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H9m8 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h10a2 2 0 012 2v1" />
                    </svg>

                    <span x-show="expanded" class="text-sm font-medium">
                        Logout
                    </span>
                </button>
            </form>

        </div>
    </div>
</aside>

@include('sweetalert::alert')

<style>
    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }

    .no-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>