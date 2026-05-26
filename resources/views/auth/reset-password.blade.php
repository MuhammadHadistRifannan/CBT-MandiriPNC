<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'CBT Mandiri PNC') }} - Reset Password</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#F4F7FB] font-sans antialiased">
    <div class="flex min-h-screen">
        @include('auth.sidebar.sidebar')

        <div class="flex w-full items-center justify-center p-6 sm:p-12 lg:w-1/2">
            <div class="relative w-full max-w-md overflow-hidden rounded-xl border border-gray-500 bg-white p-8 sm:px-10 sm:py-8">
                <img src="{{ asset('assets/images/corner.png') }}"
                    alt="Wave Decoration"
                    class="pointer-events-none absolute right-0 top-0 z-0 w-32 rotate-90 opacity-100">

                <div class="relative z-10 mb-6 mt-2">
                    <h2 class="mb-1 text-2xl font-bold tracking-tight text-black">Buat Password Baru</h2>
                    <p class="text-xs font-medium text-black">Masukkan password baru untuk akun Anda</p>
                    <div class="mt-2 h-[2px] w-full bg-gradient-to-r from-gray-400 to-transparent"></div>
                </div>

                <form method="POST" action="{{ route('password.store') }}" class="relative z-10">
                    @csrf

                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <div class="mb-5">
                        <label for="email" class="mb-2 block text-xs font-bold text-black">Email</label>
                        <div class="relative flex items-center">
                            <div class="absolute left-3 text-black">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z" />
                                </svg>
                            </div>
                            <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}"
                                required autofocus autocomplete="username"
                                class="w-full rounded-lg border border-gray-300 bg-white py-2.5 pl-9 pr-4 text-sm transition-colors placeholder-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                                placeholder="Masukkan Email">
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="mb-5">
                        <label for="password" class="mb-2 block text-xs font-bold text-black">Password Baru</label>
                        <div class="relative flex items-center">
                            <div class="absolute left-3 text-black">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                    <path d="M7 11V7a5 5 0 0110 0v4"></path>
                                </svg>
                            </div>
                            <input id="password" type="password" name="password" required autocomplete="new-password"
                                class="w-full rounded-lg border border-gray-300 bg-white py-2.5 pl-9 pr-10 text-sm transition-colors placeholder-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                                placeholder="Masukkan Password Baru">
                            <button type="button" data-password-toggle="password"
                                class="absolute right-3 text-black transition hover:text-gray-600 focus:outline-none"
                                aria-label="Tampilkan password baru">
                                <svg data-eye-open class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                                <svg data-eye-closed class="hidden h-4 w-4" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"></path>
                                    <line x1="1" y1="1" x2="23" y2="23"></line>
                                </svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="mb-6">
                        <label for="password_confirmation" class="mb-2 block text-xs font-bold text-black">Konfirmasi Password</label>
                        <div class="relative flex items-center">
                            <div class="absolute left-3 text-black">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                    <path d="M7 11V7a5 5 0 0110 0v4"></path>
                                </svg>
                            </div>
                            <input id="password_confirmation" type="password" name="password_confirmation" required
                                autocomplete="new-password"
                                class="w-full rounded-lg border border-gray-300 bg-white py-2.5 pl-9 pr-10 text-sm transition-colors placeholder-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                                placeholder="Ulangi Password Baru">
                            <button type="button" data-password-toggle="password_confirmation"
                                class="absolute right-3 text-black transition hover:text-gray-600 focus:outline-none"
                                aria-label="Tampilkan konfirmasi password">
                                <svg data-eye-open class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                                <svg data-eye-closed class="hidden h-4 w-4" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"></path>
                                    <line x1="1" y1="1" x2="23" y2="23"></line>
                                </svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <button type="submit"
                        class="w-full rounded-lg bg-[#307DCA] px-4 py-2.5 font-bold text-white transition duration-300 hover:bg-[#2563A3]">
                        Reset Password
                    </button>

                    <div class="mt-4 rounded-lg bg-[#EAEAEA] py-2.5 text-center text-sm font-medium text-gray-700">
                        Kembali ke halaman
                        <a href="{{ route('login') }}" class="font-bold text-[#2B82D4] hover:underline">Login</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('[data-password-toggle]').forEach((button) => {
            button.addEventListener('click', () => {
                const input = document.getElementById(button.dataset.passwordToggle);
                const isPassword = input.type === 'password';

                input.type = isPassword ? 'text' : 'password';
                button.querySelector('[data-eye-open]').classList.toggle('hidden', isPassword);
                button.querySelector('[data-eye-closed]').classList.toggle('hidden', !isPassword);
            });
        });
    </script>
</body>
</html>
