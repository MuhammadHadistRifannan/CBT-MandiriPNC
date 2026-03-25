<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'CBT Mandiri PNC') }} - Lupa Password</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-[#F4F7FB]">

    <div class="min-h-screen flex">
        
        @include('auth.sidebar.sidebar')

        <div class="w-full lg:w-1/2 flex justify-center items-center p-6 sm:p-12">
            
            <div class="w-full max-w-md bg-white rounded-xl border border-gray-500 p-8 sm:px-10 sm:py-8 relative overflow-hidden">
                
                <img src="{{ asset('assets/images/corner.png') }}" 
                     alt="Wave Decoration" 
                     class="absolute top-0 right-0 w-32 opacity-100 pointer-events-none transform rotate-90 z-0">

                <div class="relative z-10 mb-6 mt-2">
                    <h2 class="text-2xl font-bold text-black tracking-tight mb-2">Lupa Password?</h2>
                    <p class="text-xs font-medium text-black leading-relaxed">
                        Masukan email aktif yang dimiliki akun anda
                    </p>
                    <div class="h-[1px] w-4/5 bg-gradient-to-r from-gray-400 to-transparent mt-4"></div>
                </div>

                <div class="relative z-10 mb-4">
                    <x-auth-session-status class="text-sm font-medium text-green-600" :status="session('status')" />
                </div>

                <form method="POST" action="{{ route('password.email') }}" class="relative z-10">
                    @csrf

                    <div class="mb-6">
                        <label for="email" class="block text-xs font-bold text-black mb-2">Email</label>
                        <div class="relative flex items-center">
                            <div class="absolute left-3 text-black">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                                </svg>
                            </div>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                                   class="w-full pl-9 pr-4 py-2.5 bg-white border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors placeholder-gray-400"
                                   placeholder="Masukkan Email Aktif">
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs" />
                    </div>

                    <button type="submit" class="w-full bg-[#307DCA] hover:bg-[#2563A3] text-white font-bold py-2.5 px-4 rounded-lg transition duration-300">
                        Reset Password
                    </button>

                    <div class="mt-4 bg-[#EAEAEA] rounded-lg py-2.5 text-center text-sm text-gray-700 font-medium">
                        Ingat password Anda ?  
                        <a href="{{ route('login') }}" class="text-[#2B82D4] font-bold hover:underline">Login sekarang</a>
                    </div>
                </form>

            </div>
        </div>
    </div>

</body>
</html>