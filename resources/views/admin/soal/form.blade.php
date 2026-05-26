<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'CBT Mandiri PNC') }} - {{ $title }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#F5F7FA] text-slate-800 antialiased">
    <div x-data="{ sidebarOpen: false }" class="flex h-screen overflow-hidden">
        @include('layouts.admin.sidebar')

        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="bg-white border-b border-slate-200 px-4 lg:px-8 py-4">
                <div class="flex items-center justify-between gap-4">
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
                            <h1 class="text-xl lg:text-2xl font-black text-slate-900">{{ $title }}</h1>
                            <p class="text-sm text-slate-500">Isi soal pilihan ganda dan kunci jawaban yang valid.</p>
                        </div>
                    </div>

                    <a href="{{ route('admin.soal') }}"
                        class="hidden sm:inline-flex px-5 py-3 rounded-2xl border border-slate-200 bg-white font-semibold text-slate-700 hover:bg-slate-50 transition">
                        Kembali
                    </a>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-4 lg:p-8">
                <form method="POST" action="{{ $action }}" class="max-w-5xl mx-auto bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                    @csrf
                    @if ($method !== 'POST')
                        @method($method)
                    @endif

                    <div class="p-6 lg:p-8 space-y-6">
                        @if ($errors->any())
                            <div class="rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-semibold text-red-700">
                                Periksa kembali input soal. Ada data yang belum valid.
                            </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Sub Soal</label>
                                <select name="sub_soal"
                                    class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#0F4C81]/20">
                                    @foreach (\App\Models\SoalCbt::SUB_SOAL as $subSoal)
                                        <option value="{{ $subSoal }}" @selected(old('sub_soal', $soal?->sub_soal) === $subSoal)>{{ $subSoal }}</option>
                                    @endforeach
                                </select>
                                @error('sub_soal')
                                    <p class="mt-2 text-sm text-red-600 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Jawaban Benar</label>
                                <select name="jawaban_benar"
                                    class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#0F4C81]/20">
                                    @foreach (\App\Models\SoalCbt::JAWABAN as $jawaban)
                                        <option value="{{ $jawaban }}" @selected(old('jawaban_benar', $soal?->jawaban_benar) === $jawaban)>{{ $jawaban }}</option>
                                    @endforeach
                                </select>
                                @error('jawaban_benar')
                                    <p class="mt-2 text-sm text-red-600 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Pertanyaan</label>
                            <textarea name="pertanyaan" rows="6"
                                class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm leading-relaxed focus:outline-none focus:ring-2 focus:ring-[#0F4C81]/20">{{ old('pertanyaan', $soal?->pertanyaan) }}</textarea>
                            @error('pertanyaan')
                                <p class="mt-2 text-sm text-red-600 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            @foreach (['a', 'b', 'c', 'd', 'e'] as $opsi)
                                @php $field = 'opsi_' . $opsi; @endphp
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-2">
                                        Opsi {{ strtoupper($opsi) }} {{ $opsi === 'e' ? '(Opsional)' : '' }}
                                    </label>
                                    <textarea name="{{ $field }}" rows="3"
                                        class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm leading-relaxed focus:outline-none focus:ring-2 focus:ring-[#0F4C81]/20">{{ old($field, $soal?->{$field}) }}</textarea>
                                    @error($field)
                                        <p class="mt-2 text-sm text-red-600 font-semibold">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endforeach
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Pembahasan (Opsional)</label>
                            <textarea name="pembahasan" rows="4"
                                class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm leading-relaxed focus:outline-none focus:ring-2 focus:ring-[#0F4C81]/20">{{ old('pembahasan', $soal?->pembahasan) }}</textarea>
                            @error('pembahasan')
                                <p class="mt-2 text-sm text-red-600 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="px-6 lg:px-8 py-5 border-t border-slate-200 flex flex-col sm:flex-row justify-end gap-3">
                        <a href="{{ $soal ? route('admin.soal.preview', $soal) : route('admin.soal') }}"
                            class="inline-flex justify-center px-5 py-3 rounded-2xl border border-slate-300 font-semibold text-slate-700 hover:bg-slate-100 transition">
                            Batal
                        </a>
                        <button class="px-5 py-3 rounded-2xl bg-[#0F4C81] text-white font-semibold hover:bg-[#0B3A63] transition">
                            Simpan Soal
                        </button>
                    </div>
                </form>
            </main>
        </div>
    </div>
</body>

</html>
