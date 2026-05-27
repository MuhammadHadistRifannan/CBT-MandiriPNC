<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'CBT Mandiri PNC') }} - Dashboard Pengawas</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#F5F7FA] text-slate-800 antialiased">
    <div x-data="{
        sidebarOpen: false,
        loading: false,
        stats: @js($stats),
        monitoring: @js($monitoring),
        alerts: @js($alerts),
        updatedAt: @js($updatedAt),
        init() {
            this.poller = setInterval(() => this.refresh(), 5000);
        },
        async refresh() {
            this.loading = true;
            try {
                const response = await fetch(@js(route('pengawas.dashboard.data')), {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                });
                if (!response.ok) return;
                const data = await response.json();
                this.stats = data.stats;
                this.monitoring = data.monitoring;
                this.alerts = data.alerts;
                this.updatedAt = data.updatedAt;
            } finally {
                this.loading = false;
            }
        },
        alertClass(tone) {
            return {
                danger: 'border-red-200 bg-red-50 text-red-700',
                warning: 'border-amber-200 bg-amber-50 text-amber-700',
                success: 'border-emerald-200 bg-emerald-50 text-emerald-700',
                neutral: 'border-slate-200 bg-slate-50 text-slate-600'
            }[tone];
        }
    }" class="flex h-screen overflow-hidden">
        @include('layouts.pengawas.sidebar')

        <div class="flex min-w-0 flex-1 flex-col overflow-hidden">
            <header class="flex items-center justify-between border-b border-slate-200 bg-white px-4 py-4 lg:px-8">
                <div class="flex items-center gap-3">
                    <button type="button" @click="sidebarOpen = true" :aria-expanded="sidebarOpen.toString()"
                        aria-label="Buka navigasi"
                        class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#0F4C81] text-white lg:hidden">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[#0F4C81]">Panel Pengawas</p>
                        <h1 class="text-xl font-black text-slate-800 sm:text-2xl">Dashboard Ujian</h1>
                    </div>
                </div>
                <div class="hidden items-center gap-3 sm:flex">
                    <span class="relative flex h-3 w-3">
                        <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex h-3 w-3 rounded-full bg-emerald-500"></span>
                    </span>
                    <div class="text-right text-xs text-slate-500">
                        <p class="font-semibold text-slate-700">Live Monitoring</p>
                        <p>Diperbarui <span x-text="updatedAt"></span> WIB</p>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto bg-[#F8FAFC] p-4 lg:p-8">
                <div class="mx-auto max-w-7xl space-y-7">
                    <section class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                        <div>
                            <h2 class="text-2xl font-black text-slate-800 lg:text-3xl">Ringkasan Kondisi Ujian</h2>
                            <p class="mt-2 text-sm text-slate-500">Pantau status peserta dan tangani peringatan secepatnya.</p>
                        </div>
                        <button type="button" @click="refresh()" :disabled="loading"
                            class="inline-flex items-center justify-center gap-2 rounded-2xl bg-[#0F4C81] px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-900/10 transition hover:bg-[#0B3A63] disabled:opacity-60">
                            <svg :class="{ 'animate-spin': loading }" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v6h6M20 20v-6h-6M20 9A8 8 0 006.2 5.5L4 10m16 4-2.2 4.5A8 8 0 014 15" />
                            </svg>
                            <span x-text="loading ? 'Memperbarui...' : 'Refresh Data'"></span>
                        </button>
                    </section>

                    <section class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
                        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="text-sm font-medium text-slate-500">Total Peserta</p>
                                    <p class="mt-3 text-3xl font-black text-slate-800" x-text="stats.total.toLocaleString('id-ID')"></p>
                                </div>
                                <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-50 text-[#0F4C81]">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 20h5V4H2v16h5m10 0v-2a4 4 0 00-8 0v2m8 0H9m4-10a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                </span>
                            </div>
                            <p class="mt-5 text-xs text-slate-500">Sesi peserta terdaftar</p>
                        </div>
                        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="text-sm font-medium text-slate-500">Sedang Ujian</p>
                                    <p class="mt-3 text-3xl font-black text-slate-800" x-text="stats.active.toLocaleString('id-ID')"></p>
                                </div>
                                <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-5.197-3.03A1 1 0 008 9v6a1 1 0 001.555.832l5.197-3.03a1 1 0 000-1.664z" />
                                    </svg>
                                </span>
                            </div>
                            <p class="mt-5 text-xs text-emerald-600">Aktif berjalan sekarang</p>
                        </div>
                        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="text-sm font-medium text-slate-500">Selesai</p>
                                    <p class="mt-3 text-3xl font-black text-slate-800" x-text="stats.completed.toLocaleString('id-ID')"></p>
                                </div>
                                <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-sky-50 text-sky-600">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                    </svg>
                                </span>
                            </div>
                            <p class="mt-5 text-xs text-slate-500">Sudah submit jawaban</p>
                        </div>
                        <div class="rounded-3xl border border-red-100 bg-white p-6 shadow-sm">
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="text-sm font-medium text-slate-500">Bermasalah</p>
                                    <p class="mt-3 text-3xl font-black text-slate-800" x-text="stats.issues.toLocaleString('id-ID')"></p>
                                </div>
                                <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-red-50 text-red-600">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v4m0 4h.01M10.3 4.4L2.6 18a2 2 0 001.7 3h15.4a2 2 0 001.7-3L13.7 4.4a2 2 0 00-3.4 0z" />
                                    </svg>
                                </span>
                            </div>
                            <p class="mt-5 text-xs text-red-600">Idle atau blocked</p>
                        </div>
                    </section>

                    <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
                        <section class="min-w-0 overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm xl:col-span-2">
                            <div class="flex items-center justify-between border-b border-slate-100 px-5 py-5 sm:px-6">
                                <div>
                                    <h3 class="text-lg font-black text-slate-800">Quick Monitoring</h3>
                                    <p class="mt-1 text-sm text-slate-500">Status peserta terbaru, otomatis refresh setiap 5 detik.</p>
                                </div>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full min-w-[720px]">
                                    <thead class="bg-slate-50">
                                        <tr>
                                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Peserta</th>
                                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Status</th>
                                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Mulai</th>
                                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Sisa Waktu</th>
                                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Progress</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        <template x-for="exam in monitoring" :key="exam.id">
                                            <tr class="transition hover:bg-slate-50">
                                                <td class="px-6 py-4">
                                                    <p class="font-semibold text-slate-800" x-text="exam.participant"></p>
                                                    <p class="mt-1 text-xs text-slate-500" x-text="exam.number"></p>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <span class="rounded-xl px-3 py-1 text-xs font-bold" :class="exam.status_class" x-text="exam.status_label"></span>
                                                </td>
                                                <td class="px-6 py-4 text-sm font-medium text-slate-600" x-text="exam.started_at"></td>
                                                <td class="px-6 py-4 text-sm font-semibold text-slate-700" x-text="exam.remaining"></td>
                                                <td class="px-6 py-4">
                                                    <div class="mb-2 flex items-center justify-between gap-2 text-xs font-semibold text-slate-500">
                                                        <span x-text="`${exam.progress}%`"></span>
                                                    </div>
                                                    <div class="h-2 w-28 overflow-hidden rounded-full bg-slate-100">
                                                        <div class="h-full rounded-full bg-[#0F4C81]" :style="`width: ${exam.progress}%`"></div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </template>
                                        <template x-if="monitoring.length === 0">
                                            <tr>
                                                <td colspan="5" class="px-6 py-14 text-center text-sm text-slate-500">
                                                    Belum ada aktivitas ujian untuk dipantau.
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                        </section>

                        <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                            <div class="mb-5 flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-black text-slate-800">Alert Singkat</h3>
                                    <p class="mt-1 text-sm text-slate-500">Prioritas perhatian pengawas.</p>
                                </div>
                                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-500" x-text="alerts.length"></span>
                            </div>
                            <div class="space-y-3">
                                <template x-for="(alert, index) in alerts" :key="index">
                                    <div class="rounded-2xl border p-4" :class="alertClass(alert.tone)">
                                        <p class="text-sm font-bold" x-text="alert.title"></p>
                                        <p class="mt-2 text-xs leading-relaxed" x-text="alert.message"></p>
                                    </div>
                                </template>
                            </div>
                            <div class="mt-6 rounded-2xl bg-[#0F4C81]/5 p-4">
                                <p class="text-xs font-semibold uppercase tracking-wide text-[#0F4C81]">Mode Live</p>
                                <p class="mt-2 text-sm text-slate-600">Data monitoring diperbarui otomatis tanpa reload halaman.</p>
                            </div>
                        </section>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>

</html>
