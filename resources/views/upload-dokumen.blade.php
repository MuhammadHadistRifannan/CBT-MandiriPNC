<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'CBT Mandiri PNC') }} - Upload Dokumen</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-[#F8FAFC] text-slate-800">

    <div x-data="{ sidebarExpanded: true }" class="flex h-screen overflow-hidden">

        {{-- SIDEBAR --}}
        @include('layouts.dashboard.sidebar')

        {{-- CONTENT --}}
        <main class="flex-1 overflow-y-auto">

            <div class="p-6 md:p-8">

                {{-- HEADER --}}
                <div class="mb-8">

                    <h1 class="text-3xl font-bold text-slate-800">
                        Upload Dokumen Persyaratan
                    </h1>

                    <p class="text-slate-500 mt-2">
                        Lengkapi seluruh dokumen untuk mengikuti Seleksi Ujian Mandiri.
                    </p>

                </div>

                {{-- INFORMASI --}}
                <div
                    class="mb-8 rounded-3xl border border-blue-200 bg-blue-50 p-6">

                    <h3 class="font-semibold text-blue-800">
                        Persyaratan Peserta
                    </h3>

                    <ul class="mt-3 space-y-2 text-sm text-blue-700 list-disc pl-5 leading-relaxed">

                        <li>
                            Siswa kelas 12 SMA/MA/SMK/sederajat atau Paket C tahun berjalan.
                        </li>

                        <li>
                            Lulusan SMA/MA/SMK/sederajat atau Paket C maksimal 2 tahun terakhir.
                        </li>

                        <li>
                            Usia maksimal 25 tahun per 1 Juli tahun berjalan.
                        </li>

                        <li>
                            Memiliki prestasi akademik yang baik dan konsisten.
                        </li>

                        <li>
                            Wajib mengupload dokumen yang valid dan jelas.
                        </li>

                    </ul>

                </div>

                {{-- FORM --}}
                <form
                    action="{{ route('dokumen.simpan') }}"
                    method="POST"
                    enctype="multipart/form-data">

                    @csrf

                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

    {{-- PAS FOTO --}}
    <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm">

        <div class="mb-5">

            <h3 class="text-lg font-semibold">
                Pas Foto Terbaru
            </h3>

            <p class="text-sm text-slate-500 mt-1">
                Format JPG, JPEG, PNG maksimal 2 MB.
            </p>

        </div>

        {{-- PREVIEW FOTO --}}
        @if ($dokumen && $dokumen->foto)

            <div class="mb-5 flex justify-center">

                <img src="{{ asset('storage/' . $dokumen->foto) }}"
                    class="w-40 h-52 object-cover rounded-3xl border-4 border-slate-100 shadow-lg">

            </div>

        @endif

        <input
            type="file"
            name="foto"
            accept=".jpg,.jpeg,.png"
            class="block w-full text-sm
            file:mr-4 file:px-5 file:py-3
            file:border-0 file:rounded-2xl
            file:bg-blue-50 file:text-blue-700
            hover:file:bg-blue-100">

        @error('foto')
            <p class="text-red-500 text-sm mt-3">
                {{ $message }}
            </p>
        @enderror

    </div>

    {{-- IDENTITAS --}}
    <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm">

        <div class="mb-5">

            <h3 class="text-lg font-semibold">
                KTP / Kartu Pelajar
            </h3>

            <p class="text-sm text-slate-500 mt-1">
                Upload identitas diri yang masih berlaku.
            </p>

        </div>

        {{-- FILE --}}
        @if ($dokumen && $dokumen->kartu_identitas)

            <a href="{{ asset('storage/' . $dokumen->kartu_identitas) }}"
                target="_blank"
                class="mb-5 flex items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4 hover:bg-slate-100 transition">

                <svg class="w-6 h-6 text-red-500 shrink-0"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">

                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>

                <div>
                    <p class="text-sm font-semibold text-slate-700">
                        Dokumen sudah diupload
                    </p>

                    <p class="text-xs text-slate-500">
                        Klik untuk download / lihat file
                    </p>
                </div>

            </a>

        @endif

        <input
            type="file"
            name="identitas"
            accept=".pdf"
            class="block w-full text-sm
            file:mr-4 file:px-5 file:py-3
            file:border-0 file:rounded-2xl
            file:bg-blue-50 file:text-blue-700
            hover:file:bg-blue-100">

        @error('identitas')
            <p class="text-red-500 text-sm mt-3">
                {{ $message }}
            </p>
        @enderror

    </div>

    {{-- SURAT KETERANGAN --}}
    <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm">

        <div class="mb-5">

            <h3 class="text-lg font-semibold">
                Surat Keterangan Kelas 12 / Lulus
            </h3>

            <p class="text-sm text-slate-500 mt-1 leading-relaxed">
                Untuk siswa kelas akhir atau lulusan tahun berjalan.
                Dokumen wajib terdapat identitas dan cap/stempel resmi sekolah.
                <b>(Jika sudah mendapat ijazah bisa dikosongi)</b>
            </p>

        </div>

        {{-- FILE --}}
        @if ($dokumen && $dokumen->suket)

            <a href="{{ asset('storage/' . $dokumen->suket) }}"
                target="_blank"
                class="mb-5 flex items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4 hover:bg-slate-100 transition">

                <svg class="w-6 h-6 text-red-500 shrink-0"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">

                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>

                <div>
                    <p class="text-sm font-semibold text-slate-700">
                        Dokumen sudah diupload
                    </p>

                    <p class="text-xs text-slate-500">
                        Klik untuk download / lihat file
                    </p>
                </div>

            </a>

        @endif

        <input
            type="file"
            name="surat_keterangan"
            accept=".pdf"
            class="block w-full text-sm
            file:mr-4 file:px-5 file:py-3
            file:border-0 file:rounded-2xl
            file:bg-blue-50 file:text-blue-700
            hover:file:bg-blue-100">

        @error('surat_keterangan')
            <p class="text-red-500 text-sm mt-3">
                {{ $message }}
            </p>
        @enderror

    </div>

    {{-- IJAZAH --}}
    <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm">

        <div class="mb-5">

            <h3 class="text-lg font-semibold">
                Ijazah / SKL
            </h3>

            <p class="text-sm text-slate-500 mt-1">
                Khusus lulusan maksimal 2 tahun terakhir.
                <b>(Jika belum mendapat ijazah bisa dikosongi)</b>
            </p>

        </div>

        {{-- FILE --}}
        @if ($dokumen && $dokumen->ijazah)

            <a href="{{ asset('storage/' . $dokumen->ijazah) }}"
                target="_blank"
                class="mb-5 flex items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4 hover:bg-slate-100 transition">

                <svg class="w-6 h-6 text-red-500 shrink-0"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">

                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>

                <div>
                    <p class="text-sm font-semibold text-slate-700">
                        Dokumen sudah diupload
                    </p>

                    <p class="text-xs text-slate-500">
                        Klik untuk download / lihat file
                    </p>
                </div>

            </a>

        @endif

        <input
            type="file"
            name="ijazah"
            accept=".pdf"
            class="block w-full text-sm
            file:mr-4 file:px-5 file:py-3
            file:border-0 file:rounded-2xl
            file:bg-blue-50 file:text-blue-700
            hover:file:bg-blue-100">

        @error('ijazah')
            <p class="text-red-500 text-sm mt-3">
                {{ $message }}
            </p>
        @enderror

    </div>

</div>

                    {{-- SUBMIT --}}
                    <div class="mt-8 flex justify-end">

                        <button
                            type="submit"
                            class="bg-[#0F4C81] hover:bg-[#0c3c65]
                            text-white px-8 py-3 rounded-2xl
                            font-semibold transition">

                            Simpan Dokumen

                        </button>

                    </div>

                </form>

            </div>

        </main>

    </div>

</body>

</html>