<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'CBT Mandiri PNC') }} - Check-in Peserta</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#F5F7FA] text-slate-800 antialiased">
    <div x-data="{
        sidebarOpen: false,
        scanner: null,
        scannerActive: false,
        loading: false,
        confirming: false,
        manualCode: '',
        participant: null,
        source: null,
        notice: null,
        async startScanner() {
            this.notice = null;
            this.participant = null;
            try {
                this.scanner = new window.Html5Qrcode('qr-reader');
                await this.scanner.start({ facingMode: 'environment' }, { fps: 10, qrbox: { width: 230, height: 230 } }, async (payload) => {
                    await this.stopScanner();
                    await this.lookup({ method: 'qr', qr_payload: payload });
                }, () => {});
                this.scannerActive = true;
            } catch (error) {
                this.notice = { type: 'error', text: 'Kamera tidak dapat dibuka. Gunakan input manual atau izinkan akses kamera.' };
            }
        },
        async stopScanner() {
            if (this.scanner && this.scannerActive) {
                await this.scanner.stop();
                this.scanner.clear();
            }
            this.scannerActive = false;
            this.scanner = null;
        },
        async lookup(payload) {
            this.loading = true;
            this.notice = null;
            this.participant = null;
            try {
                const response = await fetch(@js(route('pengawas.check-in.lookup')), {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                    },
                    body: JSON.stringify(payload)
                });
                const result = await response.json();
                if (!response.ok) {
                    throw new Error(Object.values(result.errors ?? {}).flat()[0] ?? 'Data kartu ujian tidak dapat divalidasi.');
                }
                this.source = payload;
                this.participant = result.participant;
            } catch (error) {
                this.notice = { type: 'error', text: error.message };
            } finally {
                this.loading = false;
            }
        },
        lookupManual() {
            if (!this.manualCode.trim()) {
                this.notice = { type: 'error', text: 'Masukkan kode ujian terlebih dahulu.' };
                return;
            }
            this.lookup({ method: 'manual', kode_ujian: this.manualCode.trim() });
        },
        async confirm() {
            if (!this.participant || !this.source) return;
            this.confirming = true;
            this.notice = null;
            try {
                const response = await fetch(this.participant.confirm_url, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                    },
                    body: JSON.stringify(this.source)
                });
                const result = await response.json();
                if (!response.ok) {
                    throw new Error(Object.values(result.errors ?? {}).flat()[0] ?? 'Check-in gagal dikonfirmasi.');
                }
                this.participant = result.participant;
                this.notice = { type: 'success', text: result.message };
                this.manualCode = '';
            } catch (error) {
                this.notice = { type: 'error', text: error.message };
            } finally {
                this.confirming = false;
            }
        }
    }" class="flex h-screen overflow-hidden">
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
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[#0F4C81]">Operasional Ujian</p>
                    <h1 class="text-xl font-black text-slate-800 sm:text-2xl">Check-in Peserta</h1>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto bg-[#F8FAFC] p-4 lg:p-8">
                <div class="mx-auto max-w-7xl space-y-6">
                    <section>
                        <h2 class="text-2xl font-black text-slate-800">Scan Kartu Ujian</h2>
                        <p class="mt-2 text-sm text-slate-500">Verifikasi fisik peserta sebelum memberikan akses ujian CBT.</p>
                    </section>

                    <div x-show="notice" x-transition
                        class="rounded-2xl border px-5 py-4 text-sm font-semibold"
                        :class="notice?.type === 'success' ? 'border-emerald-200 bg-emerald-50 text-emerald-700' : 'border-red-200 bg-red-50 text-red-700'"
                        x-text="notice?.text"></div>

                    <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
                        <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                            <div class="mb-5">
                                <h3 class="text-lg font-black text-slate-800">Scanner Kamera</h3>
                                <p class="mt-1 text-sm text-slate-500">Arahkan kamera ke QR kartu peserta.</p>
                            </div>
                            <div id="qr-reader" class="min-h-[260px] overflow-hidden rounded-2xl border border-dashed border-slate-200 bg-slate-50"></div>
                            <div class="mt-5 flex gap-3">
                                <button type="button" x-show="!scannerActive" @click="startScanner()"
                                    class="flex-1 rounded-2xl bg-[#0F4C81] px-4 py-3 text-sm font-semibold text-white hover:bg-[#0B3A63]">
                                    Buka Kamera
                                </button>
                                <button type="button" x-show="scannerActive" @click="stopScanner()"
                                    class="flex-1 rounded-2xl bg-slate-800 px-4 py-3 text-sm font-semibold text-white">
                                    Hentikan Scan
                                </button>
                            </div>
                        </section>

                        <section class="space-y-6 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                            <div>
                                <h3 class="text-lg font-black text-slate-800">Input Manual</h3>
                                <p class="mt-1 text-sm text-slate-500">Gunakan kode ujian jika kamera tidak tersedia.</p>
                            </div>
                            <label class="block">
                                <span class="mb-2 block text-xs font-bold uppercase tracking-wide text-slate-500">Kode Ujian</span>
                                <input type="text" x-model="manualCode" @keydown.enter.prevent="lookupManual()"
                                    placeholder="Contoh: UJN-12-ABCDEFGH"
                                    class="w-full rounded-2xl border-slate-200 px-4 py-3 text-sm font-semibold uppercase focus:border-[#0F4C81] focus:ring-[#0F4C81]">
                            </label>
                            <button type="button" @click="lookupManual()" :disabled="loading"
                                class="w-full rounded-2xl border border-[#0F4C81] bg-blue-50 px-4 py-3 text-sm font-semibold text-[#0F4C81] transition hover:bg-blue-100 disabled:opacity-60">
                                <span x-text="loading ? 'Memvalidasi...' : 'Cari Peserta'"></span>
                            </button>
                            <div class="rounded-2xl bg-amber-50 p-4 text-xs leading-relaxed text-amber-700">
                                Input manual hanya digunakan setelah mencocokkan identitas peserta dengan kartu fisik.
                            </div>
                        </section>

                        <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                            <h3 class="text-lg font-black text-slate-800">Data Peserta</h3>
                            <div x-show="!participant" class="mt-6 rounded-2xl bg-slate-50 px-4 py-12 text-center text-sm text-slate-500">
                                Scan QR atau masukkan kode ujian untuk melihat peserta.
                            </div>
                            <div x-show="participant" x-cloak class="mt-5 space-y-4">
                                <div>
                                    <p class="text-xs font-bold uppercase text-slate-400">Nama Peserta</p>
                                    <p class="mt-1 text-lg font-black text-slate-800" x-text="participant?.name"></p>
                                    <p class="text-sm text-slate-500" x-text="participant?.participant_number"></p>
                                </div>
                                <dl class="space-y-3 rounded-2xl bg-slate-50 p-4 text-sm">
                                    <div class="flex justify-between gap-3"><dt class="text-slate-500">Kode ujian</dt><dd class="font-semibold text-slate-800" x-text="participant?.exam_code"></dd></div>
                                    <div class="flex justify-between gap-3"><dt class="text-slate-500">Prodi utama</dt><dd class="text-right font-semibold text-slate-800" x-text="participant?.primary_prodi"></dd></div>
                                    <div class="flex justify-between gap-3"><dt class="text-slate-500">Prodi cadangan</dt><dd class="text-right font-semibold text-slate-800" x-text="participant?.secondary_prodi"></dd></div>
                                    <div class="flex justify-between gap-3"><dt class="text-slate-500">Pembayaran</dt><dd class="font-semibold text-emerald-600" x-text="participant?.payment_label"></dd></div>
                                    <div class="flex justify-between gap-3"><dt class="text-slate-500">Status</dt><dd><span class="rounded-xl px-3 py-1 text-xs font-bold" :class="participant?.status_class" x-text="participant?.status_label"></span></dd></div>
                                </dl>
                                <button type="button" x-show="participant?.status === 'not_checked_in'" @click="confirm()" :disabled="confirming"
                                    class="w-full rounded-2xl bg-emerald-600 px-4 py-4 text-sm font-bold text-white transition hover:bg-emerald-700 disabled:opacity-60">
                                    <span x-text="confirming ? 'Menyimpan...' : 'Konfirmasi Check-in'"></span>
                                </button>
                            </div>
                        </section>
                    </div>

                    <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                        <div class="border-b border-slate-100 px-6 py-5">
                            <h3 class="text-lg font-black text-slate-800">Check-in Terbaru</h3>
                            <p class="mt-1 text-sm text-slate-500">Peserta yang baru selesai diverifikasi pengawas.</p>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full min-w-[680px]">
                                <thead class="bg-slate-50 text-left text-xs font-bold uppercase tracking-wide text-slate-500">
                                    <tr>
                                        <th class="px-6 py-4">Peserta</th>
                                        <th class="px-6 py-4">Waktu</th>
                                        <th class="px-6 py-4">Metode</th>
                                        <th class="px-6 py-4">Pengawas</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @forelse ($recentCheckIns as $checkIn)
                                        <tr>
                                            <td class="px-6 py-4">
                                                <p class="font-semibold text-slate-800">{{ $checkIn->user?->name ?? '-' }}</p>
                                                <p class="text-xs text-slate-500">{{ $checkIn->user?->peserta?->nomor_peserta ?? $checkIn->kode_ujian }}</p>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-slate-600">{{ $checkIn->checked_in_at?->format('d M Y, H:i') }}</td>
                                            <td class="px-6 py-4 text-sm font-semibold text-slate-700">{{ $checkIn->check_in_method?->label() ?? '-' }}</td>
                                            <td class="px-6 py-4 text-sm text-slate-600">{{ $checkIn->pengawas?->name ?? '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="px-6 py-10 text-center text-sm text-slate-500">Belum ada peserta yang check-in.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </section>
                </div>
            </main>
        </div>
    </div>
</body>

</html>
