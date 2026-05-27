<div x-cloak x-show="sidebarOpen" x-transition.opacity @click="sidebarOpen = false"
    class="fixed inset-0 z-40 bg-slate-950/55 backdrop-blur-sm lg:hidden" aria-hidden="true"></div>

<aside @keydown.escape.window="sidebarOpen = false"
    :class="sidebarOpen ? '!translate-x-0' : '-translate-x-full lg:translate-x-0'"
    class="fixed inset-y-0 left-0 z-50 flex h-screen w-[284px] -translate-x-full flex-col border-r border-[#132238] bg-[#071226] transition-transform duration-300 lg:static lg:translate-x-0">
    <div class="flex items-center gap-3 border-b border-white/10 px-5 py-6">
        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white">
            <img src="{{ asset('assets/images/pnc-logo.png') }}" alt="Logo PNC" class="h-10 w-10 object-contain">
        </div>
        <div class="min-w-0 flex-1">
            <p class="truncate text-sm font-black text-white">CBT MANDIRI</p>
            <p class="truncate text-xs text-slate-400">Panel Pengawas</p>
        </div>
        <button type="button" @click="sidebarOpen = false" aria-label="Tutup navigasi"
            class="flex h-10 w-10 items-center justify-center rounded-xl text-slate-300 transition hover:bg-white/10 hover:text-white lg:hidden">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <nav class="flex-1 space-y-7 overflow-y-auto px-4 py-6">
        <div>
            <p class="mb-3 px-3 text-[11px] font-bold uppercase tracking-[0.18em] text-slate-500">Monitoring</p>
            <a href="{{ route('pengawas.dashboard') }}"
                class="flex items-center gap-3 rounded-2xl px-3 py-3 {{ request()->routeIs('pengawas.dashboard*') ? 'bg-[#0F4C81] text-white shadow-lg shadow-blue-950/30' : 'text-slate-300 transition hover:bg-white/5 hover:text-white' }}">
                <span class="flex h-11 w-11 items-center justify-center rounded-xl {{ request()->routeIs('pengawas.dashboard*') ? 'bg-white/15' : 'bg-white/5' }}">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 13h6V4H4v9zm10 7h6V4h-6v16zM4 20h6v-3H4v3z" />
                    </svg>
                </span>
                <span class="min-w-0">
                    <span class="block text-sm font-semibold">Dashboard</span>
                    <span class="block text-xs {{ request()->routeIs('pengawas.dashboard*') ? 'text-blue-100' : 'text-slate-500' }}">Ringkasan langsung</span>
                </span>
            </a>

            <div class="mt-2 flex items-center gap-3 rounded-2xl px-3 py-3 text-slate-400">
                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-white/5">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12h4l3-8 4 16 3-8h4" />
                    </svg>
                </span>
                <span class="min-w-0 flex-1 text-sm font-medium">Monitoring Ujian</span>
                <span class="rounded-full bg-white/5 px-2 py-1 text-[10px]">Segera</span>
            </div>

            <div class="flex items-center gap-3 rounded-2xl px-3 py-3 text-slate-400">
                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-white/5">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5V4H2v16h5m10 0v-2a4 4 0 00-8 0v2m8 0H9m4-10a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </span>
                <span class="min-w-0 flex-1 text-sm font-medium">Peserta Aktif</span>
                <span class="rounded-full bg-white/5 px-2 py-1 text-[10px]">Segera</span>
            </div>
        </div>

        <div>
            <p class="mb-3 px-3 text-[11px] font-bold uppercase tracking-[0.18em] text-slate-500">Operasional</p>
            <a href="{{ route('pengawas.check-in') }}"
                class="mb-2 flex items-center gap-3 rounded-2xl px-3 py-3 {{ request()->routeIs('pengawas.check-in*') ? 'bg-[#0F4C81] text-white shadow-lg shadow-blue-950/30' : 'text-slate-300 transition hover:bg-white/5 hover:text-white' }}">
                <span class="flex h-11 w-11 items-center justify-center rounded-xl {{ request()->routeIs('pengawas.check-in*') ? 'bg-white/15' : 'bg-white/5' }}">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3M5 11h14M7 21h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </span>
                <span class="min-w-0">
                    <span class="block text-sm font-semibold">Check-in Peserta</span>
                    <span class="block text-xs {{ request()->routeIs('pengawas.check-in*') ? 'text-blue-100' : 'text-slate-500' }}">Validasi kartu ujian</span>
                </span>
            </a>
            <a href="{{ route('pengawas.broadcast') }}"
                class="mb-2 flex items-center gap-3 rounded-2xl px-3 py-3 {{ request()->routeIs('pengawas.broadcast*') ? 'bg-[#0F4C81] text-white shadow-lg shadow-blue-950/30' : 'text-slate-300 transition hover:bg-white/5 hover:text-white' }}">
                <span class="flex h-11 w-11 items-center justify-center rounded-xl {{ request()->routeIs('pengawas.broadcast*') ? 'bg-white/15' : 'bg-white/5' }}">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.862 9.862 0 01-4.255-.949L3 20l1.395-3.72A7.56 7.56 0 013 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                </span>
                <span class="min-w-0">
                    <span class="block text-sm font-semibold">Broadcast Pesan</span>
                    <span class="block text-xs {{ request()->routeIs('pengawas.broadcast*') ? 'text-blue-100' : 'text-slate-500' }}">Pesan peserta aktif</span>
                </span>
            </a>
            <a href="{{ route('pengawas.activities') }}"
                class="mb-2 flex items-center gap-3 rounded-2xl px-3 py-3 {{ request()->routeIs('pengawas.activities*') ? 'bg-[#0F4C81] text-white shadow-lg shadow-blue-950/30' : 'text-slate-300 transition hover:bg-white/5 hover:text-white' }}">
                <span class="flex h-11 w-11 items-center justify-center rounded-xl {{ request()->routeIs('pengawas.activities*') ? 'bg-white/15' : 'bg-white/5' }}">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </span>
                <span class="min-w-0">
                    <span class="block text-sm font-semibold">Aktivitas Peserta</span>
                    <span class="block text-xs {{ request()->routeIs('pengawas.activities*') ? 'text-blue-100' : 'text-slate-500' }}">Log perilaku ujian</span>
                </span>
            </a>
            @foreach ([
                ['Pengaturan Sesi', 'M12 6v6l4 2m5-2a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['Laporan Pengawas', 'M9 17v-6m4 6V7m4 10V4M5 20h14'],
            ] as [$label, $path])
                <div class="flex items-center gap-3 rounded-2xl px-3 py-3 text-slate-400">
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-white/5">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $path }}" />
                        </svg>
                    </span>
                    <span class="min-w-0 flex-1 text-sm font-medium">{{ $label }}</span>
                    <span class="rounded-full bg-white/5 px-2 py-1 text-[10px]">Segera</span>
                </div>
            @endforeach
        </div>
    </nav>

    <div class="border-t border-white/10 p-4">
        <div class="mb-4 rounded-2xl bg-white/5 px-4 py-3">
            <p class="truncate text-sm font-semibold text-white">{{ auth()->user()->name }}</p>
            <p class="mt-1 text-xs text-slate-400">Pengawas Ujian</p>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="flex w-full items-center justify-center gap-2 rounded-2xl border border-white/10 px-4 py-3 text-sm font-semibold text-slate-200 transition hover:bg-red-500/15 hover:text-red-200">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v1" />
                </svg>
                Logout
            </button>
        </form>
    </div>
</aside>
