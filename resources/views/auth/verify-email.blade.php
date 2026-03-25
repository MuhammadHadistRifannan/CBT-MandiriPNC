<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'CBT Mandiri PNC') }} - Verifikasi Email</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-[#F4F7FB]">

    <div class="min-h-screen flex">
        
        @include('auth.sidebar.sidebar')

        <div class="w-full lg:w-1/2 flex justify-center items-center p-6 sm:p-12">
            
            <div class="w-full max-w-md bg-white rounded-xl border border-gray-500 p-8 sm:px-10 sm:py-10 relative overflow-hidden text-center">
                
                <img src="{{ asset('assets/images/corner.png') }}" 
                     alt="Wave Decoration" 
                     class="absolute top-0 right-0 w-32 opacity-100 pointer-events-none transform rotate-90 z-0">

                <div class="relative z-10 mb-6 mt-2">
                    <h2 class="text-2xl font-extrabold text-black tracking-tight mb-2">Verifikasi Email Peserta</h2>
                    <p class="text-sm font-medium text-black">
                        Kami telah mengirim link verifikasi email ke akun anda
                    </p>
                    <div class="h-[1px] w-full bg-gradient-to-r from-transparent via-gray-400 to-transparent mt-6 mb-8"></div>
                </div>

                @if (session('status') == 'verification-link-sent')
                    <div class="relative z-10 mb-6 font-medium text-xs text-green-600 bg-green-50 p-3 rounded-lg border border-green-200 text-left">
                        {{ __('Link verifikasi baru telah dikirim ke email Anda.') }}
                    </div>
                @endif

                <div class="relative z-10 space-y-4">
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button type="submit" class="w-full bg-[#307DCA] hover:bg-[#2563A3] text-white font-bold py-3 px-4 rounded-lg transition duration-300 shadow-sm">
                            Kirim Ulang Link Verifikasi
                        </button>
                    </form>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full bg-[#EAEAEA] hover:bg-gray-300 text-black font-bold py-3 px-4 rounded-lg transition duration-300">
                            Logout
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>

</body>
</html>