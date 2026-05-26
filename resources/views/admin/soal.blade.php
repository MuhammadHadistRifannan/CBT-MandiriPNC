<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'CBT Mandiri PNC') }} - Bank Soal CBT</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#F5F7FA] text-slate-800 antialiased">
    <div x-data="{ sidebarOpen: false, importModal: false, ocrProcessing: false }" class="flex h-screen overflow-hidden">
        @include('layouts.admin.sidebar')

        <div class="flex min-w-0 flex-1 flex-col overflow-hidden">
            <header class="bg-white border-b border-slate-200 px-4 lg:px-8 py-4">
                <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
                    <div class="flex items-center gap-3">
                        <button type="button" @click="sidebarOpen = true" :aria-expanded="sidebarOpen.toString()"
                            aria-label="Buka menu navigasi"
                            class="lg:hidden w-11 h-11 rounded-2xl border border-slate-200 flex items-center justify-center bg-white shadow-sm">
                            <svg class="w-6 h-6 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>

                        <div>
                            <h1 class="text-xl lg:text-2xl font-black text-slate-900">Bank Soal CBT</h1>
                            <p class="text-sm text-slate-500">Buat, review, dan rilis soal pilihan ganda ujian mandiri.</p>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3">
                        <button type="button" @click="importModal = true"
                            class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl bg-amber-500 hover:bg-amber-600 text-white font-semibold shadow-sm transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1M12 4v12m0 0l4-4m-4 4l-4-4" />
                            </svg>
                            Upload PDF
                        </button>

                        <a href="{{ route('admin.soal.create') }}"
                            class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl bg-[#0F4C81] hover:bg-[#0B3A63] text-white font-semibold shadow-sm transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah Manual
                        </a>
                    </div>
                </div>
            </header>

            <main class="min-w-0 flex-1 space-y-6 overflow-y-auto p-4 lg:p-8">
                @if (session('success'))
                    <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-700">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-semibold text-red-700">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">
                    <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
                        <p class="text-sm font-medium text-slate-500">Total Soal</p>
                        <h2 class="text-3xl font-black mt-2 text-slate-900">{{ number_format($stats['total']) }}</h2>
                        <p class="mt-5 text-sm font-semibold text-slate-500">Semua status</p>
                    </div>
                    <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
                        <p class="text-sm font-medium text-slate-500">Draft</p>
                        <h2 class="text-3xl font-black mt-2 text-amber-600">{{ number_format($stats['draft']) }}</h2>
                        <p class="mt-5 text-sm font-semibold text-amber-600">Menunggu review</p>
                    </div>
                    <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
                        <p class="text-sm font-medium text-slate-500">Preview</p>
                        <h2 class="text-3xl font-black mt-2 text-blue-600">{{ number_format($stats['preview']) }}</h2>
                        <p class="mt-5 text-sm font-semibold text-blue-600">Sudah dikoreksi</p>
                    </div>
                    <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
                        <p class="text-sm font-medium text-slate-500">Released</p>
                        <h2 class="text-3xl font-black mt-2 text-emerald-600">{{ number_format($stats['released']) }}</h2>
                        <p class="mt-5 text-sm font-semibold text-emerald-600">Siap platform ujian</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                    <section class="xl:col-span-2 bg-white rounded-3xl border border-slate-200 shadow-sm p-6">
                        <div class="flex items-center justify-between gap-4 mb-6">
                            <div>
                                <h2 class="text-lg font-black text-slate-900">Distribusi Sub Soal</h2>
                                <p class="text-sm text-slate-500 mt-1">Jumlah soal berdasarkan PM, PBI, PU, dan PPU.</p>
                            </div>
                        </div>

                        <div class="space-y-4">
                            @foreach (\App\Models\SoalCbt::SUB_SOAL as $subSoal)
                                @php
                                    $total = (int) ($categoryStats[$subSoal] ?? 0);
                                    $percent = $stats['total'] > 0 ? max(6, round(($total / $stats['total']) * 100)) : 0;
                                @endphp
                                <div>
                                    <div class="flex items-center justify-between text-sm font-semibold">
                                        <span>{{ $subSoal }}</span>
                                        <span class="text-slate-500">{{ $total }} soal</span>
                                    </div>
                                    <div class="mt-2 h-3 rounded-full bg-slate-100 overflow-hidden">
                                        <div class="h-full rounded-full bg-[#0F4C81]" style="width: {{ $percent }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>

                    <section class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6">
                        <h2 class="text-lg font-black text-slate-900">Alur Review</h2>
                        <div class="mt-6 space-y-5">
                            <div class="flex gap-4">
                                <div class="w-3 h-3 rounded-full bg-amber-500 mt-2"></div>
                                <div>
                                    <p class="text-sm font-semibold">Draft</p>
                                    <p class="text-xs text-slate-500 mt-1">Soal baru dari manual atau Detektor OCR.</p>
                                </div>
                            </div>
                            <div class="flex gap-4">
                                <div class="w-3 h-3 rounded-full bg-blue-500 mt-2"></div>
                                <div>
                                    <p class="text-sm font-semibold">Preview</p>
                                    <p class="text-xs text-slate-500 mt-1">Soal sudah diedit atau divalidasi admin.</p>
                                </div>
                            </div>
                            <div class="flex gap-4">
                                <div class="w-3 h-3 rounded-full bg-emerald-500 mt-2"></div>
                                <div>
                                    <p class="text-sm font-semibold">Released</p>
                                    <p class="text-xs text-slate-500 mt-1">Soal siap masuk platform ujian.</p>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>

                <section class="min-w-0 overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                    <div class="p-6 border-b border-slate-200">
                        <form method="GET" action="{{ route('admin.soal') }}"
                            class="grid grid-cols-1 md:grid-cols-[1fr_160px_160px_140px_auto] gap-3">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode atau pertanyaan..."
                                class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#0F4C81]/20">

                            <select name="sub_soal" class="rounded-2xl border border-slate-200 px-4 py-3 text-sm">
                                <option value="">Semua Sub Soal</option>
                                @foreach (\App\Models\SoalCbt::SUB_SOAL as $subSoal)
                                    <option value="{{ $subSoal }}" @selected(request('sub_soal') === $subSoal)>{{ $subSoal }}</option>
                                @endforeach
                            </select>

                            <select name="status" class="rounded-2xl border border-slate-200 px-4 py-3 text-sm">
                                <option value="">Semua Status</option>
                                @foreach (\App\Enums\SoalCbtStatus::cases() as $status)
                                    <option value="{{ $status->value }}" @selected(request('status') === $status->value)>{{ $status->label() }}</option>
                                @endforeach
                            </select>

                            <select name="per_page" class="rounded-2xl border border-slate-200 px-4 py-3 text-sm">
                                @foreach ([10, 25, 50, 100] as $size)
                                    <option value="{{ $size }}" @selected((int) request('per_page', 10) === $size)>{{ $size }} / halaman</option>
                                @endforeach
                            </select>

                            <button class="px-5 py-3 rounded-2xl bg-slate-900 text-white text-sm font-semibold">Filter</button>
                        </form>
                    </div>

                    <div class="flex items-center justify-between border-b border-slate-100 bg-slate-50/70 px-4 py-3 xl:hidden sm:px-6">
                        <p class="text-xs font-semibold text-slate-500">Geser tabel, aksi tetap tersedia di kanan</p>
                        <svg class="h-4 w-4 text-[#0F4C81]" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7l-5 5 5 5m-5-5h18m-5-5l5 5-5 5" />
                        </svg>
                    </div>

                    <div class="w-full max-w-full overflow-x-auto overscroll-x-contain [scrollbar-width:thin] [-webkit-overflow-scrolling:touch]">
                        <table class="min-w-[900px] w-full lg:min-w-[1100px]">
                            <thead class="bg-slate-50 border-b border-slate-200">
                                <tr class="text-left text-sm text-slate-500">
                                    <th class="px-6 py-4">Kode</th>
                                    <th class="px-6 py-4">Pertanyaan</th>
                                    <th class="px-6 py-4">Sub Soal</th>
                                    <th class="px-6 py-4">Sumber</th>
                                    <th class="px-6 py-4">Jawaban</th>
                                    <th class="px-6 py-4">Status</th>
                                    <th class="sticky right-0 z-10 border-l border-slate-200 bg-slate-50 px-4 py-4 shadow-[-8px_0_12px_-12px_rgba(15,23,42,0.45)] sm:px-6">
                                        Action
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-slate-100">
                                @forelse ($soal as $item)
                                    @php
                                        $badgeClass = match ($item->status) {
                                            \App\Enums\SoalCbtStatus::Draft => 'bg-amber-100 text-amber-700',
                                            \App\Enums\SoalCbtStatus::Preview => 'bg-blue-100 text-blue-700',
                                            \App\Enums\SoalCbtStatus::Released => 'bg-emerald-100 text-emerald-700',
                                        };
                                    @endphp
                                    <tr class="group transition hover:bg-slate-50">
                                        <td class="whitespace-nowrap px-6 py-5 font-semibold text-slate-900">{{ $item->kode_soal }}</td>
                                        <td class="w-[420px] px-6 py-5 lg:w-[520px]">
                                            <p class="line-clamp-2 text-sm leading-relaxed">{{ $item->pertanyaan }}</p>
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-5 font-semibold">{{ $item->sub_soal }}</td>
                                        <td class="whitespace-nowrap px-6 py-5">{{ $item->source_type->label() }}</td>
                                        <td class="px-6 py-5">
                                            <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-bold">
                                                {{ $item->jawaban_benar }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-5">
                                            <span class="px-3 py-1 rounded-full {{ $badgeClass }} text-xs font-bold">
                                                {{ $item->status->label() }}
                                            </span>
                                        </td>
                                        <td class="sticky right-0 z-[5] border-l border-slate-100 bg-white px-3 py-5 shadow-[-8px_0_12px_-12px_rgba(15,23,42,0.45)] group-hover:bg-slate-50 sm:px-4">
                                            <div class="grid min-w-[158px] grid-cols-2 gap-2 lg:flex lg:min-w-max lg:flex-nowrap">
                                                <a href="{{ route('admin.soal.preview', $item) }}"
                                                    class="inline-flex justify-center rounded-xl bg-[#0F4C81] px-3 py-2 text-sm font-semibold text-white transition hover:bg-[#0B3A63] lg:px-4">
                                                    Preview
                                                </a>
                                                <a href="{{ route('admin.soal.edit', $item) }}"
                                                    class="inline-flex justify-center rounded-xl border border-slate-200 px-3 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 lg:px-4">
                                                    Edit
                                                </a>
                                                @if ($item->status !== \App\Enums\SoalCbtStatus::Released)
                                                    <form method="POST" action="{{ route('admin.soal.release', $item) }}">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button class="w-full rounded-xl bg-emerald-600 px-3 py-2 text-sm font-semibold text-white transition hover:bg-emerald-700 lg:px-4">
                                                            Rilis
                                                        </button>
                                                    </form>
                                                @endif
                                                <form method="POST" action="{{ route('admin.soal.destroy', $item) }}"
                                                    onsubmit="return confirm('Hapus soal {{ $item->kode_soal }} dari bank soal?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="w-full rounded-xl bg-red-50 px-3 py-2 text-sm font-semibold text-red-700 transition hover:bg-red-100 lg:px-4">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                                            Belum ada soal CBT. Tambahkan manual atau upload PDF template rapi.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="p-6 border-t border-slate-200 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                        <p class="text-sm font-medium text-slate-500">
                            Menampilkan {{ $soal->firstItem() ?? 0 }}-{{ $soal->lastItem() ?? 0 }} dari {{ $soal->total() }} soal
                        </p>

                        <div>
                            {{ $soal->links() }}
                        </div>
                    </div>
                </section>
            </main>
        </div>

        <div x-show="importModal" x-cloak x-transition.opacity class="fixed inset-0 z-[9999]">
            <div @click="if (!ocrProcessing) importModal = false" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
            <div class="relative flex min-h-screen items-center justify-center p-4">
                <form method="POST" action="{{ route('admin.soal.import-pdf') }}" enctype="multipart/form-data"
                    @submit="ocrProcessing = true"
                    @click.stop
                    class="w-full max-w-xl rounded-3xl bg-white shadow-2xl overflow-hidden">
                    @csrf
                    <div class="px-6 py-5 border-b border-slate-200">
                        <h2 class="text-xl font-black text-slate-900">Upload Soal PDF</h2>
                        <p class="text-sm text-slate-500 mt-1">Gunakan PDF template rapi berisi nomor soal, opsi A-D/E, dan kunci jawaban.</p>
                    </div>

                    <div class="p-6 space-y-4">
                        <input type="file" name="pdf" accept="application/pdf" required :disabled="ocrProcessing"
                            class="block w-full rounded-2xl border border-slate-200 p-3 text-sm file:mr-4 file:rounded-xl file:border-0 file:bg-[#0F4C81] file:px-4 file:py-2 file:text-white file:font-semibold">
                        @error('pdf')
                            <p class="text-sm text-red-600 font-semibold">{{ $message }}</p>
                        @enderror
                        <p class="text-xs leading-relaxed text-slate-500">
                            Sistem akan mengirim PDF ke OCR detector untuk ekstraksi teks dan jawaban. Hasil import tetap berstatus draft sampai admin review.
                        </p>
                    </div>

                    <div class="px-6 py-4 border-t border-slate-200 flex justify-end gap-3">
                        <button type="button" @click="importModal = false" :disabled="ocrProcessing"
                            class="px-5 py-3 rounded-2xl border border-slate-300 font-semibold text-slate-700 hover:bg-slate-100 transition disabled:cursor-not-allowed disabled:opacity-50">
                            Batal
                        </button>
                        <button type="submit" :disabled="ocrProcessing"
                            class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl bg-amber-500 text-white font-semibold hover:bg-amber-600 transition disabled:cursor-not-allowed disabled:opacity-70">
                            <svg x-show="ocrProcessing" class="w-5 h-5 animate-spin" viewBox="0 0 24 24" fill="none">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                            </svg>
                            <span x-text="ocrProcessing ? 'Memproses OCR...' : 'Proses OCR'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
