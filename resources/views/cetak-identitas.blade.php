<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'CBT Mandiri PNC') }} - Cetak Identitas</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @media print {

            /* 1. Reset Dasar Kertas */
            @page {
                size: A4;
                margin: 0;
                /* Kita atur margin lewat padding kartu saja */
            }

            body {
                margin: 0 !important;
                padding: 0 !important;
                background: white !important;
            }

            /* 2. Sembunyikan semua kecuali kartu */
            body * {
                visibility: hidden !important;
            }

            /* 3. Perbaikan Posisi Kartu (Anti Terpotong) */
            #printable-card,
            #printable-card * {
                visibility: visible !important;
            }

            #printable-card {
                position: relative !important;
                display: block !important;
                margin: 40px auto !important;
                /* Centering otomatis yang stabil */
                width: 18cm !important;
                padding: 40px !important;
                background-color: #173A5E !important;
                border-radius: 20px !important;

                /* Paksa warna agar muncul di printer */
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                box-shadow: inset 0 0 0 1000px #173A5E !important;
            }

            /* 4. Paksa Layout Horizontal (Baris) */
            #printable-card .flex-row-print {
                display: flex !important;
                flex-direction: row !important;
                align-items: flex-start !important;
                gap: 40px !important;
                text-align: left !important;
            }

            /* Pastikan teks tetap putih & tidak transparan */
            #printable-card p,
            #printable-card h3,
            #printable-card span {
                color: white !important;
                opacity: 1 !important;
            }

            /* Hilangkan elemen dashboard lainnya */
            aside,
            header,
            nav,
            button,
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>

<body class="font-sans antialiased text-gray-900 bg-white">

    <div x-data="{ sidebarExpanded: true }" class="flex h-screen overflow-hidden">

        @include('layouts.dashboard.sidebar')

        <div class="flex-1 flex flex-col overflow-hidden relative">

            {{-- HEADER --}}
            <header class="sticky top-0 z-30 bg-white border-b border-slate-200 px-4 lg:px-8 py-4">

                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

                    {{-- LEFT --}}
                    <div class="flex items-start sm:items-center gap-4">

                        {{-- MOBILE MENU --}}
                        <button @click="mobileOpen = true"
                            class="lg:hidden flex items-center justify-center w-11 h-11 rounded-xl bg-[#0F4C81] text-white shrink-0">

                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>

                        {{-- ICON --}}
                        <div
                            class="hidden sm:flex w-14 h-14 rounded-2xl bg-[#0F4C81] text-white items-center justify-center shadow-sm shrink-0">

                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10m-11 9h12a2 2 0 002-2V7a2 2 0 00-2-2H6a2 2 0 00-2 2v11a2 2 0 002 2z" />
                            </svg>
                        </div>

                        {{-- TITLE --}}
                        <div class="min-w-0">

                            <p class="text-xs sm:text-sm font-medium text-[#0F4C81] uppercase tracking-wide">
                                Seleksi Mandiri PNC
                            </p>

                            <h1
                                class="text-xl sm:text-2xl lg:text-3xl font-bold text-slate-800 tracking-tight leading-tight">

                                Cetak Identitas
                            </h1>

                            <p class="hidden sm:block text-sm text-slate-500 mt-1 max-w-2xl">
                                Cetak Identitas Anda untuk divalidasi Pengawas.
                            </p>
                        </div>
                    </div>

                    {{-- RIGHT --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 w-full lg:w-auto">

                        {{-- STEP --}}
                        <div class="bg-slate-100 border border-slate-200 rounded-2xl px-4 py-3">

                            <p class="text-xs text-slate-500">
                                Tahapan
                            </p>

                            <h3 class="text-sm font-semibold text-slate-800">
                                Cetak Identitas
                            </h3>
                        </div>

                        {{-- STATUS --}}
                        <div class="bg-emerald-50 border border-emerald-200 rounded-2xl px-4 py-3">

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



            <main
                class="flex-1 overflow-x-hidden overflow-y-auto bg-white p-8 sm:p-12 relative z-0 flex flex-col items-center">
                @if ($status == 'invalid')
                    <div class="relative z-10 space-y-8">
                        <div
                            class="bg-white rounded-[3rem] shadow-2xl border border-gray-100 p-10 md:p-20 relative overflow-hidden">
                            <div
                                class="w-32 h-32 bg-red-50 text-red-500 rounded-full flex items-center justify-center mx-auto shadow-inner">
                                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                    </path>
                                </svg>
                            </div>
                            <div class="space-y-4 m-4 text-center flex flex-col items-center justify-center">

                                <h2 class="text-4xl font-black text-gray-900 uppercase tracking-tighter">
                                    Akses Terkunci
                                </h2>

                                <p class="text-lg font-bold text-gray-500 leading-relaxed max-w-md mx-auto">

                                    Anda belum melakukan
                                    <span class="text-red-600 underline">
                                        Simpan Permanen
                                    </span>
                                    pilihan program studi.

                                    Silakan selesaikan tahap pemilihan prodi terlebih dahulu.
                                </p>
                            </div>
                            <div class="flex justify-center p-4">
                                <a href="{{ route('prodi.pilih') }}"
                                    class="inline-flex items-center justify-center bg-gray-900 text-white font-black px-10 py-4 rounded-2xl hover:bg-black transition shadow-lg">
                                    Kembali ke Pilih Prodi
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="w-full max-w-4xl">
                @elseif($status == 'valid')
                                    <div class="px-3 sm:px-5 md:px-0">

                                        <h2
                                            class="text-xl sm:text-2xl font-extrabold text-black mb-6 sm:mb-8 no-print text-center md:text-left">
                                            Preview Kartu Ujian
                                        </h2>

                                        <div id="printable-card" class="relative w-full max-w-2xl mx-auto
                        bg-[#173A5E]
                        rounded-[1.8rem] sm:rounded-[2.5rem]
                        p-4 sm:p-8 lg:p-10
                        shadow-2xl overflow-hidden">

                                            {{-- BACKGROUND EFFECT --}}
                                            <div
                                                class="absolute -bottom-10 -right-10 w-44 sm:w-64 h-44 sm:h-64 bg-white/5 rounded-full blur-3xl">
                                            </div>

                                            <div
                                                class="absolute top-0 left-0 w-28 sm:w-40 h-28 sm:h-40 bg-blue-400/10 rounded-full blur-3xl">
                                            </div>

                                            {{-- HEADER --}}
                                            <div class="flex flex-col sm:flex-row items-center sm:items-center gap-4
                            mb-6 sm:mb-8 relative z-10 border-b border-white/20 pb-5 sm:pb-6 text-center sm:text-left">

                                                <div class="bg-white/10 p-2 sm:p-3 rounded-xl backdrop-blur-sm shadow-lg shrink-0">

                                                    <img src="{{ asset('assets/images/pnc-logo.png') }}" alt="Logo PNC"
                                                        class="w-10 h-10 sm:w-12 sm:h-12 object-contain">
                                                </div>

                                                <div>

                                                    <h3
                                                        class="text-white font-black text-sm sm:text-lg md:text-xl tracking-wide uppercase leading-tight">

                                                        Seleksi Mandiri CBT
                                                    </h3>

                                                    <p class="text-slate-200 text-xs sm:text-sm mt-1">
                                                        Politeknik Negeri Cilacap
                                                    </p>
                                                </div>
                                            </div>

                                            {{-- CONTENT --}}
                                            <div class="flex flex-col items-center md:items-start md:flex-row
                            gap-5 sm:gap-8 relative z-10">

                                                {{-- FOTO --}}
                                                <div class="shrink-0">

                                                    <div class="w-28 h-36 sm:w-40 sm:h-52
                                    bg-gray-200 rounded-2xl overflow-hidden
                                    border-[3px] border-white shadow-2xl">

                                                        <img src="{{ asset('storage/avatar-kartu.jpeg') }}" alt="Foto Peserta"
                                                            class="w-full h-full object-cover">
                                                    </div>
                                                </div>

                                                {{-- DATA --}}
                                                <div class="flex-grow text-white w-full">

                                                    {{-- NAMA --}}
                                                    <div class="space-y-1 mb-4 sm:mb-5 text-center md:text-left">

                                                        <p
                                                            class="text-[10px] sm:text-xs uppercase tracking-[0.2em] text-white font-bold opacity-70">

                                                            Nama Peserta
                                                        </p>

                                                        <p
                                                            class="text-base sm:text-xl md:text-2xl font-black leading-tight break-words">

                                                            Muhammad Hadist Rifannan
                                                        </p>
                                                    </div>

                                                    {{-- NOMOR --}}
                                                    <div class="space-y-1 mb-5 sm:mb-6 text-center md:text-left">

                                                        <p
                                                            class="text-[10px] sm:text-xs uppercase tracking-[0.2em] text-white font-bold opacity-70">

                                                            Nomor Peserta
                                                        </p>

                                                        <p class="text-base sm:text-xl md:text-2xl font-black leading-tight">

                                                            2604000129
                                                        </p>
                                                    </div>

                                                    {{-- PRODI --}}
                                                    <div>

                                                        <p
                                                            class="text-[10px] sm:text-xs uppercase tracking-[0.2em] text-white font-bold opacity-70 mb-3 text-center md:text-left">

                                                            Program Studi Pilihan
                                                        </p>

                                                        <div class="space-y-3">

                                                            {{-- PRODI 1 --}}
                                                            <div
                                                                class="bg-white/10 backdrop-blur-md border border-white/10 rounded-2xl px-3 sm:px-4 py-3 flex items-start gap-3 shadow-lg">

                                                                <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-xl bg-[#0F65B6]
                                                flex items-center justify-center
                                                text-[10px] sm:text-xs font-black text-white shrink-0 mt-0.5">

                                                                    1
                                                                </div>

                                                                <div class="min-w-0">

                                                                    <p
                                                                        class="text-xs sm:text-sm font-bold text-white leading-tight break-words">

                                                                        D4 Teknologi Rekayasa Multimedia
                                                                    </p>

                                                                    <p class="text-[10px] sm:text-xs text-slate-200 mt-1">

                                                                        Jurusan Komputer dan Bisnis
                                                                    </p>
                                                                </div>
                                                            </div>

                                                            {{-- PRODI 2 --}}
                                                            <div
                                                                class="bg-white/10 backdrop-blur-md border border-white/10 rounded-2xl px-3 sm:px-4 py-3 flex items-start gap-3 shadow-lg">

                                                                <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-xl bg-white text-[#173A5E]
                                                flex items-center justify-center
                                                text-[10px] sm:text-xs font-black shrink-0 mt-0.5">

                                                                    2
                                                                </div>

                                                                <div class="min-w-0">

                                                                    <p
                                                                        class="text-xs sm:text-sm font-bold text-white leading-tight break-words">

                                                                        D3 Teknik Informatika
                                                                    </p>

                                                                    <p class="text-[10px] sm:text-xs text-slate-200 mt-1">

                                                                        Jurusan Komputer dan Bisnis
                                                                    </p>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>

                                                    {{-- QR --}}
                                                    <div class="mt-6 sm:mt-8 flex justify-center md:justify-start">

                                                        <div class="bg-white p-2 sm:p-3 rounded-2xl shadow-2xl">

                                                            <img src="{{ asset('storage/qr-kartu.png') }}" alt="QR Verification"
                                                                class="w-16 h-16 sm:w-24 sm:h-24">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- FOOTER --}}
                                            <div class="mt-8 sm:mt-10 pt-5 sm:pt-6 border-t border-white/20 text-center relative z-10">

                                                <p class="text-white font-black text-xs sm:text-lg md:text-xl tracking-[0.2em]">

                                                    Tahun Akademik 2025/2026
                                                </p>

                                                <p class="text-slate-300 text-[9px] sm:text-xs mt-2 tracking-wide">

                                                    Kartu Peserta Resmi Ujian Mandiri CBT PNC
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="text-center text-gray-800 italic font-bold text-lg mt-10 mb-10 no-print">
                                        Gunakan kartu ini untuk verifikasi peserta ke pengawas
                                    </p>

                                    <div class="flex flex-wrap justify-center gap-6 no-print">
                                        <button
                                            class="bg-[#D1D5DB] hover:bg-[#9CA3AF] text-gray-800 font-black py-4 px-12 rounded-2xl transition duration-300 shadow-md text-lg">
                                            Ubah foto
                                        </button>
                                        <button onclick="window.print()"
                                            class="bg-[#F39C12] hover:bg-[#D68910] text-white font-black py-4 px-12 rounded-2xl transition duration-300 shadow-lg text-lg flex items-center gap-3">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                                                </path>
                                            </svg>
                                            Cetak Kartu
                                        </button>
                                    </div>
                                </div>
                    @endif

            </main>
        </div>
    </div>

</body>

</html>