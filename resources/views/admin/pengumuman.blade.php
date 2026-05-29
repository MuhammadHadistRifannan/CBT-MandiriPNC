<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'CBT Mandiri PNC') }} - Pengumuman PMB</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#F5F7FA] text-slate-800 antialiased">
    <div x-data="{
        sidebarOpen: false,
        batchModal: false,
        batchAction: @js(route('admin.pengumuman.batches.store')),
        batchMethod: 'POST',
        batchForm: {
            title: 'Pengumuman Hasil PMB',
            tahun: @js(now('Asia/Jakarta')->year),
            announcement_date: '',
            status: 'draft'
        },
        openCreateBatch() {
            this.batchAction = @js(route('admin.pengumuman.batches.store'));
            this.batchMethod = 'POST';
            this.batchForm = { title: 'Pengumuman Hasil PMB', tahun: @js(now('Asia/Jakarta')->year), announcement_date: '', status: 'draft' };
            this.batchModal = true;
        },
        openEditBatch(item) {
            this.batchAction = item.update_url;
            this.batchMethod = 'PUT';
            this.batchForm = item;
            this.batchModal = true;
        }
    }" class="flex h-screen overflow-hidden">
        @include('layouts.admin.sidebar')

        <div class="flex min-w-0 flex-1 flex-col overflow-hidden">
            @include('layouts.admin.mobile-header', ['title' => 'Pengumuman PMB'])

            <main class="flex-1 overflow-y-auto p-4 lg:p-8">
                <div class="mx-auto max-w-7xl space-y-6">
                    <section class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                        <div>
                            <h1 class="text-2xl font-black text-slate-800 lg:text-3xl">Pengumuman Hasil PMB</h1>
                            <p class="mt-2 text-sm text-slate-500">Kelulusan digenerate otomatis berdasarkan skor akhir, pilihan prodi, dan kuota.</p>
                        </div>
                        <button type="button" @click="openCreateBatch()"
                            class="w-full rounded-2xl bg-[#0F4C81] px-5 py-3 text-sm font-black text-white shadow-lg sm:w-auto">
                            Buat Jadwal
                        </button>
                    </section>

                    @if(session('success'))
                        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-bold text-emerald-700">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-bold text-red-700">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                        <div class="mb-5">
                            <h2 class="text-lg font-black text-slate-800">Jadwal & Generate Ranking</h2>
                            <p class="mt-1 text-sm text-slate-500">Admin hanya mengatur jadwal, publish/unpublish, kuota prodi, dan menjalankan ranking otomatis.</p>
                        </div>

                        <div class="grid gap-4 lg:grid-cols-3">
                            @forelse($batches as $batch)
                                <article class="rounded-3xl border border-slate-200 p-5">
                                    <div class="flex items-start justify-between gap-3">
                                        <div>
                                            <h3 class="font-black text-slate-800">{{ $batch->title }}</h3>
                                            <p class="mt-1 text-sm text-slate-500">Tahun {{ $batch->tahun }}</p>
                                        </div>
                                        <span class="rounded-full px-3 py-1 text-xs font-black {{ $batch->status->badgeClass() }}">
                                            {{ $batch->status->label() }}
                                        </span>
                                    </div>

                                    <div class="mt-5 space-y-2 text-sm font-semibold text-slate-600">
                                        <p>{{ $batch->announcement_date->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB</p>
                                        <p>{{ $batch->results_count }} hasil peserta</p>
                                        <p class="{{ $batch->ranking_locked ? 'text-emerald-700' : 'text-amber-700' }}">
                                            {{ $batch->ranking_locked ? 'Ranking terkunci' : 'Ranking belum digenerate' }}
                                        </p>
                                        @if($batch->generated_at)
                                            <p class="text-xs text-slate-400">Generate: {{ $batch->generated_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB</p>
                                        @endif
                                    </div>

                                    <div class="mt-5 flex flex-wrap gap-2">
                                        <button type="button"
                                            @click="openEditBatch(@js([
                                                'title' => $batch->title,
                                                'tahun' => $batch->tahun,
                                                'announcement_date' => $batch->announcement_date->timezone('Asia/Jakarta')->format('Y-m-d\TH:i'),
                                                'status' => $batch->status->value,
                                                'update_url' => route('admin.pengumuman.batches.update', $batch),
                                            ]))"
                                            class="rounded-xl border border-slate-200 px-4 py-2 text-xs font-black text-slate-600 hover:bg-slate-50">
                                            Edit Jadwal
                                        </button>

                                        <form method="POST" action="{{ route('admin.pengumuman.batches.generate', $batch) }}"
                                            onsubmit="return confirm('Generate ranking untuk {{ $batch->title }}? Hasil akan dikunci setelah proses selesai.')">
                                            @csrf
                                            <button type="submit" @disabled($batch->ranking_locked)
                                                class="rounded-xl px-4 py-2 text-xs font-black {{ $batch->ranking_locked ? 'cursor-not-allowed bg-slate-100 text-slate-400' : 'bg-slate-900 text-white hover:bg-slate-700' }}">
                                                Generate Ranking
                                            </button>
                                        </form>
                                    </div>
                                </article>
                            @empty
                                <div class="rounded-3xl border border-dashed border-slate-300 p-8 text-center text-sm text-slate-500 lg:col-span-3">
                                    Belum ada jadwal pengumuman.
                                </div>
                            @endforelse
                        </div>
                    </section>

                    <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                        <div class="mb-5 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                            <div>
                                <h2 class="text-lg font-black text-slate-800">Kuota Prodi</h2>
                                <p class="mt-1 text-sm text-slate-500">Kuota ini dipakai oleh proses ranking otomatis.</p>
                            </div>
                            <a href="{{ route('admin.prodi') }}" class="text-sm font-black text-[#0F4C81] hover:underline">Kelola kuota prodi</a>
                        </div>
                        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                            @foreach($prodis as $prodi)
                                <div class="rounded-2xl border border-slate-200 p-4">
                                    <p class="truncate text-sm font-black text-slate-800">{{ strtoupper($prodi->tingkat) }} {{ $prodi->nama_prodi }}</p>
                                    <p class="mt-2 text-2xl font-black text-[#0F4C81]">{{ number_format($prodi->kuota) }}</p>
                                    <p class="text-xs font-semibold text-slate-400">Daya tampung: {{ number_format($prodi->daya_tampung) }}</p>
                                </div>
                            @endforeach
                        </div>
                    </section>

                    <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                        <form method="GET" action="{{ route('admin.pengumuman') }}" class="grid gap-3 md:grid-cols-[1fr_1fr_auto]">
                            <select name="batch_id" class="rounded-2xl border-slate-200 px-4 py-3 text-sm focus:border-[#0F4C81] focus:ring-[#0F4C81]">
                                <option value="">Semua Jadwal</option>
                                @foreach($batches as $batch)
                                    <option value="{{ $batch->id }}" @selected(($filters['batch_id'] ?? '') == $batch->id)>
                                        {{ $batch->title }} - {{ $batch->tahun }}
                                    </option>
                                @endforeach
                            </select>
                            <input type="search" name="search" value="{{ $filters['search'] ?? '' }}"
                                placeholder="Cari nomor peserta atau nama..."
                                class="rounded-2xl border-slate-200 px-4 py-3 text-sm focus:border-[#0F4C81] focus:ring-[#0F4C81]">
                            <button class="rounded-2xl bg-slate-900 px-5 py-3 text-sm font-bold text-white">Filter</button>
                        </form>
                    </section>

                    <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                        <div class="border-b border-slate-100 px-6 py-5">
                            <h2 class="text-lg font-black text-slate-800">Hasil Ranking Otomatis</h2>
                            <p class="mt-1 text-sm text-slate-500">Data ini read-only dan berasal dari hasil generate ranking.</p>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full min-w-[1120px]">
                                <thead class="bg-slate-50 text-left text-xs font-bold uppercase tracking-wide text-slate-500">
                                    <tr>
                                        <th class="px-6 py-4">Rank</th>
                                        <th class="px-6 py-4">Peserta</th>
                                        <th class="px-6 py-4">Skor</th>
                                        <th class="px-6 py-4">Pilihan 1</th>
                                        <th class="px-6 py-4">Pilihan 2</th>
                                        <th class="px-6 py-4">Hasil</th>
                                        <th class="px-6 py-4">Prodi Diterima</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @forelse($announcements as $announcement)
                                        <tr class="hover:bg-slate-50">
                                            <td class="px-6 py-4 text-sm font-black text-slate-700">#{{ $announcement->ranking_position }}</td>
                                            <td class="px-6 py-4">
                                                <p class="font-black text-slate-800">{{ $announcement->user?->name ?? 'Peserta PMB' }}</p>
                                                <p class="text-xs font-semibold text-slate-500">{{ $announcement->nomor_peserta }}</p>
                                            </td>
                                            <td class="px-6 py-4 text-sm font-black text-slate-700">{{ number_format((float) $announcement->skor_akhir, 2) }}</td>
                                            <td class="px-6 py-4 text-sm font-semibold text-slate-600">{{ $announcement->pilihanPertama?->nama_prodi ?? '-' }}</td>
                                            <td class="px-6 py-4 text-sm font-semibold text-slate-600">{{ $announcement->pilihanKedua?->nama_prodi ?? '-' }}</td>
                                            <td class="px-6 py-4">
                                                <span class="rounded-full px-3 py-1 text-xs font-black {{ $announcement->status_hasil->cardClass() }}">
                                                    {{ $announcement->status_hasil->label() }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-sm font-semibold text-slate-600">{{ $announcement->prodiDiterima?->nama_prodi ?? '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="px-6 py-12 text-center text-sm text-slate-500">
                                                Belum ada hasil ranking. Pilih jadwal, pastikan peserta sudah submit ujian, lalu klik Generate Ranking.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="border-t border-slate-100 px-6 py-4">
                            {{ $announcements->links() }}
                        </div>
                    </section>
                </div>
            </main>
        </div>

        <div x-show="batchModal" x-transition style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-slate-950/60" @click="batchModal = false"></div>
            <form method="POST" :action="batchAction" class="relative w-full max-w-xl rounded-[2rem] bg-white p-6 shadow-2xl">
                @csrf
                <template x-if="batchMethod === 'PUT'">
                    <input type="hidden" name="_method" value="PUT">
                </template>
                <h2 class="text-xl font-black text-slate-800">Jadwal Pengumuman</h2>
                <div class="mt-5 grid gap-4 sm:grid-cols-2">
                    <label class="sm:col-span-2">
                        <span class="mb-2 block text-sm font-bold text-slate-600">Judul</span>
                        <input name="title" x-model="batchForm.title" class="w-full rounded-2xl border-slate-200">
                    </label>
                    <label>
                        <span class="mb-2 block text-sm font-bold text-slate-600">Tahun</span>
                        <input type="number" name="tahun" x-model="batchForm.tahun" class="w-full rounded-2xl border-slate-200">
                    </label>
                    <label>
                        <span class="mb-2 block text-sm font-bold text-slate-600">Status</span>
                        <select name="status" x-model="batchForm.status" class="w-full rounded-2xl border-slate-200">
                            @foreach($announcementStatuses as $status)
                                <option value="{{ $status->value }}">{{ $status->label() }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="sm:col-span-2">
                        <span class="mb-2 block text-sm font-bold text-slate-600">Tanggal & Jam Pengumuman</span>
                        <input type="datetime-local" name="announcement_date" x-model="batchForm.announcement_date" class="w-full rounded-2xl border-slate-200">
                    </label>
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" @click="batchModal = false" class="rounded-2xl px-5 py-3 text-sm font-bold text-slate-500">Batal</button>
                    <button class="rounded-2xl bg-[#0F4C81] px-5 py-3 text-sm font-black text-white">Simpan Jadwal</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
