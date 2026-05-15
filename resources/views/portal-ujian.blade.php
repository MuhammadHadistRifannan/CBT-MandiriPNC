<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'CBT Mandiri PNC') }} - Portal Ujian</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased text-gray-900 bg-[#F8FAFC]"> <div x-data="{ sidebarExpanded: true }" class="flex h-screen overflow-hidden">

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

            <main class="flex-1 overflow-x-hidden overflow-y-auto p-8 sm:p-12 relative z-0 flex items-center justify-center">

                <div class="w-full max-w-4xl text-center">

                    {{-- WRAPPER KONTEN UTAMA DENGAN CARD --}}
                    <div class="bg-white rounded-[3rem] shadow-2xl border border-gray-100 p-10 md:p-20 relative overflow-hidden">
                        
                        <div class="absolute -top-24 -right-24 w-64 h-64 bg-blue-50 rounded-full blur-3xl opacity-50"></div>
                        <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-emerald-50 rounded-full blur-3xl opacity-50"></div>

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
                            <div class="relative z-10 space-y-10" x-data="{ showModal: false }">
                                <div class="space-y-4">
                                    <h2 class="text-4xl font-black text-gray-900 uppercase">Sistem Siap</h2>
                                    <p class="text-lg font-bold text-gray-500 max-w-lg mx-auto">
                                        Seluruh data telah diverifikasi. Pastikan Anda telah berdoa dan siap secara mental sebelum menekan tombol di bawah.
                                    </p>
                                </div>
                                
                                <button @click="showModal = true"
                                    class="group relative bg-[#0D9488] hover:bg-[#0F766E] text-white font-black py-6 px-20 rounded-[2rem] text-3xl shadow-[0_15px_30px_-10px_rgba(13,148,136,0.5)] transition-all duration-300 hover:-translate-y-2 active:translate-y-0">
                                    Mulai Ujian
                                </button>

                                <p class="text-xs font-black text-gray-400 uppercase tracking-widest flex items-center justify-center gap-2">
                                    <span class="w-2 h-2 bg-emerald-500 rounded-full animate-ping"></span>
                                    Sesi Ujian Aktif
                                </p>

                                {{-- MODAL KONFIRMASI YANG DIPERMANIS --}}
                                <div x-show="showModal" x-transition style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
                                    <div class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm" @click="showModal = false"></div>
                                    
                                    <div class="relative bg-white rounded-[2.5rem] shadow-2xl w-full max-w-xl overflow-hidden border border-gray-100">
                                        <div class="bg-gradient-to-r from-[#1E78D0] to-[#5C9CE0] p-8 text-center relative">
                                            <img src="{{ asset('assets/images/corner.png') }}" class="absolute top-0 left-0 h-full opacity-20 transform -scale-x-100 -rotate-90 pointer-events-none">
                                            <h3 class="text-2xl font-black text-white relative z-10 uppercase tracking-tight">Konfirmasi Pelaksanaan</h3>
                                        </div>
                                        
                                        <div class="p-10 space-y-8">
                                            <div class="space-y-4 text-gray-700">
                                                <p class="font-black text-lg italic leading-tight">"Saya menyatakan akan mengerjakan ujian ini dengan jujur dan menjunjung tinggi integritas akademik."</p>
                                            </div>

                                            <label class="flex items-start gap-4 p-5 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200 cursor-pointer group hover:border-blue-300 transition-colors">
                                                <input type="checkbox" id="confirm-check" class="mt-1 w-6 h-6 rounded-lg border-2 border-gray-300 text-blue-600 focus:ring-blue-500 transition-all">
                                                <span class="text-sm font-bold text-gray-600 text-left leading-snug group-hover:text-black">
                                                    Saya menyetujui seluruh ketentuan dan bersedia didiskualifikasi jika terbukti curang.
                                                </span>
                                            </label>

                                            <div class="grid grid-cols-2 gap-4">
                                                <button @click="showModal = false" class="py-4 rounded-2xl font-black text-gray-400 hover:bg-gray-50 transition uppercase tracking-widest text-sm border-2 border-gray-100">Batalkan</button>
                                                <button class="py-4 bg-[#1E78D0] hover:bg-[#165DA3] text-white rounded-2xl font-black shadow-lg transition-all uppercase tracking-widest text-sm transform hover:scale-105">Lanjutkan</button>
                                            </div>
                                        </div>
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