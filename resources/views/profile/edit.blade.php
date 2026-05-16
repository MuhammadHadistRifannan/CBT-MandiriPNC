<x-app-layout>
    <div x-data="{ expanded: true, mobileOpen: false }"
    class="flex h-screen overflow-hidden bg-slate-50">

    {{-- SIDEBAR --}}
    @include('layouts.dashboard.sidebar')

    {{-- MAIN CONTENT --}}
    <div class="flex-1 flex flex-col overflow-hidden">

        {{-- HEADER --}}
        @include('layouts.dashboard.header', [
            'title' => 'Profile Peserta'
        ])
    <main class="flex-1 overflow-y-auto bg-slate-50 p-4 lg:p-8">

    <div class="max-w-5xl mx-auto space-y-6">

        {{-- HEADER --}}
        <div
            class="bg-white border border-slate-200 rounded-3xl p-5 lg:p-8 shadow-sm">

            <div
                class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">

                {{-- PROFILE --}}
                <div class="flex items-center gap-5">

                    <div class="relative shrink-0 m-4">

                        <img src="{{ auth()->user()->foto ? asset('storage/'.auth()->user()->foto) : asset('assets/images/photo.png') }}"
                            class="w-24 h-24 lg:w-28 lg:h-28 rounded-3xl object-cover border-4 border-slate-100 shadow-sm">

                            
        </div>

                {{-- INFO --}}
                <div
                    class="grid grid-cols-2 gap-3 lg:min-w-[260px]">

                    <div
                        class="bg-slate-50 border border-slate-200 rounded-2xl p-4">

                        <p class="text-xs text-slate-500">
                            ID Peserta
                        </p>

                        <h3
                            class="text-sm font-bold text-slate-800 mt-1">
                            CBT2026
                        </h3>
                    </div>

                    <div
                        class="bg-slate-50 border border-slate-200 rounded-2xl p-4">

                        <p class="text-xs text-slate-500">
                            Status
                        </p>

                        <h3
                            class="text-sm font-bold text-emerald-600 mt-1">
                            Verified
                        </h3>
                    </div>
                </div>

            </div>
        </div>

        {{-- FORM --}}
{{-- PROFILE UPDATE --}}
<form method="post"
    action="{{ route('profile.update') }}"
    class="bg-white border border-slate-200 rounded-3xl shadow-sm overflow-hidden">
    
    @csrf
    @method('patch')

    {{-- FORM HEADER --}}
    <div class="px-5 lg:px-8 py-5 border-b border-slate-200">

        <h2 class="text-xl font-bold text-slate-800">
            Informasi Akun
        </h2>

        <p class="text-sm text-slate-500 mt-1">
            Pastikan informasi akun sudah benar dan aktif.
        </p>
    </div>

    {{-- FORM BODY --}}
    <div class="p-5 lg:p-8 space-y-8">

        {{-- SUCCESS --}}
        @if (session('status') === 'profile-updated')
            <div
                class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-4 rounded-2xl text-sm">

                Profile berhasil diperbarui.
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- NAMA --}}
            <div class="space-y-2">

                <label
                    class="text-sm font-semibold text-slate-700">
                    Nama Lengkap
                </label>

                <input type="text"
                    name="name"
                    value="{{ old('name', $user->name) }}"
                    required
                    autofocus
                    autocomplete="name"
                    class="w-full rounded-2xl border border-slate-300 px-5 py-4 text-sm focus:ring-2 focus:ring-[#0F4C81] focus:border-[#0F4C81]">

                @error('name')
                    <p class="text-sm text-red-500">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- EMAIL --}}
            <div class="space-y-2">

                <label
                    class="text-sm font-semibold text-slate-700">
                    Email
                </label>

                <input type="email"
                    name="email"
                    value="{{ old('email', $user->email) }}"
                    required
                    autocomplete="username"
                    class="w-full rounded-2xl border border-slate-300 px-5 py-4 text-sm focus:ring-2 focus:ring-[#0F4C81] focus:border-[#0F4C81]">

                @error('email')
                    <p class="text-sm text-red-500">
                        {{ $message }}
                    </p>
                @enderror
            </div>

        </div>

        {{-- VERIFY EMAIL --}}
        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())

            <div
                class="bg-amber-50 border border-amber-200 rounded-2xl p-5">

                <p class="text-sm text-amber-700">
                    Email Anda belum diverifikasi.
                </p>

                <button form="send-verification"
                    class="mt-3 text-sm font-semibold text-[#0F4C81] hover:underline">
                    Kirim ulang email verifikasi
                </button>

                @if (session('status') === 'verification-link-sent')
                    <p class="mt-2 text-sm text-emerald-600">
                        Link verifikasi berhasil dikirim.
                    </p>
                @endif
            </div>

        @endif

        {{-- BUTTON --}}
        <div
            class="flex flex-col sm:flex-row gap-3 sm:justify-end">

            <button type="reset"
                class="px-6 py-4 rounded-2xl border border-slate-300 hover:bg-slate-100 text-slate-700 font-medium transition">

                Reset
            </button>

            <button type="submit"
                class="px-8 py-4 rounded-2xl bg-[#0F4C81] hover:bg-[#0B3B63] text-white font-semibold transition shadow-sm">

                Simpan Perubahan
            </button>
        </div>

    </div>
</form>

{{-- VERIFY EMAIL FORM --}}
<form id="send-verification"
    method="post"
    action="{{ route('verification.send') }}">
    @csrf
</form>

    </div>

</main>
</x-app-layout>