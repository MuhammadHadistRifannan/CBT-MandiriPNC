<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Ujian - CBT PNC</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .sidebar-gradient {
            background: linear-gradient(180deg, #4facfe 0%, #00f2fe 100%);
            /* Atau gunakan warna biru solid sesuai brand PNC */
            background-color: #1a6db6; 
        }
        /* Style untuk sidebar aktif */
        .nav-active {
            background-color: rgba(255, 255, 255, 0.2);
            border-left: 4px solid white;
        }
    </style>
</head>
@include('sweetalert::alert')
<body class="bg-gray-50 font-sans antialiased">

    <div class="flex min-h-screen">
        <aside class="w-64 sidebar-gradient text-white flex flex-col shadow-xl">
            <div class="p-8 flex flex-col items-center">
                <img src="{{ asset('assets/images/pnc-logo.png') }}" alt="Logo" class="w-20 h-20 mb-4 drop-shadow-lg">
                <h1 class="font-bold text-center leading-tight">CBT Mandiri PNC</h1>
            </div>

            <nav class="flex-grow px-4 space-y-2 mt-4">
                <a href="#" class="flex items-center gap-4 px-4 py-3 rounded-lg hover:bg-white/10 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    <span>Home</span>
                </a>
                <a href="#" class="flex items-center gap-4 px-4 py-3 rounded-lg hover:bg-white/10 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 14l9-5-9-5-9 5 9 5z"></path><path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path></svg>
                    <span>Pilih Prodi</span>
                </a>
                <a href="#" class="flex items-center gap-4 px-4 py-3 rounded-lg nav-active transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                    <span>Mulai Ujian</span>
                </a>
                <a href="#" class="flex items-center gap-4 px-4 py-3 rounded-lg hover:bg-white/10 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <span>Cetak Kartu</span>
                </a>
            </nav>

            <div class="p-6">
                <button class="w-full py-3 bg-orange-500 hover:bg-orange-600 text-white font-bold rounded-xl transition-all shadow-lg flex items-center justify-center gap-2">
                    <span>Sign Out</span>
                </button>
            </div>
        </aside>

        <div class="flex-grow flex flex-col">
            <header class="h-20 bg-white shadow-sm flex items-center justify-between px-10">
                <h2 class="text-2xl font-bold text-gray-800">Portal Ujian</h2>
                <div class="flex items-center gap-4 bg-gray-100 px-4 py-2 rounded-full cursor-pointer hover:bg-gray-200 transition">
                    <img src="https://ui-avatars.com/api/?name=User" class="w-8 h-8 rounded-full border border-blue-500">
                    <span class="font-semibold text-gray-700 text-sm">Profile</span>
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"></path></svg>
                </div>
            </header>

            <main class="flex-grow flex items-center justify-center p-10">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>