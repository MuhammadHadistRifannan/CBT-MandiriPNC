<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'CBT Mandiri PNC') }} - Broadcast Pesan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#F5F7FA] text-slate-800 antialiased">
    <div x-data="{ sidebarOpen: false }" class="flex h-screen overflow-hidden">
        @include('layouts.pengawas.sidebar')

        <div class="flex min-w-0 flex-1 flex-col overflow-hidden">
            <header class="flex items-center gap-3 border-b border-slate-200 bg-white px-4 py-4 lg:px-8">
                <button type="button" @click="sidebarOpen = true" aria-label="Buka navigasi"
                    class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#0F4C81] text-white lg:hidden">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[#0F4C81]">Komunikasi Ujian</p>
                    <h1 class="text-xl font-black text-slate-800 sm:text-2xl">Broadcast Pesan</h1>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto bg-[#F8FAFC] p-4 lg:p-8">
                <div class="mx-auto max-w-6xl space-y-6">
                    <section class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                        <div>
                            <h2 class="text-2xl font-black text-slate-800">Kirim Pengumuman Cepat</h2>
                            <p class="mt-2 text-sm text-slate-500">Pesan diterima peserta yang sedang ujian atau idle saat ini.</p>
                        </div>
                        <div class="rounded-2xl border border-blue-100 bg-blue-50 px-5 py-3 text-[#0F4C81]">
                            <p class="text-xs font-bold uppercase tracking-wide">Peserta Aktif</p>
                            <p class="mt-1 text-2xl font-black">{{ number_format($activeParticipants, 0, ',', '.') }}</p>
                        </div>
                    </section>

                    @if (session('success'))
                        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-700">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="grid grid-cols-1 gap-6 lg:grid-cols-5">
                        <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm lg:col-span-2">
                            <h3 class="text-lg font-black text-slate-800">Pesan Baru</h3>
                            <p class="mt-1 text-sm text-slate-500">Buat instruksi singkat dan jelas untuk peserta.</p>
                            <form action="{{ route('pengawas.broadcast.store') }}" method="POST" class="mt-6 space-y-4">
                                @csrf
                                <label class="block">
                                    <span class="mb-2 block text-xs font-bold uppercase tracking-wide text-slate-500">Isi Pesan</span>
                                    <textarea name="message" rows="7" maxlength="500" required
                                        placeholder="Contoh: Harap tetap di halaman ujian. Kendala jaringan sedang ditangani."
                                        class="w-full resize-none rounded-2xl border-slate-200 px-4 py-3 text-sm focus:border-[#0F4C81] focus:ring-[#0F4C81]">{{ old('message') }}</textarea>
                                </label>
                                @error('message')
                                    <p class="text-sm font-semibold text-red-600">{{ $message }}</p>
                                @enderror
                                <button type="submit"
                                    class="w-full rounded-2xl bg-[#0F4C81] px-5 py-4 text-sm font-bold text-white transition hover:bg-[#0B3A63]">
                                    Kirim Ke Peserta Aktif
                                </button>
                            </form>
                        </section>

                        <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm lg:col-span-3">
                            <div class="border-b border-slate-100 px-6 py-5">
                                <h3 class="text-lg font-black text-slate-800">Riwayat Pesan</h3>
                                <p class="mt-1 text-sm text-slate-500">Pesan broadcast terakhir yang telah dikirim.</p>
                            </div>
                            <div class="divide-y divide-slate-100">
                                @forelse ($history as $broadcast)
                                    <article class="space-y-3 px-6 py-5">
                                        <div class="flex flex-wrap items-center justify-between gap-2 text-xs text-slate-500">
                                            <span class="font-semibold text-[#0F4C81]">{{ $broadcast->pengawas?->name ?? 'Pengawas' }}</span>
                                            <span>{{ $broadcast->created_at->format('d M Y, H:i') }} WIB</span>
                                        </div>
                                        <p class="text-sm leading-relaxed text-slate-700">{{ $broadcast->message }}</p>
                                        <div class="flex gap-3 text-xs font-semibold">
                                            <span class="rounded-full bg-blue-50 px-3 py-1 text-[#0F4C81]">{{ $broadcast->recipients_count }} penerima</span>
                                            <span class="rounded-full bg-slate-100 px-3 py-1 text-slate-600">{{ $broadcast->dismissed_count }} ditutup</span>
                                        </div>
                                    </article>
                                @empty
                                    <div class="px-6 py-16 text-center text-sm text-slate-500">Belum ada pesan broadcast.</div>
                                @endforelse
                            </div>
                        </section>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>

</html>
