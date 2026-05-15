<header
    class="sticky top-0 z-40 h-16 lg:h-20 bg-white border-b border-slate-200 flex items-center justify-between px-4 lg:px-8">

    {{-- LEFT --}}
    <div class="flex items-center gap-4">

        {{-- MOBILE MENU --}}
        <button @click="mobileOpen = true"
            class="lg:hidden flex items-center justify-center w-11 h-11 rounded-xl bg-[#0F4C81] text-white shadow-md">

            <svg class="w-7 h-7"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24">

                <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2.5"
                    d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        {{-- TITLE --}}
        <div class="leading-tight">

            <h1
                class="text-lg lg:text-2xl font-bold text-slate-800 tracking-tight">
                {{ $title ?? 'Portal Ujian' }}
            </h1>

            <p
                class="hidden sm:block text-xs text-slate-500 font-medium">
                Sistem Seleksi Mandiri Politeknik Negeri Cilacap
            </p>
        </div>
    </div>

    {{-- RIGHT --}}
    <div class="flex items-center gap-3 lg:gap-5">

        {{-- CLOCK --}}
        <div
            class="hidden md:flex flex-col items-end border-r border-slate-200 pr-4">

            <div id="digital-clock"
                class="text-base font-bold text-[#0F4C81] tracking-wide">
                00:00
            </div>

            <div class="text-xs text-slate-500">
                {{ now()->format('d M Y') }}
            </div>
        </div>

        {{-- USER --}}
        <div
            class="flex items-center gap-3 bg-slate-50 border border-slate-200 rounded-xl px-2 py-2">

            <img src="{{ asset('assets/images/photo.png') }}"
                class="w-10 h-10 rounded-lg object-cover">

            <div class="hidden sm:block leading-tight">

                <p class="text-sm font-semibold text-slate-800 max-w-[120px] truncate">
                    {{ Auth::user()->name }}
                </p>

                <p class="text-xs text-slate-500">
                    Peserta CBT
                </p>
            </div>
        </div>
    </div>
</header>

<script>
    function updateClock() {
        const now = new Date();

        const time = now.toLocaleTimeString('id-ID', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: false
        });

        const el = document.getElementById('digital-clock');

        if (el) {
            el.textContent = time.replace(/\./g, ':');
        }
    }

    setInterval(updateClock, 1000);
    updateClock();
</script>