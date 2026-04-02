<x-app-layout>
    <div class="min-h-screen bg-gray-50 py-8 px-4">
        <div class="max-w-md mx-auto bg-white rounded-2xl shadow-sm overflow-hidden">

            {{-- Header --}}
            <div class="relative px-6 pt-6 pb-4 border-b border-gray-100">
                <div class="absolute top-0 right-0 w-24 h-24 overflow-hidden rounded-bl-[60px] rotate-90"
                    > <img src="{{ asset('assets/images/corner.png') }}"/>
                </div>
                <h1 class="text-xl font-bold text-gray-900 relative z-10">Edit Profile</h1>
                <p class="text-sm text-gray-400 relative z-10">Customisasi profile Anda.</p>
            </div>

            <div class="px-6 py-6 space-y-8">

                {{-- Avatar Section --}}
                <div class="flex flex-col items-center gap-2">
                    <div class="relative">
                        <div class="w-24 h-24 rounded-full overflow-hidden border-4 border-white shadow-md">
                            @if (Auth::user()->profile_photo_path)
                                <img src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}"
                                    alt="Avatar" class="w-full h-full object-cover" id="avatar-preview">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=3b82f6&color=fff&size=128"
                                    alt="Avatar" class="w-full h-full object-cover" id="avatar-preview">
                            @endif
                        </div>
                        <label for="avatar-input"
                            class="absolute -bottom-1 -right-1 bg-blue-500 hover:bg-blue-600 text-white rounded-full p-1.5 cursor-pointer shadow transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </label>
                    </div>
                    <form id="avatar-form" action="#" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        <input type="file" id="avatar-input" name="avatar" class="hidden" accept="image/*">
                    </form>
                    <button onclick="document.getElementById('avatar-input').click()"
                        class="text-sm text-blue-500 hover:text-blue-600 font-medium transition-colors">
                        Change Avatar
                    </button>
                </div>

                {{-- Profile Info Section --}}
                <div>
                    <div class="mb-4">
                        <h2 class="text-lg font-bold text-gray-900">Informasi Profile</h2>
                        <p class="text-sm text-gray-400">Update informasi profil dan email akun Anda.</p>
                    </div>

                    <form method="post" action="{{ route('profile.update') }}" class="space-y-4">
                        @csrf
                        @method('patch')

                        {{-- Name --}}
                        <div class="space-y-1.5">
                            <label for="name" class="block text-sm font-semibold text-gray-700">Name</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </span>
                                <input type="text" id="name" name="name"
                                    value="{{ old('name', $user->name) }}"
                                    placeholder="Masukan Nama Anda"
                                    class="w-full pl-9 pr-4 py-2.5 border border-gray-200 rounded-lg text-sm text-gray-700 placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition"
                                    required autofocus autocomplete="name">
                            </div>
                            @if ($errors->get('name'))
                                <p class="text-xs text-red-500 mt-1">{{ $errors->first('name') }}</p>
                            @endif
                        </div>

                        {{-- Email --}}
                        <div class="space-y-1.5">
                            <label for="email" class="block text-sm font-semibold text-gray-700">Email</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </span>
                                <input type="email" id="email" name="email"
                                    value="{{ old('email', $user->email) }}"
                                    placeholder="Masukkan Email Aktif"
                                    class="w-full pl-9 pr-4 py-2.5 border border-gray-200 rounded-lg text-sm text-gray-700 placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition"
                                    required autocomplete="username">
                            </div>
                            @if ($errors->get('email'))
                                <p class="text-xs text-red-500 mt-1">{{ $errors->first('email') }}</p>
                            @endif

                            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                                <div class="mt-2 p-3 bg-yellow-50 rounded-lg">
                                    <p class="text-xs text-yellow-700">
                                        Email Anda belum diverifikasi.
                                        <button form="send-verification"
                                            class="underline font-medium hover:text-yellow-900 transition-colors">
                                            Klik untuk kirim ulang verifikasi.
                                        </button>
                                    </p>
                                    @if (session('status') === 'verification-link-sent')
                                        <p class="text-xs text-green-600 mt-1 font-medium">Link verifikasi baru telah dikirim.</p>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <div class="flex items-center gap-3 pt-1">
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-600 text-white text-sm font-semibold px-5 py-2.5 rounded-lg transition-colors shadow-sm">
                                Save Changes
                            </button>
                            @if (session('status') === 'profile-updated')
                                <span class="text-sm text-green-500 font-medium">Tersimpan!</span>
                            @endif
                        </div>
                    </form>

                    <form id="send-verification" action="{{ route('verification.send') }}" method="post" class="hidden">
                        @csrf
                    </form>
                </div>

                {{-- Divider --}}
                <div class="border-t border-gray-100"></div>

                {{-- Update Password Section --}}
                <div>
                    <div class="mb-4">
                        <h2 class="text-lg font-bold text-gray-900">Update Password</h2>
                        <p class="text-sm text-gray-400">Ganti password secara berkala untuk keamanan akun Anda.</p>
                    </div>

                    <form method="post" action="{{ route('password.update') }}" class="space-y-4">
                        @csrf
                        @method('put')

                        {{-- Current Password --}}
                        <div class="space-y-1.5">
                            <label for="current_password" class="block text-sm font-semibold text-gray-700">
                                Password Sekarang
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </span>
                                <input type="password" id="current_password" name="current_password"
                                    placeholder="Masukkan Password"
                                    class="w-full pl-9 pr-10 py-2.5 border border-gray-200 rounded-lg text-sm text-gray-700 placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition"
                                    autocomplete="current-password">
                                <button type="button" onclick="togglePassword('current_password', this)"
                                    class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-600 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 eye-icon" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </div>
                            @if ($errors->updatePasswords->get('current_password'))
                                <p class="text-xs text-red-500 mt-1">{{ $errors->updatePasswords->first('current_password') }}</p>
                            @endif
                        </div>

                        {{-- New Password --}}
                        <div class="space-y-1.5">
                            <label for="password" class="block text-sm font-semibold text-gray-700">
                                Password Baru
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </span>
                                <input type="password" id="password" name="password"
                                    placeholder="Masukkan Password Baru"
                                    class="w-full pl-9 pr-10 py-2.5 border border-gray-200 rounded-lg text-sm text-gray-700 placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition"
                                    autocomplete="new-password">
                                <button type="button" onclick="togglePassword('password', this)"
                                    class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-600 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 eye-icon" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </div>
                            @if ($errors->updatePasswords->get('password'))
                                <p class="text-xs text-red-500 mt-1">{{ $errors->updatePasswords->first('password') }}</p>
                            @endif
                        </div>

                        {{-- Confirm Password --}}
                        <div class="space-y-1.5">
                            <label for="password_confirmation" class="block text-sm font-semibold text-gray-700">
                                Konfirmasi Password Baru
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </span>
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                    placeholder="Konfirmasi Password Baru"
                                    class="w-full pl-9 pr-10 py-2.5 border border-gray-200 rounded-lg text-sm text-gray-700 placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition"
                                    autocomplete="new-password">
                                <button type="button" onclick="togglePassword('password_confirmation', this)"
                                    class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-600 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 eye-icon" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </div>
                            @if ($errors->updatePasswords->get('password_confirmation'))
                                <p class="text-xs text-red-500 mt-1">{{ $errors->updatePasswords->first('password_confirmation') }}</p>
                            @endif
                        </div>

                        <div class="flex items-center gap-3 pt-1">
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-600 text-white text-sm font-semibold px-5 py-2.5 rounded-lg transition-colors shadow-sm">
                                Save Changes
                            </button>
                            @if (session('status') === 'password-updated')
                                <span class="text-sm text-green-500 font-medium">Password diperbarui!</span>
                            @endif
                        </div>
                    </form>
                </div>

                {{-- Delete Account --}}
                <div class="flex justify-end pt-2">
                    <button onclick="document.getElementById('delete-modal').classList.remove('hidden')"
                        class="inline-flex items-center gap-2 bg-gray-800 hover:bg-gray-900 text-white text-sm font-semibold px-4 py-2.5 rounded-lg transition-colors shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Hapus Akun
                    </button>
                </div>
            </div>

            {{-- Back Button --}}
            <div class="border-t border-gray-100 px-6 py-4">
                <a href="{{ url()->previous() }}"
                    class="block text-center text-sm text-gray-500 hover:text-gray-700 font-medium transition-colors">
                    Back
                </a>
            </div>
        </div>
    </div>

    {{-- Delete Account Modal --}}
    <div id="delete-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm px-4">
        <div class="bg-white rounded-2xl shadow-xl max-w-sm w-full p-6 space-y-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-500" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-gray-900">Hapus Akun</h3>
                    <p class="text-xs text-gray-400">Tindakan ini tidak bisa dibatalkan.</p>
                </div>
            </div>
            <p class="text-sm text-gray-600">
                Setelah akun dihapus, semua data akan dihapus permanen. Masukkan password Anda untuk konfirmasi.
            </p>
            <form method="post" action="{{ route('profile.destroy') }}" class="space-y-3">
                @csrf
                @method('delete')
                <div class="relative">
                    <input type="password" name="password" placeholder="Masukkan password Anda"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm text-gray-700 placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-red-300 focus:border-transparent transition">
                </div>
                @if ($errors->userDeletion->get('password'))
                    <p class="text-xs text-red-500">{{ $errors->userDeletion->first('password') }}</p>
                @endif
                <div class="flex gap-3 pt-1">
                    <button type="button"
                        onclick="document.getElementById('delete-modal').classList.add('hidden')"
                        class="flex-1 px-4 py-2.5 border border-gray-200 rounded-lg text-sm font-semibold text-gray-600 hover:bg-gray-50 transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                        class="flex-1 px-4 py-2.5 bg-red-500 hover:bg-red-600 rounded-lg text-sm font-semibold text-white transition-colors">
                        Hapus Akun
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Scripts --}}
    <script>
        // Toggle password visibility
        function togglePassword(inputId, btn) {
            const input = document.getElementById(inputId);
            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';

            const icon = btn.querySelector('.eye-icon');
            icon.innerHTML = isPassword
                ? `<path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />`
                : `<path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />`;
        }

        // Avatar preview
        document.getElementById('avatar-input').addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = function (ev) {
                document.getElementById('avatar-preview').src = ev.target.result;
            };
            reader.readAsDataURL(file);
            // Auto-submit avatar form
            document.getElementById('avatar-form').submit();
        });

        // Close modal on backdrop click
        document.getElementById('delete-modal').addEventListener('click', function (e) {
            if (e.target === this) this.classList.add('hidden');
        });
    </script>
</x-app-layout>