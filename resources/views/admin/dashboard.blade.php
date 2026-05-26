<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'CBT Mandiri PNC') }} - Dashboard Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#F5F7FA] text-slate-800 antialiased">
    <div x-data="{ sidebarOpen: false }" class="flex h-screen overflow-hidden">
        @include('layouts.admin.sidebar')

        <div class="flex-1 flex flex-col overflow-hidden">
            @include('layouts.admin.mobile-header', ['title' => 'Dashboard Admin'])

            <main class="flex-1 overflow-y-auto bg-[#F8FAFC] p-4 lg:p-8">
                <div class="mb-8 flex flex-col gap-5 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h1 class="text-2xl font-black text-slate-800 lg:text-3xl">Dashboard Admin</h1>
                        <p class="mt-1 text-slate-500">Monitoring pendaftaran, pembayaran, dan dokumen PMB.</p>
                    </div>

                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('admin.dokumen', ['status' => \App\Enums\DokumenStatus::Pending->value]) }}"
                            class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-6 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
                            Review Dokumen
                        </a>
                        <a href="{{ route('admin.prodi') }}"
                            class="inline-flex items-center justify-center rounded-2xl bg-[#0F4C81] px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-900/10 transition hover:bg-[#0B3A63]">
                            Kelola Program Studi
                        </a>
                    </div>
                </div>

                <section class="mb-8 grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">
                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-sm font-medium text-slate-500">Total Peserta</p>
                                <h2 class="mt-2 text-3xl font-black text-slate-800">{{ number_format($stats['participants']) }}</h2>
                                <p class="mt-4 text-sm text-slate-500">Peserta dengan pembayaran berhasil</p>
                            </div>
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-50 text-[#0F4C81]">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5V4H2v16h5m10 0v-2a4 4 0 00-8 0v2m8 0H9m4-10a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-sm font-medium text-slate-500">Total Balance</p>
                                <h2 class="mt-2 text-3xl font-black text-slate-800">
                                    Rp{{ number_format($stats['balance'], 0, ',', '.') }}
                                </h2>
                                <p class="mt-4 text-sm text-slate-500">Transaksi settlement Midtrans</p>
                            </div>
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 1.12-3 2.5S10.343 13 12 13s3 1.12 3 2.5S13.657 18 12 18m0-10v10" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-sm font-medium text-slate-500">Dokumen Verified</p>
                                <h2 class="mt-2 text-3xl font-black text-slate-800">{{ number_format($stats['verified_documents']) }}</h2>
                                <p class="mt-4 text-sm text-slate-500">Dokumen telah diverifikasi admin</p>
                            </div>
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-50 text-amber-500">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6M7 3h7l5 5v13a1 1 0 01-1 1H7a1 1 0 01-1-1V4a1 1 0 011-1z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <p class="text-sm font-medium text-slate-500">Prodi Favorit</p>
                                <h2 class="mt-2 truncate text-xl font-black text-slate-800">
                                    {{ $stats['favorite_prodi']['name'] ?? '-' }}
                                </h2>
                                <p class="mt-4 text-sm text-slate-500">
                                    {{ isset($stats['favorite_prodi']) ? number_format($stats['favorite_prodi']['selections']) . ' pilihan utama' : 'Belum ada pilihan' }}
                                </p>
                            </div>
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-red-50 text-red-500">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 3c2 2 4 5 4 8a4 4 0 11-8 0c0-2 1-4 4-8z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </section>

                <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
                    <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm xl:col-span-2">
                        <div class="border-b border-slate-100 px-6 py-5">
                            <h2 class="text-lg font-black text-slate-800">Peserta Terbaru</h2>
                            <p class="mt-1 text-sm text-slate-500">Lima peserta terbaru yang telah terdaftar.</p>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full min-w-[700px]">
                                <thead class="bg-slate-50">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Peserta</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Pilihan Utama</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Pembayaran</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Dokumen</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @forelse ($recentParticipants as $participant)
                                        @php
                                            $participantUser = $participant->user;
                                            $billingStatus = $participantUser?->billing?->transaction_status;
                                            $documentStatus = $participantUser?->dokumen?->status;
                                        @endphp
                                        <tr class="transition hover:bg-slate-50">
                                            <td class="px-6 py-4">
                                                <p class="font-semibold text-slate-800">{{ $participantUser?->name ?? '-' }}</p>
                                                <p class="mt-1 text-sm text-slate-500">{{ $participant->nomor_peserta }}</p>
                                            </td>
                                            <td class="px-6 py-4 text-sm font-medium text-slate-700">
                                                {{ $participantUser?->pilihan?->pilihan_prodi_1?->nama_prodi ?? 'Belum memilih' }}
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="rounded-xl px-3 py-1 text-xs font-bold {{ $billingStatus?->badgeClass() ?? 'bg-slate-100 text-slate-600' }}">
                                                    {{ $billingStatus?->label() ?? 'Belum ditagih' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="rounded-xl px-3 py-1 text-xs font-bold {{ $documentStatus?->badgeClass() ?? 'bg-slate-100 text-slate-600' }}">
                                                    {{ $documentStatus?->label() ?? 'Belum lengkap' }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-14 text-center text-sm text-slate-500">
                                                Belum ada peserta yang tercatat.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="border-t border-slate-100 px-6 py-5 text-sm text-slate-500">
                            Menampilkan {{ $recentParticipants->count() }} peserta terbaru
                        </div>
                    </section>

                    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h2 class="text-lg font-black text-slate-800">Prodi Terfavorit</h2>
                        <p class="mb-6 mt-1 text-sm text-slate-500">Berdasarkan pilihan utama peserta.</p>

                        <div class="space-y-5">
                            @forelse ($favoriteProdis as $prodi)
                                <div>
                                    <div class="mb-2 flex items-center justify-between gap-3">
                                        <p class="truncate text-sm font-semibold text-slate-700">{{ $prodi['name'] }}</p>
                                        <p class="shrink-0 text-sm font-bold text-slate-500">
                                            {{ $prodi['percentage'] }}%
                                        </p>
                                    </div>
                                    <div class="h-3 w-full overflow-hidden rounded-full bg-slate-100">
                                        <div class="h-full rounded-full bg-[#0F4C81]" style="width: {{ $prodi['percentage'] }}%"></div>
                                    </div>
                                    <p class="mt-2 text-xs text-slate-500">{{ number_format($prodi['selections']) }} peserta memilih prodi ini</p>
                                </div>
                            @empty
                                <div class="rounded-2xl bg-slate-50 px-4 py-8 text-center text-sm text-slate-500">
                                    Belum ada pilihan program studi.
                                </div>
                            @endforelse
                        </div>
                    </section>
                </div>
            </main>
        </div>
    </div>
</body>

</html>
