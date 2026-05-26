<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'CBT Mandiri PNC') }} - Pilih Prodi</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased text-gray-900 bg-[#F8FAFC]">
    <div x-data='{ 
        openModal: false,
        agree: false,
        sidebarExpanded: true, 
        pilihan1: 1, 
        pilihan2: 1, 
        listProdi: @json($data['prodis']) 
    }' class="flex h-screen overflow-hidden">

        @include('layouts.dashboard.sidebar')

        <div class="flex-1 flex flex-col overflow-hidden relative">

            {{-- HEADER --}}
            <header class="sticky top-0 z-30 bg-white border-b border-slate-200 px-4 lg:px-8 py-4">

                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

                    {{-- LEFT --}}
                    <div class="flex items-start sm:items-center gap-4">

                        {{-- MOBILE MENU --}}
                        <button @click="mobileOpen = true"
                            class="lg:hidden flex items-center justify-center w-11 h-11 rounded-xl bg-[#0F4C81] text-white shrink-0">

                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>

                        {{-- ICON --}}
                        <div
                            class="hidden sm:flex w-14 h-14 rounded-2xl bg-[#0F4C81] text-white items-center justify-center shadow-sm shrink-0">

                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10m-11 9h12a2 2 0 002-2V7a2 2 0 00-2-2H6a2 2 0 00-2 2v11a2 2 0 002 2z" />
                            </svg>
                        </div>

                        {{-- TITLE --}}
                        <div class="min-w-0">

                            <p class="text-xs sm:text-sm font-medium text-[#0F4C81] uppercase tracking-wide">
                                Seleksi Mandiri PNC
                            </p>

                            <h1
                                class="text-xl sm:text-2xl lg:text-3xl font-bold text-slate-800 tracking-tight leading-tight">

                                Pemilihan Program Studi
                            </h1>

                            <p class="hidden sm:block text-sm text-slate-500 mt-1 max-w-2xl">
                                Pilih program studi utama dan cadangan sesuai minat dan strategi seleksi Anda.
                            </p>
                        </div>
                    </div>

                    {{-- RIGHT --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 w-full lg:w-auto">

                        {{-- STEP --}}
                        <div class="bg-slate-100 border border-slate-200 rounded-2xl px-4 py-3">

                            <p class="text-xs text-slate-500">
                                Tahapan
                            </p>

                            <h3 class="text-sm font-semibold text-slate-800">
                                Pilih Program Studi
                            </h3>
                        </div>

                        {{-- STATUS --}}
                        <div class="bg-emerald-50 border border-emerald-200 rounded-2xl px-4 py-3">

                            <p class="text-xs text-emerald-600">
                                Status
                            </p>

                            <h3 class="text-sm font-semibold text-emerald-700">
                                Pendaftaran Aktif
                            </h3>
                        </div>

                    </div>
                </div>
            </header>

            {{-- WRAP CONTENT --}}
            <main class="flex-1 overflow-y-auto bg-slate-50 p-4 lg:p-8">

                <div class="max-w-6xl mx-auto space-y-6">

                    {{-- PAGE HEADER --}}
                    <div class="flex flex-col gap-2">



                        <h1 class="text-2xl lg:text-3xl font-bold text-slate-800">
                            Pemilihan Program Studi
                        </h1>

                        <p class="text-sm text-slate-500 max-w-2xl">
                            Pilih program studi utama dan cadangan sesuai minat serta strategi seleksi Anda.
                        </p>
                    </div>

                    @if($data['isSave'])
                       @php
    // SAFE GUARD: billing bisa null
    $billing = $data['billing'] ?? null;

    // SAFE BOOLEAN: isPay bisa null / 0 / 1
    $isPay = $billing?->isPay ?? false;

    // SNAP TOKEN SAFE
    $snapToken = session('snap_token');

    // BUTTON STATE
    $canPay = $billing && !$isPay;
@endphp

<div class="bg-green-50 border border-green-200 text-green-700 rounded-3xl p-5">

    <h3 class="font-bold mb-3">
        Pilihan Program Studi Telah Dikunci
    </h3>

    {{-- STATUS BADGE --}}
    <div class="mb-3">
        @if(!$billing)
            <span id="keterangan" class="inline-block bg-red-500 text-white text-xs px-3 py-1 rounded-full">
                Data Pembayaran Tidak Ditemukan
            </span>

        @elseif($isPay)
            <span id="keterangan" class="inline-block bg-green-600 text-white text-xs px-3 py-1 rounded-full">
                Sudah Dibayar
            </span>

        @else
            <span id="keterangan" class="inline-block bg-yellow-500 text-white text-xs px-3 py-1 rounded-full">
                Menunggu Pembayaran
            </span>
        @endif
    </div>

    <p class="text-sm mb-2">
        Anda sudah melakukan simpan permanen pilihan program studi dan data tidak dapat diubah kembali.
    </p>

    <p class="text-md font-bold mb-1">
        @if($isPay)
            Terima kasih, pembayaran Anda sudah diterima.
        @else
            Silakan lakukan pembayaran untuk melanjutkan.
        @endif
    </p>

    <p class="text-sm">
        Kode Pembayaran:
        <span class="font-semibold">
            {{ $billing?->kode_bayar ?? '-' }}
        </span>
    </p>

    {{-- BUTTON --}}
    <div class="mt-5">
        <button
            id="btn-bayar"
            type="button"
            onclick="payNow()"
            @if(!$canPay) disabled @endif
            class="w-full sm:w-auto bg-[#0F4C81] text-white px-6 py-3 rounded-2xl font-semibold transition
            @if(!$canPay) opacity-50 cursor-not-allowed @endif"
        >
            @if(!$billing)
                Data Tidak Tersedia
            @elseif($isPay)
                Sudah Dibayar
            @else
                Bayar Sekarang
            @endif
        </button>
    </div>

</div>
                            

        </div>
                    @else

                                    {{-- CARD --}}
                                    <form action="{{ route('prodi.simpan') }}" method="POST"
                                        class="bg-white border border-slate-200 rounded-3xl shadow-sm overflow-hidden">
                                        @csrf

                                        <div class="p-5 lg:p-8 space-y-8">

                                            {{-- SELECT PRODI --}}
                                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                                                {{-- PILIHAN 1 --}}
                                                <div class="space-y-3">

                                                    <label class="flex items-center gap-3 text-sm font-semibold text-slate-700">

                                                        <div
                                                            class="w-8 h-8 rounded-xl bg-[#0F4C81] text-white flex items-center justify-center">
                                                            1
                                                        </div>

                                                        Pilihan Prodi Utama
                                                    </label>

                                                    <div class="relative">

                                                        <select name="pilihan_1" x-model="pilihan1"
                                                            class="w-full rounded-2xl border border-slate-300 bg-white px-5 py-4 text-sm font-medium focus:ring-2 focus:ring-[#0F4C81] focus:border-[#0F4C81]">

                                                            <template x-for="data in listProdi" :key="data.id">
                                                                <option :value="data.id"
                                                                    x-text="`${data.tingkat} - ${data.nama_prodi}`"></option>
                                                            </template>

                                                        </select>
                                                    </div>
                                                </div>

                                                {{-- PILIHAN 2 --}}
                                                <div class="space-y-3">

                                                    <label class="flex items-center gap-3 text-sm font-semibold text-slate-700">

                                                        <div
                                                            class="w-8 h-8 rounded-xl bg-slate-500 text-white flex items-center justify-center">
                                                            2
                                                        </div>

                                                        Pilihan Prodi Cadangan
                                                    </label>

                                                    <div class="relative">

                                                        <select name="pilihan_2" x-model="pilihan2"
                                                            class="w-full rounded-2xl border border-slate-300 bg-white px-5 py-4 text-sm font-medium focus:ring-2 focus:ring-[#0F4C81] focus:border-[#0F4C81]">

                                                            <template x-for="data in listProdi" :key="data.id">
                                                                <option :value="data.id"
                                                                    x-text="`${data.tingkat} - ${data.nama_prodi}`"></option>
                                                            </template>

                                                        </select>
                                                    </div>
                                                </div>

                                            </div>

                                            {{-- ANALISIS --}}
                                            <div class="space-y-4">

                                                <div>
                                                    <h2 class="text-lg font-bold text-slate-800">
                                                        Analisis Keketatan
                                                    </h2>

                                                    <p class="text-sm text-slate-500">
                                                        Perbandingan jumlah peminat dan daya tampung.
                                                    </p>
                                                </div>

                                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

                                                    {{-- CARD 1 --}}
                                                    <div class="border border-slate-200 rounded-3xl p-6 bg-slate-50">

                                                        <div class="flex items-center justify-between mb-5">

                                                            <div>
                                                                <h3 class="font-bold text-slate-800"
                                                                    x-text="listProdi.find(p => p.id == pilihan1)?.nama_prodi"></h3>

                                                                <p class="text-sm text-slate-500">
                                                                    Pilihan Utama
                                                                </p>
                                                            </div>

                                                            <span class="bg-[#0F4C81] text-white text-xs px-3 py-1 rounded-full">
                                                                Pilihan 1
                                                            </span>
                                                        </div>

                                                        <div class="grid grid-cols-2 gap-4 mb-5">

                                                            <div class="bg-white rounded-2xl p-4 border border-slate-200">
                                                                <p class="text-xs text-slate-500">
                                                                    Peminat
                                                                </p>

                                                                <h4 class="text-2xl font-bold text-slate-800"
                                                                    x-text="listProdi.find(p => p.id == pilihan1)?.peminat"></h4>
                                                            </div>

                                                            <div class="bg-white rounded-2xl p-4 border border-slate-200">
                                                                <p class="text-xs text-slate-500">
                                                                    Daya Tampung
                                                                </p>

                                                                <h4 class="text-2xl font-bold text-slate-800"
                                                                    x-text="listProdi.find(p => p.id == pilihan1)?.daya_tampung"></h4>
                                                            </div>
                                                        </div>

                                                        <div class="space-y-2">

                                                            <div class="flex justify-between text-sm">
                                                                <span class="text-slate-500">
                                                                    Rasio
                                                                </span>

                                                                <span class="font-semibold text-[#0F4C81]"
                                                                    x-text="listProdi.find(p => p.id == pilihan1)?.keketatan * 100 + '%'"></span>
                                                            </div>

                                                            <div class="w-full h-3 bg-slate-200 rounded-full overflow-hidden">

                                                                <div class="h-full bg-[#0F4C81] rounded-full transition-all duration-500"
                                                                    :style="`width:${listProdi.find(p => p.id == pilihan1)?.keketatan * 100}%`">
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>

                                                    {{-- CARD 2 --}}
                                                    <div class="border border-slate-200 rounded-3xl p-6 bg-slate-50">

                                                        <div class="flex items-center justify-between mb-5">

                                                            <div>
                                                                <h3 class="font-bold text-slate-800" x-text="listProdi[pilihan2].nama">
                                                                </h3>

                                                                <p class="text-sm text-slate-500">
                                                                    Pilihan Cadangan
                                                                </p>
                                                            </div>

                                                            <span class="bg-slate-600 text-white text-xs px-3 py-1 rounded-full">
                                                                Pilihan 2
                                                            </span>
                                                        </div>

                                                        <div class="grid grid-cols-2 gap-4 mb-5">

                                                            <div class="bg-white rounded-2xl p-4 border border-slate-200">
                                                                <p class="text-xs text-slate-500">
                                                                    Peminat
                                                                </p>

                                                                <h4 class="text-2xl font-bold text-slate-800"
                                                                    x-text="listProdi.find(p => p.id == pilihan2)?.peminat"></h4>
                                                            </div>

                                                            <div class="bg-white rounded-2xl p-4 border border-slate-200">
                                                                <p class="text-xs text-slate-500">
                                                                    Daya Tampung
                                                                </p>

                                                                <h4 class="text-2xl font-bold text-slate-800"
                                                                    x-text="listProdi.find(p => p.id == pilihan2).daya_tampung"></h4>
                                                            </div>
                                                        </div>

                                                        <div class="space-y-2">

                                                            <div class="flex justify-between text-sm">
                                                                <span class="text-slate-500">
                                                                    Rasio
                                                                </span>

                                                                <span class="font-semibold text-slate-700"
                                                                    x-text="listProdi.find(p => p.id == pilihan2).keketatan * 100 + '%'"></span>
                                                            </div>

                                                            <div class="w-full h-3 bg-slate-200 rounded-full overflow-hidden">

                                                                <div class="h-full bg-slate-600 rounded-full transition-all duration-500"
                                                                    :style="`width:${listProdi.find(p => p.id == pilihan2)?.keketatan * 100}%`">
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>



                                                @error('pilihan_1')
                                                    <div class="bg-amber-50 border border-amber-200 text-amber-700 rounded-2xl p-4 text-sm">
                                                        {{ $message }}
                                                    </div>
                                                @enderror


                                            </div>

                                            {{-- AGREEMENT --}}
                                            <div class="bg-slate-50 border border-slate-200 rounded-3xl p-5 lg:p-6">

                                                <label class="flex items-start gap-4">

                                                    <input type="checkbox" required x-model="agree"
                                                        class="mt-1 w-5 h-5 rounded border-slate-300 text-[#0F4C81] focus:ring-[#0F4C81]">

                                                    <div>

                                                        <h3 class="font-semibold text-slate-800 mb-2">
                                                            Pernyataan Persetujuan
                                                        </h3>

                                                        <p class="text-sm text-slate-600 leading-relaxed">
                                                            Saya menyatakan data pilihan program studi sudah benar dan tidak dapat
                                                            diubah kembali setelah disimpan.
                                                        </p>
                                                    </div>
                                                </label>
                                            </div>

                                            {{-- BUTTON --}}
                                            {{-- BUTTON --}}
                                            <div class="flex flex-col items-center gap-3 pt-2">

                                                <button type="button" @click="if(agree){ openModal = true }" :class="agree 
                        ? 'bg-[#0F4C81] hover:bg-[#0B3B63] cursor-pointer' 
                        : 'bg-slate-300 cursor-not-allowed'"
                                                    class="w-full sm:w-auto text-white px-10 py-4 rounded-2xl font-semibold transition">

                                                    Simpan Pilihan Prodi
                                                </button>

                                                <p class="text-xs text-slate-500 text-center">
                                                    Pastikan pilihan sudah benar sebelum menyimpan.
                                                </p>
                                                <p x-show="!agree" class="text-sm text-red-500 text-center">
                                                    Anda harus menyetujui pernyataan terlebih dahulu.
                                                </p>
                                            </div>

                                            {{-- MODAL --}}
                                            <div x-show="openModal" x-transition.opacity
                                                class="fixed inset-0 z-[999] flex items-center justify-center bg-black/50 p-4">

                                                <div x-show="openModal" x-transition
                                                    class="w-full max-w-md bg-white rounded-3xl shadow-2xl overflow-hidden">

                                                    {{-- HEADER --}}
                                                    <div class="p-6 border-b border-slate-200">

                                                        <div class="flex items-center gap-4">

                                                            <div
                                                                class="w-14 h-14 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center">

                                                                <svg class="w-7 h-7" fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">

                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M12 9v2m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.34 16c-.77 1.33.19 3 1.73 3z" />
                                                                </svg>
                                                            </div>

                                                            <div>
                                                                <h2 class="text-xl font-bold text-slate-800">
                                                                    Konfirmasi Pilihan
                                                                </h2>

                                                                <p class="text-sm text-slate-500">
                                                                    Pastikan data sudah benar.
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    {{-- BODY --}}
                                                    <div class="p-6 space-y-5">

                                                        <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4">

                                                            <div class="space-y-4">

                                                                <div>
                                                                    <p class="text-xs text-slate-500 mb-1">
                                                                        Pilihan Prodi 1
                                                                    </p>

                                                                    <h3 class="font-semibold text-slate-800"
                                                                        x-text="listProdi.find(p => p.id == pilihan1)?.nama_prodi">
                                                                    </h3>
                                                                </div>

                                                                <div>
                                                                    <p class="text-xs text-slate-500 mb-1">
                                                                        Pilihan Prodi 2
                                                                    </p>

                                                                    <h3 class="font-semibold text-slate-800"
                                                                        x-text="listProdi.find(p => p.id == pilihan2)?.nama_prodi">
                                                                    </h3>
                                                                </div>

                                                            </div>
                                                        </div>

                                                        <div
                                                            class="bg-amber-50 border border-amber-200 rounded-2xl p-4 text-sm text-amber-700">

                                                            Setelah disimpan, pilihan prodi tidak dapat diubah kembali.
                                                        </div>
                                                    </div>

                                                    {{-- FOOTER --}}
                                                    <div class="flex flex-col sm:flex-row gap-3 p-6 border-t border-slate-200">

                                                        <button type="button" @click="openModal = false"
                                                            class="w-full border border-slate-300 hover:bg-slate-100 text-slate-700 py-3 rounded-2xl font-medium transition">

                                                            Batal
                                                        </button>

                                                        <button type="submit"
                                                            class="w-full bg-[#0F4C81] hover:bg-[#0B3B63] text-white py-3 rounded-2xl font-semibold transition">

                                                            Ya, Simpan
                                                        </button>
                                                    </div>
                    @endif

                                </div>
                            </div>

                        </div>
                    </form>

                </div>

            </main>

        </div>
    </div>

    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
data-client-key="{{ config('services.midtrans.client_key') }}"></script>

<script>
async function payNow() {
    let snapToken = "{{ session('snap_token') }}";
    if (!snapToken){
            const response = await fetch(@js(route('payment.snap')), {
                method:"POST",
                headers: {
                    "Content-Type" : "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
            }); 

            const data = await response.json();
            snapToken = data.snap_token;

    }

    if (!snapToken) {
        alert("Snap token tidak ditemukan");
        return;
    }

    snap.pay(snapToken, {
        onSuccess: function(result) {
            console.log("success", result);
            window.location.reload();
        },
        onPending: function(result) {
            console.log("pending", result);
        },
        onError: function(result) {
            console.log("error", result);
        },
        onClose: function() {
            console.log("popup closed");
        }
    });
}
</script>

</body>

</html>
