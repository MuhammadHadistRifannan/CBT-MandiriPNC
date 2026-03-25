<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'CBT Mandiri PNC') }} - Registrasi</title>

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
                    <h2 class="text-2xl font-bold text-black tracking-tight mb-1">Registrasi Portal</h2>
                    <p class="text-xs font-medium text-black">Buat akun untuk mengikuti ujian mandiri PNC</p>
                    <div class="h-[2px] w-200 bg-gradient-to-r from-gray-400 to-transparent mt-4"></div>
                </div>

                <form method="POST" action="{{ route('register') }}" class="relative z-10">
                    @csrf

                    <div class="mb-4">
                        <label for="name" class="block text-xs font-bold text-black mb-2">Nama</label>
                        <div class="relative flex items-center">
                            <div class="absolute left-3 text-black">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                </svg>
                            </div>
                            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                                   class="w-full pl-9 pr-4 py-2.5 bg-white border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors placeholder-gray-400"
                                   placeholder="Masukkan Nama Peserta">
                        </div>
                        <x-input-error :messages="$errors->get('name')" class="mt-1 text-xs" />
                    </div>

                    <div class="mb-4">
                        <label for="email" class="block text-xs font-bold text-black mb-2">Email</label>
                        <div class="relative flex items-center">
                            <div class="absolute left-3 text-black">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                                </svg>
                            </div>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                                   class="w-full pl-9 pr-4 py-2.5 bg-white border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors placeholder-gray-400"
                                   placeholder="Masukkan Email Peserta">
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs" />
                    </div>

                    <div class="mb-4">
                        <label for="password" class="block text-xs font-bold text-black mb-2">Password</label>
                        <div class="relative flex items-center">
                            <div class="absolute left-3 text-black">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                </svg>
                            </div>
                            <input id="password" type="password" name="password" required autocomplete="new-password"
                                   class="w-full pl-9 pr-10 py-2.5 bg-white border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors placeholder-gray-400"
                                   placeholder="Masukkan Password">
                            <button type="button" onclick="toggleVisibility('password', 'eyeOpen1', 'eyeClosed1')" class="absolute right-3 text-black hover:text-gray-600 focus:outline-none">
                                <svg id="eyeOpen1" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                                <svg id="eyeClosed1" class="w-4 h-4 hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                                    <line x1="1" y1="1" x2="23" y2="23"></line>
                                </svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs" />
                    </div>

                    <div class="mb-6">
                        <label for="password_confirmation" class="block text-xs font-bold text-black mb-2">Confirm Password</label>
                        <div class="relative flex items-center">
                            <div class="absolute left-3 text-black">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                </svg>
                            </div>
                            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                                   class="w-full pl-9 pr-10 py-2.5 bg-white border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors placeholder-gray-400"
                                   placeholder="Masukkan Password">
                            <button type="button" onclick="toggleVisibility('password_confirmation', 'eyeOpen2', 'eyeClosed2')" class="absolute right-3 text-black hover:text-gray-600 focus:outline-none">
                                <svg id="eyeOpen2" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                                <svg id="eyeClosed2" class="w-4 h-4 hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                                    <line x1="1" y1="1" x2="23" y2="23"></line>
                                </svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-xs" />
                    </div>

                    <button type="submit" class="w-full bg-[#307DCA] hover:bg-[#2563A3] text-white font-bold py-2.5 px-4 rounded-lg transition duration-300">
                        Daftar
                    </button>

                    <div class="mt-4 bg-[#EAEAEA] rounded-lg py-2.5 text-center text-sm text-gray-700 font-medium">
                        Sudah punya akun ?  
                        <a href="{{ route('login') }}" class="text-[#2B82D4] font-bold hover:underline">Login sekarang</a>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>
        function toggleVisibility(inputId, eyeOpenId, eyeClosedId) {
            const inputElement = document.getElementById(inputId);
            const eyeOpen = document.getElementById(eyeOpenId);
            const eyeClosed = document.getElementById(eyeClosedId);

            const isPassword = inputElement.getAttribute('type') === 'password';
            
            inputElement.setAttribute('type', isPassword ? 'text' : 'password');
            eyeOpen.classList.toggle('hidden');
            eyeClosed.classList.toggle('hidden');
        }
    </script>
</body>
</html>