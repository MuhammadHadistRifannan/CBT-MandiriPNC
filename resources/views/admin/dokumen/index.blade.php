<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'CBT Mandiri PNC') }} - Verifikasi Dokumen</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#F5F7FA] text-slate-800 antialiased">
    <div x-data="{ sidebarOpen: false }" class="flex h-screen overflow-hidden">
        @include('layouts.admin.sidebar')

        <div class="flex flex-1 flex-col overflow-hidden">
            @include('layouts.admin.mobile-header', ['title' => 'Verifikasi Dokumen'])

            <main class="flex-1 overflow-y-auto bg-[#F8FAFC] p-4 lg:p-8">
                <div class="mb-8">
                    <h1 class="text-2xl font-black text-slate-800 lg:text-3xl">Verifikasi Dokumen</h1>
                    <p class="mt-1 text-slate-500">Kelola antrean berkas peserta secara cepat dan terukur.</p>
                </div>

                @if (session('success'))
                    <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-700">
                        {{ session('success') }}
                    </div>
                @endif

                <section class="mb-8 grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">
                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                        <p class="text-sm font-medium text-slate-500">Total Upload</p>
                        <p class="mt-2 text-3xl font-black text-slate-800">{{ number_format($stats['total']) }}</p>
                    </div>
                    <div class="rounded-3xl border border-amber-200 bg-white p-6 shadow-sm">
                        <p class="text-sm font-medium text-slate-500">Menunggu Review</p>
                        <p class="mt-2 text-3xl font-black text-amber-600">{{ number_format($stats['pending']) }}</p>
                    </div>
                    <div class="rounded-3xl border border-emerald-200 bg-white p-6 shadow-sm">
                        <p class="text-sm font-medium text-slate-500">Verified</p>
                        <p class="mt-2 text-3xl font-black text-emerald-600">{{ number_format($stats['verified']) }}</p>
                    </div>
                    <div class="rounded-3xl border border-red-200 bg-white p-6 shadow-sm">
                        <p class="text-sm font-medium text-slate-500">Rejected</p>
                        <p class="mt-2 text-3xl font-black text-red-600">{{ number_format($stats['rejected']) }}</p>
                    </div>
                </section>

                <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-100 p-6">
                        <div class="mb-5">
                            <h2 class="text-xl font-black text-slate-800">Antrean Pemeriksaan</h2>
                            <p class="mt-1 text-sm text-slate-500">Prioritaskan status pending, lalu buka detail untuk memeriksa file.</p>
                        </div>

                        <form method="GET" action="{{ route('admin.dokumen') }}"
                            class="grid grid-cols-1 gap-3 md:grid-cols-[minmax(280px,1fr)_190px_auto_auto]">
                            <div class="relative">
                                <input type="search" name="search" value="{{ request('search') }}"
                                    placeholder="Cari nama, email, atau nomor peserta..."
                                    class="w-full rounded-2xl border border-slate-200 bg-white py-3 pl-12 pr-4 text-sm focus:border-[#0F4C81] focus:outline-none focus:ring-2 focus:ring-[#0F4C81]/20">
                                <svg class="absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-4.35-4.35M11 18a7 7 0 100-14 7 7 0 000 14z" />
                                </svg>
                            </div>

                            <select name="status"
                                class="rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#0F4C81]/20">
                                <option value="">Semua Status</option>
                                @foreach (\App\Enums\DokumenStatus::cases() as $status)
                                    <option value="{{ $status->value }}" @selected(request('status') === $status->value)>
                                        {{ $status->label() }}
                                    </option>
                                @endforeach
                            </select>

                            <button type="submit"
                                class="rounded-2xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-700">
                                Filter
                            </button>

                            <a href="{{ route('admin.dokumen', ['status' => \App\Enums\DokumenStatus::Pending->value]) }}"
                                class="inline-flex items-center justify-center rounded-2xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                                Pending
                            </a>
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[960px]">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Peserta</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">File Tersedia</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Upload</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Status</th>
                                    <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider text-slate-500">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse ($dokumens as $dokumen)
                                    <tr class="transition hover:bg-slate-50">
                                        <td class="px-6 py-5">
                                            <p class="font-bold text-slate-800">{{ $dokumen->user->name }}</p>
                                            <p class="mt-1 text-sm text-slate-500">
                                                {{ $dokumen->user->peserta?->nomor_peserta ?? $dokumen->user->email }}
                                            </p>
                                        </td>
                                        <td class="px-6 py-5 text-sm font-semibold text-slate-700">{{ count($dokumen->availableFiles()) }} berkas</td>
                                        <td class="px-6 py-5 text-sm text-slate-600">{{ $dokumen->created_at->format('d M Y, H:i') }}</td>
                                        <td class="px-6 py-5">
                                            <span class="rounded-xl px-3 py-1 text-xs font-bold {{ $dokumen->status->badgeClass() }}">
                                                {{ $dokumen->status->label() }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-5 text-right">
                                            <a href="{{ route('admin.dokumen.show', $dokumen) }}"
                                                class="inline-flex items-center rounded-xl bg-[#0F4C81] px-4 py-2 text-sm font-semibold text-white transition hover:bg-[#0B3A63]">
                                                Review
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-14 text-center text-sm text-slate-500">
                                            Tidak ada dokumen sesuai filter.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="flex flex-col gap-4 border-t border-slate-100 px-6 py-5 md:flex-row md:items-center md:justify-between">
                        <p class="text-sm text-slate-500">
                            Menampilkan {{ $dokumens->firstItem() ?? 0 }} - {{ $dokumens->lastItem() ?? 0 }} dari {{ $dokumens->total() }} dokumen
                        </p>
                        {{ $dokumens->links() }}
                    </div>
                </section>
            </main>
        </div>
    </div>
</body>

</html>
