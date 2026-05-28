<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'CBT Mandiri PNC') }} - Aktivitas Peserta</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#F5F7FA] text-slate-800 antialiased">
    <div x-data="{
        sidebarOpen: false,
        loading: false,
        userId: @js((string) ($filters['user_id'] ?? '')),
        eventType: @js($filters['event_type'] ?? ''),
        page: @js((int) ($filters['page'] ?? 1)),
        perPage: @js((int) ($filters['per_page'] ?? 10)),
        stats: @js($stats),
        logs: @js($logs),
        pagination: @js($pagination),
        init() {
            this.poller = setInterval(() => this.refresh(), 5000);
        },
        async refresh(page = this.page) {
            this.loading = true;
            try {
                const url = new URL(@js(route('pengawas.activities.data')), window.location.origin);
                if (this.userId) url.searchParams.set('user_id', this.userId);
                if (this.eventType) url.searchParams.set('event_type', this.eventType);
                url.searchParams.set('page', page);
                url.searchParams.set('per_page', this.perPage);
                const response = await fetch(url, {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                });
                if (!response.ok) return;
                const data = await response.json();
                this.stats = data.stats;
                this.logs = data.logs;
                this.pagination = data.pagination;
                this.page = data.pagination.current_page;
            } finally {
                this.loading = false;
            }
        },
        resetAndRefresh() {
            this.page = 1;
            this.refresh(1);
        },
        nextPage() {
            if (this.pagination.current_page >= this.pagination.last_page) return;
            this.refresh(this.pagination.current_page + 1);
        },
        previousPage() {
            if (this.pagination.current_page <= 1) return;
            this.refresh(this.pagination.current_page - 1);
        }
    }" class="flex h-screen overflow-hidden">
        @include('layouts.pengawas.sidebar')

        <div class="flex min-w-0 flex-1 flex-col overflow-hidden">
            <header class="flex items-center justify-between gap-4 border-b border-slate-200 bg-white px-4 py-4 lg:px-8">
                <div class="flex items-center gap-3">
                    <button type="button" @click="sidebarOpen = true" aria-label="Buka navigasi"
                        class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#0F4C81] text-white lg:hidden">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[#0F4C81]">Pengawasan Ujian</p>
                        <h1 class="text-xl font-black text-slate-800 sm:text-2xl">Aktivitas Peserta</h1>
                    </div>
                </div>
                <span class="hidden items-center gap-2 text-xs font-semibold text-slate-500 sm:flex">
                    <span class="h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
                    Live setiap 5 detik
                </span>
            </header>

            <main class="flex-1 overflow-y-auto bg-[#F8FAFC] p-4 lg:p-8">
                <div class="mx-auto max-w-7xl space-y-6">
                    <section>
                        <h2 class="text-2xl font-black text-slate-800">Log Perilaku Selama Ujian</h2>
                        <p class="mt-2 text-sm text-slate-500">Pantau perpindahan tab, kondisi idle, fokus jendela, dan refresh halaman peserta.</p>
                    </section>

                    <section class="grid grid-cols-2 gap-4 lg:grid-cols-4">
                        <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                            <p class="text-sm text-slate-500">Total Event</p>
                            <p class="mt-2 text-3xl font-black text-slate-800" x-text="stats.total"></p>
                        </div>
                        <div class="rounded-3xl border border-red-100 bg-white p-5 shadow-sm">
                            <p class="text-sm text-slate-500">Pindah Tab</p>
                            <p class="mt-2 text-3xl font-black text-red-600" x-text="stats.tab_switches"></p>
                        </div>
                        <div class="rounded-3xl border border-amber-100 bg-white p-5 shadow-sm">
                            <p class="text-sm text-slate-500">Idle</p>
                            <p class="mt-2 text-3xl font-black text-amber-600" x-text="stats.idle"></p>
                        </div>
                        <div class="rounded-3xl border border-orange-100 bg-white p-5 shadow-sm">
                            <p class="text-sm text-slate-500">Refresh</p>
                            <p class="mt-2 text-3xl font-black text-orange-600" x-text="stats.refreshes"></p>
                        </div>
                    </section>

                    <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-[1fr_1fr_auto]">
                            <label>
                                <span class="mb-2 block text-xs font-bold uppercase tracking-wide text-slate-500">Peserta</span>
                                <select x-model="userId" @change="resetAndRefresh()"
                                    class="w-full rounded-2xl border-slate-200 px-4 py-3 text-sm focus:border-[#0F4C81] focus:ring-[#0F4C81]">
                                    <option value="">Semua Peserta</option>
                                    @foreach ($participants as $participant)
                                        <option value="{{ $participant['id'] }}">{{ $participant['name'] }} - {{ $participant['number'] }}</option>
                                    @endforeach
                                </select>
                            </label>
                            <label>
                                <span class="mb-2 block text-xs font-bold uppercase tracking-wide text-slate-500">Jenis Event</span>
                                <select x-model="eventType" @change="resetAndRefresh()"
                                    class="w-full rounded-2xl border-slate-200 px-4 py-3 text-sm focus:border-[#0F4C81] focus:ring-[#0F4C81]">
                                    <option value="">Semua Event</option>
                                    @foreach ($eventTypes as $event)
                                        <option value="{{ $event['value'] }}">{{ $event['label'] }}</option>
                                    @endforeach
                                </select>
                            </label>
                            <button type="button" @click="refresh()" :disabled="loading"
                                class="self-end rounded-2xl bg-[#0F4C81] px-6 py-3 text-sm font-bold text-white disabled:opacity-60">
                                <span x-text="loading ? 'Memuat...' : 'Refresh'"></span>
                            </button>
                        </div>
                    </section>

                    <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                        <div class="border-b border-slate-100 px-6 py-5">
                            <h3 class="text-lg font-black text-slate-800">Aktivitas Terbaru</h3>
                            <p class="mt-1 text-sm text-slate-500">
                                Menampilkan <span x-text="pagination.from ?? 0"></span>-<span x-text="pagination.to ?? 0"></span>
                                dari <span x-text="pagination.total"></span> event sesuai filter.
                            </p>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full min-w-[720px]">
                                <thead class="bg-slate-50 text-left text-xs font-bold uppercase tracking-wide text-slate-500">
                                    <tr>
                                        <th class="px-6 py-4">Waktu</th>
                                        <th class="px-6 py-4">Peserta</th>
                                        <th class="px-6 py-4">Event</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    <template x-for="log in logs" :key="log.id">
                                        <tr class="hover:bg-slate-50">
                                            <td class="px-6 py-4 text-sm text-slate-600" x-text="log.occurred_at"></td>
                                            <td class="px-6 py-4">
                                                <p class="text-sm font-semibold text-slate-800" x-text="log.participant"></p>
                                                <p class="text-xs text-slate-500" x-text="log.number"></p>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="rounded-full px-3 py-1 text-xs font-bold" :class="log.event_class" x-text="log.event_label"></span>
                                            </td>
                                        </tr>
                                    </template>
                                    <template x-if="logs.length === 0">
                                        <tr>
                                            <td colspan="3" class="px-6 py-12 text-center text-sm text-slate-500">Belum ada aktivitas peserta yang tercatat.</td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                        <div class="flex flex-col gap-3 border-t border-slate-100 px-6 py-4 sm:flex-row sm:items-center sm:justify-between">
                            <p class="text-sm font-semibold text-slate-500">
                                Halaman <span x-text="pagination.current_page"></span> dari <span x-text="pagination.last_page"></span>
                            </p>
                            <div class="flex items-center gap-2">
                                <label class="mr-2 hidden items-center gap-2 text-sm font-semibold text-slate-500 sm:flex">
                                    Per halaman
                                    <select x-model.number="perPage" @change="resetAndRefresh()"
                                        class="rounded-xl border-slate-200 py-2 text-sm focus:border-[#0F4C81] focus:ring-[#0F4C81]">
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                    </select>
                                </label>
                                <button type="button" @click="previousPage()" :disabled="loading || pagination.current_page <= 1"
                                    class="rounded-2xl border border-slate-200 px-4 py-2 text-sm font-bold text-slate-600 transition hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-40">
                                    Sebelumnya
                                </button>
                                <button type="button" @click="nextPage()" :disabled="loading || pagination.current_page >= pagination.last_page"
                                    class="rounded-2xl bg-[#0F4C81] px-4 py-2 text-sm font-bold text-white transition hover:bg-[#0b3b64] disabled:cursor-not-allowed disabled:opacity-40">
                                    Berikutnya
                                </button>
                            </div>
                        </div>
                    </section>
                </div>
            </main>
        </div>
    </div>
</body>

</html>
