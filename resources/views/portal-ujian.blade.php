<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'CBT Mandiri PNC') }} - Portal Ujian</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased text-gray-900 bg-[#F8FAFC]">
    <div x-data="{
        sidebarExpanded: true,
        mobileOpen: false,
        activityTrackingEnabled: @js($activityTrackingEnabled),
        activityIdleTimer: null,
        activityIdleRecorded: false,
        broadcastMessages: [],
        async loadBroadcastMessages() {
            try {
                const response = await fetch(@js(route('participant.broadcast.index')), {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                });
                if (response.ok) {
                    this.broadcastMessages = (await response.json()).messages;
                }
            } catch (error) {
                console.error('Broadcast belum dapat diperbarui.', error);
            }
        },
        async dismissBroadcast(message) {
            const response = await fetch(message.dismiss_url, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                }
            });
            if (response.ok) {
                this.broadcastMessages = this.broadcastMessages.filter((item) => item.id !== message.id);
            }
        },
        async recordActivity(eventType) {
            if (!this.activityTrackingEnabled) return;

            try {
                await fetch(@js(route('participant.activity.store')), {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                    },
                    body: JSON.stringify({ event_type: eventType })
                });
            } catch (error) {
                console.error('Aktivitas tidak dapat disimpan.', error);
            }
        },
        resetIdleTimer() {
            if (!this.activityTrackingEnabled) return;

            clearTimeout(this.activityIdleTimer);
            this.activityIdleRecorded = false;
            this.activityIdleTimer = setTimeout(() => {
                this.activityIdleRecorded = true;
                this.recordActivity('idle');
            }, 60000);
        },
        initActivityTracking() {
            if (!this.activityTrackingEnabled) return;

            this.recordActivity('refresh');
            document.addEventListener('visibilitychange', () => {
                this.recordActivity(document.hidden ? 'tab_hidden' : 'tab_visible');
            });
            window.addEventListener('blur', () => this.recordActivity('window_blur'));
            window.addEventListener('focus', () => this.recordActivity('window_focus'));
            ['mousemove', 'keydown', 'click', 'touchstart'].forEach((eventName) => {
                window.addEventListener(eventName, () => this.resetIdleTimer(), { passive: true });
            });
            this.resetIdleTimer();
        },
        init() {
            this.loadBroadcastMessages();
            this.broadcastPoller = setInterval(() => this.loadBroadcastMessages(), 5000);
            this.initActivityTracking();
        }
    }" class="flex h-screen overflow-hidden">

        @include('layouts.dashboard.sidebar')

        <div class="flex-1 flex flex-col overflow-hidden relative">

            {{-- HEADER --}}
<header
    class="sticky top-0 z-30 bg-white border-b border-slate-200 px-4 lg:px-8 py-4">

    <div
        class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

        {{-- LEFT --}}
        <div class="flex items-start sm:items-center gap-4">

            {{-- MOBILE MENU --}}
            <button @click="mobileOpen = true"
                class="lg:hidden flex items-center justify-center w-11 h-11 rounded-xl bg-[#0F4C81] text-white shrink-0">

                <svg class="w-6 h-6"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">

                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2.5"
                        d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            {{-- ICON --}}
            <div
                class="hidden sm:flex w-14 h-14 rounded-2xl bg-[#0F4C81] text-white items-center justify-center shadow-sm shrink-0">

                <svg class="w-7 h-7"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">

                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10m-11 9h12a2 2 0 002-2V7a2 2 0 00-2-2H6a2 2 0 00-2 2v11a2 2 0 002 2z" />
                </svg>
            </div>

            {{-- TITLE --}}
            <div class="min-w-0">

                <p
                    class="text-xs sm:text-sm font-medium text-[#0F4C81] uppercase tracking-wide">
                    Seleksi Mandiri PNC
                </p>

                <h1
                    class="text-xl sm:text-2xl lg:text-3xl font-bold text-slate-800 tracking-tight leading-tight">

                    Portal Ujian
                </h1>

                <p
                    class="hidden sm:block text-sm text-slate-500 mt-1 max-w-2xl">
                    Mulai Ujian CBT Mandiri dengan jujur dan bertanggung jawab.
                </p>
            </div>
        </div>

        {{-- RIGHT --}}
        <div
            class="grid grid-cols-1 sm:grid-cols-2 gap-3 w-full lg:w-auto">

            {{-- STEP --}}
            <div
                class="bg-slate-100 border border-slate-200 rounded-2xl px-4 py-3">

                <p class="text-xs text-slate-500">
                    Tahapan
                </p>

                <h3 class="text-sm font-semibold text-slate-800">
                    Ujian Mandiri
                </h3>
            </div>

            {{-- STATUS --}}
            <div
                class="bg-emerald-50 border border-emerald-200 rounded-2xl px-4 py-3">

                <p class="text-xs text-emerald-600">
                    Status
                </p>

                <h3 class="text-sm font-semibold text-emerald-700">
                    Pendaftaran Aktif
                </h3>
            </div>

        </div>
    </div>
</header>

            <section x-show="broadcastMessages.length > 0" x-cloak
                class="border-b border-amber-200 bg-amber-50 px-4 py-3 lg:px-8">
                <div class="mx-auto max-w-5xl space-y-3">
                    <template x-for="message in broadcastMessages" :key="message.id">
                        <article class="flex items-start gap-3 rounded-2xl border border-amber-200 bg-white px-4 py-3 shadow-sm">
                            <span class="mt-1 flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-amber-700">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5l-7 7 7 7M4 12h14a2 2 0 002-2V7" />
                                </svg>
                            </span>
                            <div class="min-w-0 flex-1">
                                <div class="flex flex-wrap items-center gap-x-2 text-xs font-semibold text-amber-700">
                                    <span>Pesan Pengawas</span>
                                    <span class="text-slate-400">|</span>
                                    <span class="text-slate-500" x-text="message.sender + ' - ' + message.sent_at + ' WIB'"></span>
                                </div>
                                <p class="mt-1 text-sm font-medium leading-relaxed text-slate-700" x-text="message.message"></p>
                            </div>
                            <button type="button" @click="dismissBroadcast(message)" aria-label="Tutup pesan"
                                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl text-slate-400 transition hover:bg-slate-100 hover:text-slate-700">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </article>
                    </template>
                </div>
            </section>

            <main class="flex-1 overflow-x-hidden overflow-y-auto p-8 sm:p-12 relative z-0 flex items-center justify-center">

                <div class="w-full max-w-4xl text-center">

                    {{-- WRAPPER KONTEN UTAMA DENGAN CARD --}}
                    <div class="bg-white rounded-[3rem] shadow-2xl border border-gray-100 p-10 md:p-20 relative overflow-hidden">
                        
                        <div class="absolute -top-24 -right-24 w-64 h-64 bg-blue-50 rounded-full blur-3xl opacity-50"></div>
                        <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-emerald-50 rounded-full blur-3xl opacity-50"></div>

                        @if(session('success'))
                            <div class="relative z-10 mb-8 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-left text-sm font-bold text-emerald-700">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if($errors->has('ujian') || $errors->has('agree'))
                            <div class="relative z-10 mb-8 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-left text-sm font-bold text-red-700">
                                {{ $errors->first('ujian') ?: $errors->first('agree') }}
                            </div>
                        @endif

                        {{-- Kondisi 1: TERKUNCI --}}
                        @if($status == 'locked')
                            <div class="relative z-10 space-y-8">
                                <div class="w-32 h-32 bg-red-50 text-red-500 rounded-full flex items-center justify-center mx-auto shadow-inner">
                                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                </div>
                                <div class="space-y-4">
                                    <h2 class="text-4xl font-black text-gray-900 uppercase tracking-tighter">Akses Terkunci</h2>
                                    <p class="text-lg font-bold text-gray-500 leading-relaxed max-w-md mx-auto">
                                        Anda belum melakukan <span class="text-red-600 underline">Simpan Permanen</span> pilihan program studi. Silakan selesaikan tahap pemilihan prodi terlebih dahulu.
                                    </p>
                                </div>
                                <a href="{{ route('prodi.pilih') }}" class="inline-block bg-gray-900 text-white font-black px-10 py-4 rounded-2xl hover:bg-black transition shadow-lg">Kembali ke Pilih Prodi</a>
                            </div>

                        @elseif($status == 'blocked')
                            <div class="relative z-10 space-y-8">
                                <div class="w-28 h-28 bg-red-50 text-red-600 rounded-full flex items-center justify-center mx-auto">
                                    <svg class="w-14 h-14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M18.364 5.636l-12.728 12.728m0-12.728l12.728 12.728M12 22a10 10 0 110-20 10 10 0 010 20z" />
                                    </svg>
                                </div>
                                <div class="space-y-4">
                                    <h2 class="text-4xl font-black text-gray-900 uppercase tracking-tighter">Akses Diblokir</h2>
                                    <p class="text-lg font-bold text-gray-500 leading-relaxed max-w-md mx-auto">
                                        Sesi ujian Anda diblokir. Silakan hubungi pengawas ruang ujian.
                                    </p>
                                </div>
                            </div>

                        @elseif($status == 'submitted')
                            <div class="relative z-10 space-y-8">
                                <div class="w-28 h-28 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center mx-auto">
                                    <svg class="w-14 h-14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <div class="space-y-4">
                                    <h2 class="text-4xl font-black text-gray-900 uppercase tracking-tighter">Ujian Selesai</h2>
                                    <p class="text-lg font-bold text-gray-500 leading-relaxed max-w-md mx-auto">
                                        Jawaban Anda telah disubmit dan sesi ujian tidak dapat dimulai kembali.
                                    </p>
                                </div>
                            </div>

                        {{-- Kondisi 2: COUNTDOWN --}}
                        @elseif($status == 'countdown')
                            <div class="relative z-10 space-y-12" x-data="{
                                targetDate: new Date('2026-05-20T08:00:00').getTime(),
                                now: new Date().getTime(),
                                days: 0, hours: 0, minutes: 0, seconds: 0,
                                update() {
                                    let distance = this.targetDate - this.now;
                                    if (distance < 0) {
                                        this.days = this.hours = this.minutes = this.seconds = 0;
                                        return;
                                    }
                                    this.days = Math.floor(distance / (1000 * 60 * 60 * 24));
                                    this.hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                    this.minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                                    this.seconds = Math.floor((distance % (1000 * 60)) / 1000);
                                }
                            }" x-init="update(); setInterval(() => { now = new Date().getTime(); update(); }, 1000)">

                                <div class="space-y-2">
                                    <span class="text-sm font-black text-blue-600 uppercase tracking-[0.3em]">Jadwal Ujian Luring</span>
                                    <h2 class="text-4xl md:text-5xl font-black text-gray-900 uppercase">Menghitung Mundur</h2>
                                </div>

                                <div class="flex flex-wrap justify-center gap-4 md:gap-8">
                                    <template x-for="(val, unit) in {Hari: days, Jam: hours, Menit: minutes, Detik: seconds}">
                                        <div class="flex flex-col items-center">
                                            <div class="w-20 h-20 md:w-28 md:h-28 bg-gray-50 border-2 border-gray-100 rounded-3xl flex items-center justify-center shadow-sm">
                                                <span class="text-3xl md:text-5xl font-black text-gray-800 font-mono" x-text="val.toString().padStart(2, '0')"></span>
                                            </div>
                                            <span class="mt-3 text-[10px] font-black text-gray-400 uppercase tracking-widest" x-text="unit"></span>
                                        </div>
                                    </template>
                                </div>

                                <p class="text-gray-400 italic font-bold">Ujian akan dilaksanakan pada 20 Mei 2026, Pukul 08:00 WIB.</p>
                            </div>

                        {{-- Kondisi 3: MENUNGGU VERIFIKASI --}}
                        @elseif($status == 'verification')
                            <div class="relative z-10 space-y-10">
                                <div class="w-24 h-24 bg-blue-50 text-[#1E78D0] rounded-3xl flex items-center justify-center mx-auto animate-bounce shadow-lg">
                                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                </div>
                                <div class="space-y-4">
                                    <h2 class="text-3xl md:text-4xl font-black text-gray-900 uppercase leading-none">Menunggu Verifikasi</h2>
                                    <p class="text-lg font-bold text-gray-500 max-w-md mx-auto leading-relaxed">
                                        Silakan tunjukkan <span class="text-blue-600 font-black">Kartu Peserta Ujian</span> kepada pengawas di ruangan untuk proses autentikasi.
                                    </p>
                                </div>
                                <div class="inline-block px-6 py-3 bg-blue-50 border border-blue-100 rounded-2xl">
                                    <p class="text-xs font-black text-blue-600 uppercase tracking-widest">Sistem Standby...</p>
                                </div>
                            </div>

                        {{-- Kondisi 4: SIAP MULAI --}}
                        @elseif($status == 'ready')
                            <div class="relative z-10 space-y-10" x-data="{ showModal: false, agreed: false }">
                                <div class="space-y-4">
                                    <h2 class="text-4xl font-black text-gray-900 uppercase">
                                        {{ $access['examStarted'] ? 'Ujian Sedang Berjalan' : 'Sistem Siap' }}
                                    </h2>
                                    <p class="text-lg font-bold text-gray-500 max-w-lg mx-auto">
                                        @if($access['examStarted'])
                                            Timer ujian sudah berjalan berdasarkan waktu server. Tetap berada di halaman ujian dan ikuti arahan pengawas.
                                        @else
                                            Anda sudah check-in. Baca instruksi ujian, lalu tekan tombol mulai saat benar-benar siap.
                                        @endif
                                    </p>
                                </div>

                                <div class="grid gap-4 text-left md:grid-cols-3">
                                    <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5">
                                        <p class="text-xs font-black uppercase tracking-widest text-slate-400">Durasi</p>
                                        <p class="mt-2 text-2xl font-black text-slate-800">{{ $access['durationMinutes'] }} menit</p>
                                    </div>
                                    <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5">
                                        <p class="text-xs font-black uppercase tracking-widest text-slate-400">Mulai</p>
                                        <p class="mt-2 text-2xl font-black text-slate-800">
                                            {{ $access['startedAt'] ? $access['startedAt']->format('H:i') . ' WIB' : 'Belum mulai' }}
                                        </p>
                                    </div>
                                    <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5">
                                        <p class="text-xs font-black uppercase tracking-widest text-slate-400">Sisa Waktu</p>
                                        <p class="mt-2 text-2xl font-black text-slate-800">{{ $access['remainingLabel'] ?? '--:--:--' }}</p>
                                    </div>
                                </div>

                                <div class="rounded-[2rem] border border-blue-100 bg-blue-50 p-6 text-left">
                                    <h3 class="text-sm font-black uppercase tracking-widest text-[#0F4C81]">Instruksi Ujian</h3>
                                    <ul class="mt-4 space-y-3 text-sm font-semibold leading-relaxed text-slate-600">
                                        <li>1. Pastikan koneksi internet stabil dan perangkat tidak berpindah tab selama ujian.</li>
                                        <li>2. Waktu mulai disimpan oleh server saat tombol mulai dikonfirmasi.</li>
                                        <li>3. Jika terjadi kendala teknis, segera hubungi pengawas ruang.</li>
                                    </ul>
                                </div>
                                
                                @if($access['canStart'])
                                    <button @click="showModal = true"
                                        class="group relative bg-[#0D9488] hover:bg-[#0F766E] text-white font-black py-6 px-20 rounded-[2rem] text-3xl shadow-[0_15px_30px_-10px_rgba(13,148,136,0.5)] transition-all duration-300 hover:-translate-y-2 active:translate-y-0">
                                        Mulai Ujian
                                    </button>
                                @else
                                    <a href="{{ route('ujian.show') }}" class="inline-flex items-center justify-center gap-3 rounded-[2rem] bg-emerald-600 px-10 py-5 text-lg font-black text-white shadow-lg transition hover:bg-emerald-700">
                                        <span class="h-3 w-3 rounded-full bg-emerald-500 animate-pulse"></span>
                                        Lanjutkan Ujian
                                    </a>
                                @endif

                                <p class="text-xs font-black text-gray-400 uppercase tracking-widest flex items-center justify-center gap-2">
                                    <span class="w-2 h-2 bg-emerald-500 rounded-full animate-ping"></span>
                                    {{ $access['examStarted'] ? 'Timer server aktif' : 'Menunggu konfirmasi mulai' }}
                                </p>

                                {{-- MODAL KONFIRMASI YANG DIPERMANIS --}}
                                <div x-show="showModal" x-transition style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
                                    <div class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm" @click="showModal = false"></div>
                                    
                                    <div class="relative bg-white rounded-[2.5rem] shadow-2xl w-full max-w-xl overflow-hidden border border-gray-100">
                                        <div class="bg-gradient-to-r from-[#1E78D0] to-[#5C9CE0] p-8 text-center relative">
                                            <img src="{{ asset('assets/images/corner.png') }}" class="absolute top-0 left-0 h-full opacity-20 transform -scale-x-100 -rotate-90 pointer-events-none">
                                            <h3 class="text-2xl font-black text-white relative z-10 uppercase tracking-tight">Konfirmasi Pelaksanaan</h3>
                                        </div>
                                        
                                        <form method="POST" action="{{ route('ujian.start') }}" class="p-10 space-y-8">
                                            @csrf

                                            <div class="space-y-4 text-gray-700">
                                                <p class="font-black text-lg italic leading-tight">"Saya menyatakan akan mengerjakan ujian ini dengan jujur dan menjunjung tinggi integritas akademik."</p>
                                                <p class="text-sm font-bold text-slate-500">Setelah dikonfirmasi, status ujian berubah menjadi sedang ujian dan timer server mulai berjalan.</p>
                                            </div>

                                            <label class="flex items-start gap-4 p-5 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200 cursor-pointer group hover:border-blue-300 transition-colors">
                                                <input type="checkbox" name="agree" value="1" x-model="agreed" id="confirm-check" class="mt-1 w-6 h-6 rounded-lg border-2 border-gray-300 text-blue-600 focus:ring-blue-500 transition-all">
                                                <span class="text-sm font-bold text-gray-600 text-left leading-snug group-hover:text-black">
                                                    Saya menyetujui seluruh ketentuan dan bersedia didiskualifikasi jika terbukti curang.
                                                </span>
                                            </label>

                                            <div class="grid grid-cols-2 gap-4">
                                                <button type="button" @click="showModal = false" class="py-4 rounded-2xl font-black text-gray-400 hover:bg-gray-50 transition uppercase tracking-widest text-sm border-2 border-gray-100">Batalkan</button>
                                                <button type="submit" :disabled="!agreed"
                                                    class="py-4 bg-[#1E78D0] hover:bg-[#165DA3] text-white rounded-2xl font-black shadow-lg transition-all uppercase tracking-widest text-sm transform hover:scale-105 disabled:cursor-not-allowed disabled:bg-slate-300 disabled:shadow-none disabled:hover:scale-100">
                                                    Lanjutkan
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            </main>
        </div>
    </div>

</body>

</html>
