<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'CBT Mandiri PNC') }} - Preview Soal</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#F5F7FA] text-slate-800 antialiased">
    <div x-data="{ sidebarOpen: false }" class="flex h-screen overflow-hidden">
        @include('layouts.admin.sidebar')

        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="bg-white border-b border-slate-200 px-4 lg:px-8 py-4">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
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
                            <h1 class="text-xl lg:text-2xl font-black text-slate-900">Preview Soal</h1>
                            <p class="text-sm text-slate-500">{{ $soal->kode_soal }} · {{ $soal->sub_soal }} · {{ $soal->source_type->label() }}</p>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('admin.soal') }}"
                            class="px-5 py-3 rounded-2xl border border-slate-200 bg-white font-semibold text-slate-700 hover:bg-slate-50 transition">
                            Kembali
                        </a>
                        <a href="{{ route('admin.soal.edit', $soal) }}"
                            class="px-5 py-3 rounded-2xl bg-[#0F4C81] text-white font-semibold hover:bg-[#0B3A63] transition">
                            Edit Soal
                        </a>
                        @if ($soal->status !== \App\Enums\SoalCbtStatus::Released)
                            <form method="POST" action="{{ route('admin.soal.release', $soal) }}">
                                @csrf
                                @method('PATCH')
                                <button class="px-5 py-3 rounded-2xl bg-emerald-600 text-white font-semibold hover:bg-emerald-700 transition">
                                    Rilis Soal
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-4 lg:p-8">
                <div class="max-w-4xl mx-auto space-y-6">
                    @if (session('success'))
                        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-700">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-semibold text-red-700">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    @php
                        $badgeClass = match ($soal->status) {
                            \App\Enums\SoalCbtStatus::Draft => 'bg-amber-100 text-amber-700',
                            \App\Enums\SoalCbtStatus::Preview => 'bg-blue-100 text-blue-700',
                            \App\Enums\SoalCbtStatus::Released => 'bg-emerald-100 text-emerald-700',
                        };
                        $options = [
                            'A' => $soal->opsi_a,
                            'B' => $soal->opsi_b,
                            'C' => $soal->opsi_c,
                            'D' => $soal->opsi_d,
                            'E' => $soal->opsi_e,
                        ];
                    @endphp

                    <section class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                        <div class="px-6 py-5 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div>
                                <h2 class="text-lg font-black text-slate-900">{{ $soal->kode_soal }}</h2>
                                <p class="text-sm text-slate-500 mt-1">Dibuat oleh {{ $soal->pembuat?->name ?? 'Admin' }}</p>
                            </div>
                            <span class="w-fit px-3 py-1 rounded-full {{ $badgeClass }} text-xs font-bold">
                                {{ $soal->status->label() }}
                            </span>
                        </div>

                        <div class="p-6 lg:p-8 space-y-6">
                            <div class="bg-slate-50 rounded-2xl p-5">
                                <p class="text-slate-800 font-semibold leading-relaxed whitespace-pre-line">{{ $soal->pertanyaan }}</p>
                            </div>

                            <div class="space-y-3">
                                @foreach ($options as $key => $value)
                                    @continue(blank($value))
                                    <div class="border {{ $soal->jawaban_benar === $key ? 'border-emerald-500 bg-emerald-50' : 'border-slate-200 bg-white' }} rounded-2xl p-4">
                                        <div class="flex gap-3">
                                            <span class="font-black {{ $soal->jawaban_benar === $key ? 'text-emerald-700' : 'text-slate-500' }}">{{ $key }}.</span>
                                            <p class="font-semibold leading-relaxed whitespace-pre-line">{{ $value }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            @if ($soal->pembahasan)
                                <div class="rounded-2xl border border-blue-100 bg-blue-50 p-5">
                                    <h3 class="text-sm font-black text-blue-700 mb-2">Pembahasan</h3>
                                    <p class="text-sm leading-relaxed text-slate-700 whitespace-pre-line">{{ $soal->pembahasan }}</p>
                                </div>
                            @endif
                        </div>
                    </section>
                </div>
            </main>
        </div>
    </div>
</body>

</html>
