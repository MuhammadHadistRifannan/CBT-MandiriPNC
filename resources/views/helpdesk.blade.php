

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'CBT Mandiri PNC') }} - Pilih Prodi</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased text-gray-900 bg-[#F8FAFC]"> <div x-data='{ 
        
    }' class="flex h-screen overflow-hidden">

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

                    Helpdesk
                </h1>

                <p
                    class="hidden sm:block text-sm text-slate-500 mt-1 max-w-2xl">
                    Pusat bantuan peserta CBT Mandiri.
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
                    Helpdesk
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

            {{-- WRAP CONTENT --}}
<main class="flex-1 overflow-y-auto bg-slate-50 p-4 lg:p-8">

    <div class="max-w-5xl mx-auto space-y-6">

        {{-- HEADER --}}
        <div>

            <p class="text-sm text-[#0F4C81] font-medium uppercase tracking-wide">
                Bantuan Peserta
            </p>

            <h1 class="text-3xl font-bold text-slate-800 mt-1">
                Help Desk CBT Mandiri
            </h1>

            <p class="text-sm text-slate-500 mt-2 max-w-2xl">
                Jika mengalami kendala saat proses pendaftaran atau ujian,
                silakan hubungi help desk resmi Politeknik Negeri Cilacap.
            </p>
        </div>

        {{-- GRID --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

            {{-- WHATSAPP --}}
            <div
                class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm">

                <div class="flex items-start gap-4">

                    <div
                        class="w-14 h-14 rounded-2xl bg-green-100 text-green-600 flex items-center justify-center">

                        <svg class="w-7 h-7"
                            fill="currentColor"
                            viewBox="0 0 24 24">

                            <path d="M20.52 3.48A11.79 11.79 0 0012.04 0C5.4 0 .02 5.38.02 12c0 2.1.55 4.16 1.6 5.98L0 24l6.18-1.6A11.96 11.96 0 0012.04 24C18.68 24 24 18.62 24 12c0-3.2-1.25-6.2-3.48-8.52z" />
                        </svg>
                    </div>

                    <div class="flex-1">

                        <h2 class="text-xl font-bold text-slate-800">
                            WhatsApp Help Desk
                        </h2>

                        <p class="text-sm text-slate-500 mt-2">
                            Hubungi admin untuk bantuan teknis dan kendala sistem CBT.
                        </p>

                        <div class="mt-5">

                            <p class="text-sm text-slate-500">
                                Nomor Admin
                            </p>

                            <h3 class="text-lg font-semibold text-slate-800">
                                0812-3456-7890
                            </h3>
                        </div>

                        <a href="https://wa.me/6281234567890"
                            target="_blank"
                            class="mt-6 inline-flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white px-5 py-3 rounded-2xl font-medium transition">

                            Hubungi Sekarang
                        </a>
                    </div>
                </div>
            </div>

            {{-- EMAIL --}}
            <div
                class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm">

                <div class="flex items-start gap-4">

                    <div
                        class="w-14 h-14 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center">

                        <svg class="w-7 h-7"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M16 12H8m8 4H8m8-8H8m-2 12h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>

                    <div class="flex-1">

                        <h2 class="text-xl font-bold text-slate-800">
                            Email Resmi
                        </h2>

                        <p class="text-sm text-slate-500 mt-2">
                            Kirim pertanyaan atau laporan kendala melalui email resmi.
                        </p>

                        <div class="mt-5">

                            <p class="text-sm text-slate-500">
                                Email
                            </p>

                            <h3 class="text-lg font-semibold text-slate-800">
                                helpdesk@pnc.ac.id
                            </h3>
                        </div>

                        <a href="mailto:helpdesk@pnc.ac.id"
                            class="mt-6 inline-flex items-center gap-2 bg-[#0F4C81] hover:bg-[#0B3B63] text-white px-5 py-3 rounded-2xl font-medium transition">

                            Kirim Email
                        </a>
                    </div>
                </div>
            </div>

        </div>

        {{-- FAQ --}}
        <div
            class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm">

            <h2 class="text-xl font-bold text-slate-800 mb-6">
                Pertanyaan Umum
            </h2>

            <div class="space-y-4">

                <div class="border border-slate-200 rounded-2xl p-5">

                    <h3 class="font-semibold text-slate-800">
                        Apakah pilihan prodi dapat diubah?
                    </h3>

                    <p class="text-sm text-slate-500 mt-2">
                        Tidak. Setelah disimpan permanen, pilihan prodi tidak dapat diubah kembali.
                    </p>
                </div>

                <div class="border border-slate-200 rounded-2xl p-5">

                    <h3 class="font-semibold text-slate-800">
                        Bagaimana jika terjadi logout saat ujian?
                    </h3>

                    <p class="text-sm text-slate-500 mt-2">
                        Segera hubungi help desk agar akun dan sesi ujian dapat diperiksa.
                    </p>
                </div>

            </div>
        </div>

    </div>

</main>

        </div>
    </div>

</body>

</html>