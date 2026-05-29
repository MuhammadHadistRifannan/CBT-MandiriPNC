<div x-cloak x-show="sidebarOpen" x-transition.opacity @click="sidebarOpen = false"
    class="fixed inset-0 z-40 bg-slate-950/55 backdrop-blur-sm lg:hidden" aria-hidden="true"></div>

<aside @keydown.escape.window="sidebarOpen = false"
    :class="sidebarOpen ? '!translate-x-0' : '-translate-x-full lg:translate-x-0'"
    class="fixed inset-y-0 left-0 z-50 flex h-screen w-[272px] -translate-x-full flex-col border-r border-[#132238] bg-[#071226] transition-transform duration-300 lg:static lg:translate-x-0">

    <div class="flex h-20 items-center gap-3 border-b border-[#132238] px-5">
        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white p-2 shadow-sm">
            <img src="{{ asset('assets/images/pnc-logo.png') }}" alt="Logo Politeknik Negeri Cilacap"
                class="h-full w-full object-contain">
        </div>

        <div class="min-w-0">
            <h1 class="truncate text-lg font-bold text-white">CBT PMB PNC</h1>
            <p class="text-xs font-medium text-slate-400">Admin Panel</p>
        </div>

        <button type="button" @click="sidebarOpen = false"
            class="ml-auto flex h-10 w-10 shrink-0 items-center justify-center rounded-xl text-slate-300 transition hover:bg-white/10 hover:text-white lg:hidden"
            aria-label="Tutup menu navigasi">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <nav class="flex-1 overflow-y-auto px-3 py-6" aria-label="Menu admin">
        <p class="mb-4 px-3 text-[11px] font-bold uppercase tracking-[0.2em] text-slate-500">
            Manajemen
        </p>

        <div class="space-y-2">
            <a href="{{ route('admin.dashboard') }}"
                class="group flex items-center gap-3 rounded-2xl px-3 py-3 transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-[#0F4C81] text-white shadow-lg shadow-blue-900/20' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl {{ request()->routeIs('admin.dashboard') ? 'bg-white/15' : 'bg-white/5 group-hover:bg-white/10' }}">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4h7v7H4V4zm9 0h7v5h-7V4zM4 13h7v7H4v-7zm9-2h7v9h-7v-9z" />
                    </svg>
                </span>
                <span class="min-w-0">
                    <span class="block truncate text-sm font-semibold">Dashboard</span>
                    <span class="block truncate text-xs {{ request()->routeIs('admin.dashboard') ? 'text-blue-100' : 'text-slate-500' }}">
                        Ringkasan PMB
                    </span>
                </span>
            </a>

            <a href="{{ route('admin.prodi') }}"
                class="group flex items-center gap-3 rounded-2xl px-3 py-3 transition-all duration-200 {{ request()->routeIs('admin.prodi*') ? 'bg-[#0F4C81] text-white shadow-lg shadow-blue-900/20' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl {{ request()->routeIs('admin.prodi*') ? 'bg-white/15' : 'bg-white/5 group-hover:bg-white/10' }}">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4L3 8.5 12 13l9-4.5L12 4zM7 11v5c0 1.4 2.2 3 5 3s5-1.6 5-3v-5M21 9v6" />
                    </svg>
                </span>
                <span class="min-w-0">
                    <span class="block truncate text-sm font-semibold">Program Studi</span>
                    <span class="block truncate text-xs {{ request()->routeIs('admin.prodi*') ? 'text-blue-100' : 'text-slate-500' }}">
                        Kelola pilihan prodi
                    </span>
                </span>
            </a>

            <a href="{{ route('admin.dokumen', ['status' => \App\Enums\DokumenStatus::Pending->value]) }}"
                class="group flex items-center gap-3 rounded-2xl px-3 py-3 transition-all duration-200 {{ request()->routeIs('admin.dokumen*') ? 'bg-[#0F4C81] text-white shadow-lg shadow-blue-900/20' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl {{ request()->routeIs('admin.dokumen*') ? 'bg-white/15' : 'bg-white/5 group-hover:bg-white/10' }}">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 3h7l5 5v13H7a2 2 0 01-2-2V5a2 2 0 012-2zm7 0v6h6M9 14l2 2 4-4" />
                    </svg>
                </span>
                <span class="min-w-0">
                    <span class="block truncate text-sm font-semibold">Verifikasi Dokumen</span>
                    <span class="block truncate text-xs {{ request()->routeIs('admin.dokumen*') ? 'text-blue-100' : 'text-slate-500' }}">
                        Review berkas peserta
                    </span>
                </span>
            </a>

            <a href="{{ route('admin.soal') }}"
                class="group flex items-center gap-3 rounded-2xl px-3 py-3 transition-all duration-200 {{ request()->routeIs('admin.soal*') ? 'bg-[#0F4C81] text-white shadow-lg shadow-blue-900/20' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl {{ request()->routeIs('admin.soal*') ? 'bg-white/15' : 'bg-white/5 group-hover:bg-white/10' }}">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5h6m-6 0a2 2 0 104 0m-4 0a2 2 0 114 0h4a2 2 0 012 2v12a2 2 0 01-2 2H7a2 2 0 01-2-2V7a2 2 0 012-2h2zm0 8l2 2 4-4m-6 8h6" />
                    </svg>
                </span>
                <span class="min-w-0">
                    <span class="block truncate text-sm font-semibold">Bank Soal CBT</span>
                    <span class="block truncate text-xs {{ request()->routeIs('admin.soal*') ? 'text-blue-100' : 'text-slate-500' }}">
                        Soal dan rilis ujian
                    </span>
                </span>
            </a>

            <a href="{{ route('admin.pengumuman') }}"
                class="group flex items-center gap-3 rounded-2xl px-3 py-3 transition-all duration-200 {{ request()->routeIs('admin.pengumuman*') ? 'bg-[#0F4C81] text-white shadow-lg shadow-blue-900/20' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl {{ request()->routeIs('admin.pengumuman*') ? 'bg-white/15' : 'bg-white/5 group-hover:bg-white/10' }}">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h6l6 6v10a2 2 0 01-2 2z" />
                    </svg>
                </span>
                <span class="min-w-0">
                    <span class="block truncate text-sm font-semibold">Pengumuman PMB</span>
                    <span class="block truncate text-xs {{ request()->routeIs('admin.pengumuman*') ? 'text-blue-100' : 'text-slate-500' }}">
                        Publish hasil seleksi
                    </span>
                </span>
            </a>
        </div>
    </nav>

    <div class="border-t border-[#132238] p-4">
        <div class="mb-4 flex items-center gap-3 rounded-2xl bg-white/[0.03] p-3">
             <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white p-2 shadow-sm">
            <img src="{{ asset('assets/images/pnc-logo.png') }}" alt="Logo Politeknik Negeri Cilacap"
                class="h-full w-full object-contain">
        </div>
            <div class="min-w-0">
                <p class="truncate text-sm font-semibold text-white">{{ auth()->user()?->name ?? 'Admin PMB' }}</p>
                <p class="truncate text-xs text-slate-400">Administrator</p>
            </div>
        </div>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit"
                class="group flex w-full items-center gap-3 rounded-2xl px-3 py-3 text-red-300 transition hover:bg-red-500/10 hover:text-red-200">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-red-500/10 group-hover:bg-red-500/20">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 17l-5-5 5-5m-5 5h12M14 4h3a2 2 0 012 2v12a2 2 0 01-2 2h-3" />
                    </svg>
                </span>
                <span class="text-sm font-semibold">Keluar</span>
            </button>
        </form>
    </div>
</aside>
