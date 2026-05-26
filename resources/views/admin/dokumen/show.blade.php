<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'CBT Mandiri PNC') }} - Review Dokumen</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#F5F7FA] text-slate-800 antialiased">
    <div x-data="{ sidebarOpen: false }" class="flex h-screen overflow-hidden">
        @include('layouts.admin.sidebar')

        <div class="flex flex-1 flex-col overflow-hidden">
            @include('layouts.admin.mobile-header', ['title' => 'Review Dokumen'])

            <main class="flex-1 overflow-y-auto bg-[#F8FAFC] p-4 lg:p-8">
                <div class="mb-7 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <a href="{{ route('admin.dokumen', ['status' => \App\Enums\DokumenStatus::Pending->value]) }}"
                            class="mb-3 inline-flex text-sm font-semibold text-[#0F4C81] hover:underline">
                            Kembali ke antrean
                        </a>
                        <h1 class="text-2xl font-black text-slate-800 lg:text-3xl">Review Dokumen Peserta</h1>
                        <p class="mt-1 text-slate-500">Periksa kejelasan dan kesesuaian berkas sebelum menentukan status.</p>
                    </div>

                    @if ($nextPending)
                        <a href="{{ route('admin.dokumen.show', $nextPending) }}"
                            class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                            Dokumen Pending Berikutnya
                        </a>
                    @endif
                </div>

                @if (session('success'))
                    <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-700">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="grid grid-cols-1 gap-6 xl:grid-cols-[minmax(0,1fr)_370px]">
                    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                        <div class="mb-6 flex flex-wrap items-center justify-between gap-4 border-b border-slate-100 pb-5">
                            <div>
                                <p class="text-xl font-black text-slate-800">{{ $dokumen->user->name }}</p>
                                <p class="mt-1 text-sm text-slate-500">
                                    {{ $dokumen->user->peserta?->nomor_peserta ?? 'Belum memiliki nomor peserta' }} - {{ $dokumen->user->email }}
                                </p>
                            </div>
                            <span class="rounded-xl px-4 py-2 text-xs font-bold {{ $dokumen->status->badgeClass() }}">
                                {{ $dokumen->status->label() }}
                            </span>
                        </div>

                        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                            @forelse ($files as $label => $path)
                                <article class="overflow-hidden rounded-2xl border border-slate-200">
                                    <div class="flex items-center justify-between border-b border-slate-100 bg-slate-50 px-4 py-3">
                                        <p class="text-sm font-bold text-slate-700">{{ $label }}</p>
                                        <a href="{{ asset('storage/' . $path) }}" target="_blank" rel="noopener"
                                            class="text-xs font-bold text-[#0F4C81] hover:underline">Buka File</a>
                                    </div>
                                    <iframe src="{{ asset('storage/' . $path) }}" title="{{ $label }}"
                                        class="h-72 w-full bg-slate-100"></iframe>
                                </article>
                            @empty
                                <div class="col-span-full rounded-2xl bg-slate-50 px-6 py-12 text-center text-sm text-slate-500">
                                    Tidak ada file yang dapat diperiksa.
                                </div>
                            @endforelse
                        </div>
                    </section>

                    <aside class="space-y-5">
                        <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                            <h2 class="text-lg font-black text-slate-800">Data Pendaftaran</h2>
                            <dl class="mt-5 space-y-4 text-sm">
                                <div>
                                    <dt class="text-slate-500">Pilihan Utama</dt>
                                    <dd class="mt-1 font-semibold text-slate-800">{{ $dokumen->user->pilihan?->pilihan_prodi_1?->nama_prodi ?? '-' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-slate-500">Pilihan Kedua</dt>
                                    <dd class="mt-1 font-semibold text-slate-800">{{ $dokumen->user->pilihan?->pilihan_prodi_2?->nama_prodi ?? '-' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-slate-500">Tanggal Upload</dt>
                                    <dd class="mt-1 font-semibold text-slate-800">{{ $dokumen->created_at->format('d M Y, H:i') }}</dd>
                                </div>
                                @if ($dokumen->reviewer)
                                    <div>
                                        <dt class="text-slate-500">Reviewer Terakhir</dt>
                                        <dd class="mt-1 font-semibold text-slate-800">{{ $dokumen->reviewer->name }}</dd>
                                        <dd class="text-xs text-slate-500">{{ $dokumen->reviewed_at?->format('d M Y, H:i') }}</dd>
                                    </div>
                                @endif
                                @if ($dokumen->rejection_note)
                                    <div>
                                        <dt class="text-slate-500">Alasan Penolakan</dt>
                                        <dd class="mt-1 rounded-xl bg-red-50 p-3 font-medium text-red-700">{{ $dokumen->rejection_note }}</dd>
                                    </div>
                                @endif
                            </dl>
                        </section>

                        <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                            <h2 class="text-lg font-black text-slate-800">Keputusan Verifikasi</h2>
                            <p class="mt-1 text-sm text-slate-500">Pastikan semua file telah diperiksa sebelum menyimpan.</p>

                            @if ($errors->any())
                                <div class="mt-4 rounded-2xl bg-red-50 px-4 py-3 text-sm font-medium text-red-700">
                                    {{ $errors->first() }}
                                </div>
                            @endif

                            <form method="POST" action="{{ route('admin.dokumen.review', $dokumen) }}" class="mt-5">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="{{ \App\Enums\DokumenStatus::Verified->value }}">
                                <input type="hidden" name="continue_next" value="1">
                                <button type="submit"
                                    class="w-full rounded-2xl bg-emerald-600 px-4 py-3 text-sm font-bold text-white transition hover:bg-emerald-700">
                                    Verifikasi dan Lanjut
                                </button>
                            </form>

                            <form method="POST" action="{{ route('admin.dokumen.review', $dokumen) }}" class="mt-4">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="{{ \App\Enums\DokumenStatus::Rejected->value }}">
                                <input type="hidden" name="continue_next" value="1">
                                <label for="rejection_note" class="mb-2 block text-sm font-semibold text-slate-700">
                                    Alasan penolakan
                                </label>
                                <textarea id="rejection_note" name="rejection_note" rows="4" required
                                    placeholder="Contoh: Foto identitas tidak terbaca."
                                    class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:border-red-400 focus:outline-none focus:ring-2 focus:ring-red-100">{{ old('rejection_note') }}</textarea>
                                <button type="submit"
                                    class="mt-3 w-full rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-bold text-red-700 transition hover:bg-red-100">
                                    Tolak dan Lanjut
                                </button>
                            </form>
                        </section>
                    </aside>
                </div>
            </main>
        </div>
    </div>
</body>

</html>
