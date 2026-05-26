<header class="sticky top-0 z-30 flex items-center gap-3 border-b border-slate-200 bg-white px-4 py-3 lg:hidden">
    <button type="button" @click="sidebarOpen = true"
        class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-700 shadow-sm transition hover:bg-slate-50"
        aria-label="Buka menu navigasi" :aria-expanded="sidebarOpen.toString()">
        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>
    <div class="min-w-0">
        <p class="truncate text-sm font-bold text-slate-900">{{ $title }}</p>
        <p class="truncate text-xs text-slate-500">CBT PMB PNC - Admin Panel</p>
    </div>
</header>
