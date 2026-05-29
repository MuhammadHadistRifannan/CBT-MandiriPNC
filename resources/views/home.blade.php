<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CBT Mandiri PNC</title>
    <!-- Pastikan Tailwind CSS ter-load di project Laravel Anda -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Custom class untuk background titik-titik (dot pattern) */
        .bg-dots {
            background-color: #ffffff;
            background-image: radial-gradient(#d1d5db 2px, transparent 2px);
            background-size: 40px 40px;
        }

        /* --- CSS UNTUK ANIMASI MUNCUL SAAT SCROLL --- */
        .hide-section {
            opacity: 0;
            transform: translateY(50px);
            transition: opacity 0.8s ease-out, transform 0.8s ease-out;
        }

        .show-section {
            opacity: 1;
            transform: translateY(0);
        }

        /* --- CSS UNTUK KURSOR KEDAP-KEDIP (TYPEWRITER) --- */
        .cursor-blink {
            animation: blink 0.8s step-end infinite;
        }

        @keyframes blink {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0;
            }
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</head>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

{
<body class="font-sans antialiased text-gray-800">

    <!-- NAVBAR -->
    {{-- NAVBAR --}}
    <nav x-data="{ open:false }"
    class="fixed top-0 left-0 right-0 z-50">

    <div
        class="mx-3 md:mx-6 lg:mx-10 mt-3 bg-white/90 backdrop-blur-xl border border-white/40 shadow-lg rounded-2xl">

        {{-- NAVBAR TOP --}}
        <div class="h-20 flex items-center justify-between">

          
                <div
                    class="bg-[#173a5e] h-full flex items-center px-4 md:px-7 gap-3 md:gap-4 rounded-r-[2rem] min-w-fit">

                    <img src="{{ asset('assets/images/pnc-logo.png') }}" alt="Logo PNC"
                        class="w-10 h-10 md:w-12 md:h-12 object-contain">

                    <div class="leading-tight text-white">

                        <h1 class="font-extrabold text-base md:text-lg tracking-wide">
                            CBT PNC
                        </h1>

                        <p class="text-[10px] md:text-xs opacity-90">
                            Ujian Mandiri
                        </p>

                        <p class="text-[9px] md:text-[10px] opacity-70">
                            Politeknik Negeri Cilacap
                        </p>
                    </div>
                </div>

                {{-- DESKTOP MENU --}}
                <div class="hidden lg:flex items-center gap-8 text-[15px] font-semibold text-slate-700 p-4">

                    <a href="#tentang" class="hover:text-[#0F65B6] transition relative group">

                        Tentang

                        <span
                            class="absolute left-0 -bottom-1 w-0 h-0.5 bg-[#0F65B6] transition-all duration-300 group-hover:w-full"></span>
                    </a>

                    <a href="#informasi" class="hover:text-[#0F65B6] transition relative group">

                        Informasi

                        <span
                            class="absolute left-0 -bottom-1 w-0 h-0.5 bg-[#0F65B6] transition-all duration-300 group-hover:w-full"></span>
                    </a>

                    <a href="#alur" class="hover:text-[#0F65B6] transition relative group">

                        Alur

                        <span
                            class="absolute left-0 -bottom-1 w-0 h-0.5 bg-[#0F65B6] transition-all duration-300 group-hover:w-full"></span>
                    </a>

                    <a href="#kontak" class="hover:text-[#0F65B6] transition relative group">

                        Kontak

                        <span
                            class="absolute left-0 -bottom-1 w-0 h-0.5 bg-[#0F65B6] transition-all duration-300 group-hover:w-full"></span>
                    </a>
                    <a href="{{ route('pengumuman.index') }}" class="hover:text-[#0F65B6] transition relative group">

                        Pengumuman

                        <span
                            class="absolute left-0 -bottom-1 w-0 h-0.5 bg-[#0F65B6] transition-all duration-300 group-hover:w-full"></span>
                    </a>
                    @if (auth()->user())
                        <a href="{{ route('dashboard') }}" class="hover:text-[#0F65B6] transition relative group font-extrabold text-lg">
    
                            {{ auth()->user()->name }}
    
                            <span
                                class="absolute left-0 -bottom-1 w-0 h-0.5 bg-[#0F65B6] transition-all duration-300 group-hover:w-full"></span>
                        </a>
                        @else
                        <a href="{{ route('login') }}" class="hover:text-[#0F65B6] transition relative group">
    
                            Login
    
                            <span
                                class="absolute left-0 -bottom-1 w-0 h-0.5 bg-[#0F65B6] transition-all duration-300 group-hover:w-full"></span>
                        </a>
                        @endif

                </div>

            {{-- MOBILE BUTTON --}}
            <div class="lg:hidden px-4">

                <button @click="open = !open"
                    type="button"
                    class="w-12 h-12 rounded-2xl border border-slate-200 bg-white shadow-sm flex items-center justify-center">

                    {{-- HAMBURGER --}}
                    <svg x-show="!open"
                        class="w-7 h-7 text-slate-700"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">

                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>

                    {{-- CLOSE --}}
                    <svg x-show="open"
                        x-cloak
                        class="w-7 h-7 text-slate-700"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">

                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>

                </button>
            </div>
        </div>

        {{-- MOBILE MENU --}}
        <div x-show="open"
            x-transition
            x-cloak
            class="lg:hidden border-t border-slate-200 bg-white">

            <div class="p-5 space-y-3">

                <a href="#tentang"
                    class="block rounded-2xl px-4 py-4 hover:bg-slate-100 text-sm font-semibold text-slate-700">
                    Tentang
                </a>

                <a href="#informasi"
                    class="block rounded-2xl px-4 py-4 hover:bg-slate-100 text-sm font-semibold text-slate-700">
                    Informasi
                </a>

                <a href="#alur"
                    class="block rounded-2xl px-4 py-4 hover:bg-slate-100 text-sm font-semibold text-slate-700">
                    Alur
                </a>

                <a href="#kontak"
                    class="block rounded-2xl px-4 py-4 hover:bg-slate-100 text-sm font-semibold text-slate-700">
                    Kontak
                </a>
                <a href="{{ route('pengumuman.index') }}"
                    class="block rounded-2xl px-4 py-4 hover:bg-slate-100 text-sm font-semibold text-slate-700">
                    Pengumuman
                </a>
                @if (auth()->user())
                <a href="{{ route('dashboard') }}"
                    class="block rounded-2xl px-4 py-4 hover:bg-slate-100 text-sm font-extrabold text-slate-700">
                    {{ auth()->user()->name }}
                </a>
                @else
                <a href="{{ route('login') }}"
                    class="block rounded-2xl px-4 py-4 hover:bg-slate-100 text-sm font-semibold text-slate-700">
                    Login
                </a>
                @endif

            </div>
        </div>

    </div>
</nav>

    <!-- KONTEN UTAMA DENGAN BACKGROUND DOTS -->
    <main class="bg-dots pt-20">

        <!-- WRAPPER HERO SECTION -->
        <div class="px-4 md:px-12 lg:px-20 pt-6 hide-section">

            <!-- HERO SECTION (ID: beranda) -->
            <section id="beranda"
                class="relative overflow-hidden rounded-[2rem] min-h-[550px] flex items-center justify-center text-center shadow-xl border border-white/20"
                style="background-image: url('{{ asset('assets/images/bg.png') }}');">
                <div class="absolute inset-0 bg-gradient-to-b from-black/40 via-black/20 to-black/50"></div>


                <div class="relative z-10">
                    <h1 class="text-4xl md:text-6xl font-black text-white leading-tight">Sistem Ujian Mandiri</h1>
                    <img src="{{ asset('assets/images/pnc-logo.png') }}" alt="Logo PNC Besar"
                        class="w-24 h-24 mx-auto mb-6 object-contain drop-shadow-[0_10px_8px_rgba(0,0,0,0.6)]">
                    <h2 class="text-2xl md:text-3xl font-medium italic text-black mb-6">Computer Based Test (CBT)</h2>

                    <!-- TEKS INI YANG AKAN DIANIMASIKAN MESIN TIK -->
                    <h3 class="text-2xl md:text-3xl font-extrabold text-black mt-2 tracking-wide h-10">
                        <span id="typewriter-text"></span><span class="cursor-blink">|</span>
                    </h3>
                </div>
            </section>
        </div>

        <!-- SECTION: APA ITU CBT (ID: tentang) -->
        <section id="tentang" class="py-24 px-6 max-w-6xl mx-auto scroll-mt-16 hide-section">
            <div class="flex flex-col items-center text-center mb-16">
                <span
                    class="px-4 py-1.5 bg-blue-50 text-[#0F65B6] text-xs font-bold uppercase tracking-widest rounded-full mb-4">Mengenal
                    Sistem</span>
                <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 leading-tight">
                    Apa Itu CBT MANDIRI <br>
                    <span class="text-[#0F65B6]">POLITEKNIK NEGERI CILACAP?</span>
                </h2>
                <div class="w-20 h-1.5 bg-[#0F65B6] mt-6 rounded-full"></div>
                <p class="mt-8 text-base md:text-lg leading-relaxed text-gray-600 max-w-3xl">
                    Sistem seleksi penerimaan mahasiswa baru berbasis digital yang dirancang untuk menggantikan metode
                    ujian tulis konvensional guna menjamin transparansi, keamanan, dan kecepatan evaluasi hasil ujian.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

                <div
                    class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300 group text-center">
                    <div
                        class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:bg-[#0F65B6] transition-colors duration-300">
                        <svg class="w-8 h-8 text-[#0F65B6] group-hover:text-white" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-gray-800">Real-Time & Akurat</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Hasil ujian diproses otomatis oleh sistem, meminimalisir <span
                            class="font-semibold italic">human error</span> dalam pengkoreksian.
                    </p>
                </div>

                <div
                    class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300 group text-center">
                    <div
                        class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:bg-[#0F65B6] transition-colors duration-300">
                        <svg class="w-8 h-8 text-[#0F65B6] group-hover:text-white" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-gray-800">Keamanan Terjamin</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Soal diacak otomatis (randomize) untuk setiap peserta guna menjaga integritas dan objektivitas
                        hasil seleksi.
                    </p>
                </div>

                <div
                    class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300 group text-center">
                    <div
                        class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:bg-[#0F65B6] transition-colors duration-300">
                        <svg class="w-8 h-8 text-[#0F65B6] group-hover:text-white" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-gray-800">Efisiensi Waktu</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Navigasi soal yang mudah membantu peserta fokus mengerjakan ujian tanpa ribet dengan lembar
                        jawaban kertas.
                    </p>
                </div>

            </div>
        </section>

        <!-- SECTION: ALUR PELAKSANAAN (ID: alur) -->
        <section id="alur" class="py-20 px-6 max-w-5xl mx-auto scroll-mt-16 relative overflow-hidden hide-section">
            <div class="text-center mb-16 relative z-20">
                <h2 class="text-2xl md:text-3xl font-extrabold text-black">Alur Pelaksanaan Ujian Mandiri</h2>
                <h3 class="text-xl md:text-2xl font-extrabold text-black mt-1">POLITEKNIK NEGERI CILACAP</h3>
                <p class="mt-4 text-black text-sm md:text-base">Informasi tahapan Ujian Mandiri Politeknik Negeri
                    Cilacap</p>
            </div>

            <div class="relative w-full py-8">
                <!-- Logo Background Transparan di Tengah -->
                <div class="absolute inset-0 flex items-center justify-center pointer-events-none z-0">
                    <img src="{{ asset('assets/images/pnc-logo.png') }}" alt="Background Logo PNC"
                        class="w-[300px] md:w-[500px] object-contain opacity-15 grayscale">
                </div>

                <!-- Garis Tengah Hitam -->
                <div class="absolute left-1/2 transform -translate-x-1/2 w-1 bg-black h-full z-10"></div>

                <!-- Step 1: Kiri -->
                <div
                    class="flex justify-between items-center w-full mb-12 hover:scale-[1.02] transition-transform duration-300 cursor-default relative z-20">
                    <div class="w-5/12">
                        <div class="border-[3px] border-black bg-white rounded-xl overflow-hidden shadow-md">
                            <div class="p-5 text-center min-h-[100px] flex flex-col justify-center">
                                <h4 class="font-bold text-black text-base md:text-lg mb-2">Pendaftaran</h4>
                                <p class="text-[10px] md:text-xs text-black leading-snug px-2">Tahap awal dimana semua
                                    peserta melakukan registrasi akun CBT Mandiri PNC</p>
                            </div>
                            <div
                                class="bg-[#1a6db6] text-white p-3 text-center font-bold text-sm md:text-base border-t-[3px] border-black">
                                3-26 April 2026</div>
                        </div>
                    </div>
                    <div class="w-2/12 flex justify-center">
                        <div
                            class="w-12 h-12 md:w-14 md:h-14 rounded-full bg-[#1a6db6] text-white flex items-center justify-center font-extrabold text-xl md:text-2xl relative z-10 shadow-lg">
                            1</div>
                    </div>
                    <div class="w-5/12"></div>
                </div>

                <!-- Step 2: Kanan -->
                <div
                    class="flex justify-between items-center w-full mb-12 hover:scale-[1.02] transition-transform duration-300 cursor-default relative z-20">
                    <div class="w-5/12"></div>
                    <div class="w-2/12 flex justify-center">
                        <div
                            class="w-12 h-12 md:w-14 md:h-14 rounded-full bg-[#1a6db6] text-white flex items-center justify-center font-extrabold text-xl md:text-2xl relative z-10 shadow-lg">
                            2</div>
                    </div>
                    <div class="w-5/12">
                        <div class="border-[3px] border-black bg-white rounded-xl overflow-hidden shadow-md">
                            <div class="p-5 text-center min-h-[100px] flex flex-col justify-center">
                                <h4 class="font-bold text-black text-base md:text-lg mb-2">Verifikasi Data</h4>
                                <p class="text-[10px] md:text-xs text-black leading-snug px-2">Peserta melakukan login
                                    menggunakan nomor pendaftaran dan kata sandi yang telah didaftarkan pada portal
                                    Sistem Ujian Mandiri.</p>
                            </div>
                            <div
                                class="bg-[#1a6db6] text-white p-3 text-center font-bold text-sm md:text-base border-t-[3px] border-black">
                                5 - 17 Mei 2026</div>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Kiri -->
                <div
                    class="flex justify-between items-center w-full mb-12 hover:scale-[1.02] transition-transform duration-300 cursor-default relative z-20">
                    <div class="w-5/12">
                        <div class="border-[3px] border-black bg-white rounded-xl overflow-hidden shadow-md">
                            <div class="p-5 text-center min-h-[100px] flex flex-col justify-center">
                                <h4 class="font-bold text-black text-base md:text-lg mb-2">Pelaksanaan Ujian</h4>
                                <p class="text-[10px] md:text-xs text-black leading-snug px-2">Peserta mengikuti ujian
                                    berbasis komputer secara luring sesuai dengan jadwal dan sesi yang tertera pada
                                    kartu ujian</p>
                            </div>
                            <div
                                class="bg-[#1a6db6] text-white p-3 text-center font-bold text-sm md:text-base border-t-[3px] border-black">
                                20 - 25 Mei 2026</div>
                        </div>
                    </div>
                    <div class="w-2/12 flex justify-center">
                        <div
                            class="w-12 h-12 md:w-14 md:h-14 rounded-full bg-[#1a6db6] text-white flex items-center justify-center font-extrabold text-xl md:text-2xl relative z-10 shadow-lg">
                            3</div>
                    </div>
                    <div class="w-5/12"></div>
                </div>

                <!-- Step 4: Kanan -->
                <div
                    class="flex justify-between items-center w-full mb-12 hover:scale-[1.02] transition-transform duration-300 cursor-default relative z-20">
                    <div class="w-5/12"></div>
                    <div class="w-2/12 flex justify-center">
                        <div
                            class="w-12 h-12 md:w-14 md:h-14 rounded-full bg-[#1a6db6] text-white flex items-center justify-center font-extrabold text-xl md:text-2xl relative z-10 shadow-lg">
                            4</div>
                    </div>
                    <div class="w-5/12">
                        <div class="border-[3px] border-black bg-white rounded-xl overflow-hidden shadow-md">
                            <div class="p-5 text-center min-h-[100px] flex flex-col justify-center">
                                <h4 class="font-bold text-black text-base md:text-lg mb-2">Pengumuman Hasil</h4>
                                <p class="text-[10px] md:text-xs text-black leading-snug px-2">Peserta mengikuti ujian
                                    berbasis komputer secara luring sesuai dengan jadwal dan sesi yang tertera pada
                                    kartu ujian</p>
                            </div>
                            <div
                                class="bg-[#1a6db6] text-white p-3 text-center font-bold text-sm md:text-base border-t-[3px] border-black">
                                20 - 25 Mei 2026</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- SECTION: LOGO JURUSAN -->
        <section class="py-12 flex flex-wrap justify-center items-center gap-8 md:gap-16 px-6 hide-section">
            <div class="flex flex-col items-center hover:-translate-y-1 transition-transform duration-300">
                <img src="{{ asset('assets/images/trm-logo.png') }}" alt="Logo TRM"
                    class="w-32 h-16 object-contain mb-2">
                <span class="text-xs text-center font-medium">Teknik Elektronika - Jurusan<br>Teknik Elektronika</span>
            </div>
            <div class="flex flex-col items-center hover:-translate-y-1 transition-transform duration-300">
                <img src="{{ asset('assets/images/jkb-logo.png') }}" alt="Logo JKB"
                    class="w-24 h-24 object-contain mb-2">
                <span class="text-xs text-center font-medium">Jurusan Komputer dan Bisnis</span>
            </div>
            <div class="flex flex-col items-center hover:-translate-y-1 transition-transform duration-300">
                <img src="{{ asset('assets/images/jrmip-logo.png') }}" alt="Logo JRMIP"
                    class="w-32 h-16 object-contain mb-2">
                <span class="text-xs text-center font-medium">Jurusan Rekayasa Mesin<br>dan Industri Pertanian</span>
            </div>
        </section>

        <!-- SECTION: INFORMASI UJIAN MANDIRI (ID: informasi) -->
        <section id="informasi" class="py-20 px-6 max-w-6xl mx-auto scroll-mt-16 hide-section">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-extrabold mb-2 text-[#0F65B6]">Informasi Penting</h2>
                <p class="text-gray-600 font-medium italic">Harap perhatikan detail pelaksanaan berikut ini</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                <div
                    class="group bg-white p-6 rounded-2xl shadow-sm border-l-4 border-[#0F65B6] hover:shadow-md transition-all duration-300 flex gap-5">
                    <div
                        class="flex-shrink-0 w-12 h-12 bg-blue-50 text-[#0F65B6] rounded-xl flex items-center justify-center group-hover:bg-[#0F65B6] group-hover:text-white transition-colors">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-lg mb-2 text-gray-800">1. Persiapan Dokumen</h4>
                        <ul class="text-sm text-gray-600 space-y-1 list-disc pl-4">
                            <li>Wajib Bawa Kartu Peserta dan Identitas Asli (KTP/Kartu Pelajar).</li>
                            <li>Pastikan <span class="font-bold">QR Code</span> pada kartu tercetak jelas untuk
                                validasi.</li>
                        </ul>
                    </div>
                </div>

                <div
                    class="group bg-white p-6 rounded-2xl shadow-sm border-l-4 border-[#0F65B6] hover:shadow-md transition-all duration-300 flex gap-5">
                    <div
                        class="flex-shrink-0 w-12 h-12 bg-blue-50 text-[#0F65B6] rounded-xl flex items-center justify-center group-hover:bg-[#0F65B6] group-hover:text-white transition-colors">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-lg mb-2 text-gray-800">2. Teknis Pelaksanaan</h4>
                        <ul class="text-sm text-gray-600 space-y-1 list-disc pl-4">
                            <li>Login dibuka <span class="font-bold text-red-500">15 menit</span> sebelum mulai.</li>
                            <li>Sistem CBT menyimpan jawaban otomatis secara real-time.</li>
                        </ul>
                    </div>
                </div>

                <div
                    class="group bg-white p-6 rounded-2xl shadow-sm border-l-4 border-red-500 hover:shadow-md transition-all duration-300 flex gap-5">
                    <div
                        class="flex-shrink-0 w-12 h-12 bg-red-50 text-red-500 rounded-xl flex items-center justify-center group-hover:bg-red-500 group-hover:text-white transition-colors">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-lg mb-2 text-gray-800 text-red-600">3. Aturan & Larangan</h4>
                        <ul class="text-sm text-gray-600 space-y-1 list-disc pl-4">
                            <li>Kecurangan terdeteksi sistem = <span
                                    class="font-bold underline text-red-600">Diskualifikasi Otomatis</span>.</li>
                            <li>Dilarang berbicara atau menggunakan alat bantu apapun.</li>
                        </ul>
                    </div>
                </div>

                <div
                    class="group bg-white p-6 rounded-2xl shadow-sm border-l-4 border-[#0F65B6] hover:shadow-md transition-all duration-300 flex gap-5">
                    <div
                        class="flex-shrink-0 w-12 h-12 bg-blue-50 text-[#0F65B6] rounded-xl flex items-center justify-center group-hover:bg-[#0F65B6] group-hover:text-white transition-colors">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-lg mb-2 text-gray-800">4. Pasca Ujian</h4>
                        <ul class="text-sm text-gray-600 space-y-1 list-disc pl-4">
                            <li>Cek status kelulusan berkala melalui portal resmi ini.</li>
                            <li>Waspada penipuan! Biaya pendidikan hanya melalui skema UKT.</li>
                        </ul>
                    </div>
                </div>

            </div>

            <div class="mt-12 p-4 bg-[#0F65B6]/5 rounded-lg border border-[#0F65B6]/10 text-center">
                <p class="text-sm text-gray-600 flex items-center justify-center gap-2 italic">
                    <svg class="w-5 h-5 text-[#0F65B6]" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                            clip-rule="evenodd"></path>
                    </svg>
                    Jika ada informasi yang kurang jelas, silakan hubungi nomor WhatsApp yang tertera di bagian kontak.
                </p>
            </div>
        </section>

        <!-- SECTION: HELPDESK (ID: kontak) -->
        <section id="kontak" class="py-20 px-6 text-center scroll-mt-16 hide-section">
            <h2 class="text-2xl font-bold mb-4">Pusat Bantuan (Helpdesk)</h2>
            <p class="text-base mb-10 text-gray-600 max-w-lg mx-auto">
                Jika mengalami kendala teknis saat pendaftaran atau pelaksanaan CBT, <strong>silakan hubungi</strong>:
            </p>

            <div class="flex flex-col mx-auto max-w-sm space-y-5 mb-10">
                <a href="https://wa.me/6282226235099" target="_blank"
                    class="flex items-center gap-4 bg-white p-5 rounded-xl shadow-sm border border-gray-200 hover:shadow-md hover:border-blue-300 hover:-translate-y-1 transition-all duration-300 group text-left">
                    <div
                        class="w-14 h-14 bg-blue-50 rounded-full flex flex-shrink-0 items-center justify-center group-hover:bg-[#0F65B6] transition-colors duration-300">
                        <svg class="w-6 h-6 text-[#0F65B6] group-hover:text-white transition-colors duration-300"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500 mb-0.5">WhatsApp</div>
                        <div class="font-bold text-gray-800 group-hover:text-[#0F65B6] transition-colors text-lg">+62
                            822 2623 5099</div>
                    </div>
                </a>

                <a href="mailto:cbt.mandiri@pnc.ac.id"
                    class="flex items-center gap-4 bg-white p-5 rounded-xl shadow-sm border border-gray-200 hover:shadow-md hover:border-blue-300 hover:-translate-y-1 transition-all duration-300 group text-left">
                    <div
                        class="w-14 h-14 bg-blue-50 rounded-full flex flex-shrink-0 items-center justify-center group-hover:bg-[#0F65B6] transition-colors duration-300">
                        <svg class="w-6 h-6 text-[#0F65B6] group-hover:text-white transition-colors duration-300"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500 mb-0.5">Email</div>
                        <div class="font-bold text-gray-800 group-hover:text-[#0F65B6] transition-colors text-lg">
                            cbt.mandiri@pnc.ac.id</div>
                    </div>
                </a>
            </div>

            <div
                class="inline-flex items-center gap-2 text-sm font-semibold text-[#0F65B6] bg-blue-50 border border-blue-100 py-2.5 px-5 rounded-full">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Jam Operasional: Senin - Jumat (08:00 - 16:00 WIB)
            </div>
        </section>

    </main>

    <!-- FOOTER -->
    <footer class="bg-[#0F65B6] text-white pt-10 pb-4 px-6 md:px-16">
        <!-- ... (Isi footer sama seperti sebelumnya) ... -->
        <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8 mb-8 border-b border-blue-400 pb-8">
            <div>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-white rounded-md flex items-center justify-center">
                        <img src="{{ asset('assets/images/pnc-logo.png') }}" alt="Logo PNC"
                            class="w-8 h-8 object-contain hover:scale-110 transition-transform duration-300">
                    </div>
                    <div>
                        <div class="font-bold text-lg leading-none">CBT PNC</div>
                        <div class="text-xs">Ujian Mandiri</div>
                    </div>
                </div>
                <p class="text-xs leading-relaxed opacity-90">Sistem Ujian Mandiri berbasis Komputer (CBT)
                    diselenggarakan<br>secara transparan, akuntabel, dan terintegrasi di lingkungan kampus.</p>
                <div class="mt-4 flex gap-3 text-sm">
                    <span class="font-medium">Follow us:</span>
                    <a href="#" class="hover:text-gray-300 hover:-translate-y-1 transition-all duration-300">IG</a>
                    <a href="#" class="hover:text-gray-300 hover:-translate-y-1 transition-all duration-300">FB</a>
                    <a href="#" class="hover:text-gray-300 hover:-translate-y-1 transition-all duration-300">YT</a>
                </div>
            </div>
            <div>
                <h3 class="font-bold mb-4">Kontak Kami</h3>
                <p class="text-xs opacity-90 leading-relaxed">Jl. Dr. Soetomo No. 1, Sidakaya, Cilacap<br>Email:
                    humas@pnc.ac.id<br>Telepon: (0282) 533329</p>
            </div>
            <div>
                <h3 class="font-bold mb-4">Pintasan</h3>
                <ul class="text-xs space-y-2 opacity-90">
                    <li><a href="#beranda" class="hover:underline hover:text-gray-200 transition-colors">Beranda</a>
                    </li>
                    <li><a href="#tentang" class="hover:underline hover:text-gray-200 transition-colors">Tentang</a>
                    </li>
                    <li><a href="#alur" class="hover:underline hover:text-gray-200 transition-colors">Alur
                            Pelaksanaan</a></li>
                    <li><a href="#informasi" class="hover:underline hover:text-gray-200 transition-colors">Informasi
                            Penting</a></li>
                    <li><a href="#kontak" class="hover:underline hover:text-gray-200 transition-colors">Kontak
                            Bantuan</a></li>
                </ul>
            </div>
        </div>
        <div class="text-center text-xs opacity-75">&copy; 2026 UPT TIK - Politeknik Negeri Cilacap. All rights
            reserved.</div>
    </footer>

    <!-- LOGIKA JAVASCRIPT UNTUK ANIMASI -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {

            // 1. ANIMASI MUNCUL SAAT SCROLL (Intersection Observer)
            const observerOptions = {
                root: null,
                rootMargin: '0px',
                threshold: 0.15 // Animasi jalan saat 15% bagian section sudah terlihat di layar
            };

            const observer = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('show-section');
                        // Optional: hentikan observasi setelah elemen muncul agar tidak berulang
                        // observer.unobserve(entry.target); 
                    }
                });
            }, observerOptions);

            const hiddenSections = document.querySelectorAll('.hide-section');
            hiddenSections.forEach(sec => observer.observe(sec));

            // 2. ANIMASI KETIK (TYPEWRITER) DI HERO SECTION
            const textToType = "POLITEKNIK NEGERI CILACAP";
            const typeSpeed = 100; // Kecepatan ngetik (ms)
            let charIndex = 0;
            const targetElement = document.getElementById("typewriter-text");

            function typeWriter() {
                if (charIndex < textToType.length) {
                    targetElement.innerHTML += textToType.charAt(charIndex);
                    charIndex++;
                    setTimeout(typeWriter, typeSpeed);
                }

            }

            // Beri sedikit delay sebelum animasi ngetik dimulai (biar hero section naik dulu)
            setTimeout(typeWriter, 1000);

        });
    </script>
</body>

</html>
