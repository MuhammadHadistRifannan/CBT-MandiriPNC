<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'CBT Mandiri PNC') }} - Program Studi</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-[#F5F7FA] text-slate-800 antialiased">
    <div
        x-data="{
            sidebarOpen: false,
            modalOpen: {{ $errors->any() ? 'true' : 'false' }},
            method: @js($formState['method']),
            editingId: @js($formState['editing_id']),
            formAction: @js($formState['action']),
            form: {
                nama_prodi: @js(old('nama_prodi', '')),
                tingkat: @js(old('tingkat', 'd4')),
                jurusan: @js(old('jurusan', '')),
                peminat: @js(old('peminat', 0)),
                daya_tampung: @js(old('daya_tampung', '')),
                keketatan_persen: @js(old('keketatan_persen', ''))
            },
            add() {
                this.method = 'POST';
                this.editingId = null;
                this.formAction = @js(route('admin.prodi.store'));
                this.form = {
                    nama_prodi: '',
                    tingkat: 'd4',
                    jurusan: '',
                    peminat: 0,
                    daya_tampung: '',
                    keketatan_persen: ''
                };
                this.modalOpen = true;
            },
            edit(prodi, action) {
                this.method = 'PUT';
                this.editingId = prodi.id;
                this.formAction = action;
                this.form = prodi;
                this.modalOpen = true;
            }
        }"
        class="flex h-screen overflow-hidden">

        @include('layouts.admin.sidebar')

        <div class="flex-1 flex flex-col overflow-hidden">
            @include('layouts.admin.mobile-header', ['title' => 'Program Studi'])

            <main class="flex-1 overflow-y-auto bg-[#F8FAFC] p-4 lg:p-8">
                <div class="mb-8 flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <h1 class="text-2xl font-black text-slate-800 lg:text-3xl">Program Studi</h1>
                        <p class="mt-1 text-slate-500">Kelola program studi dan daya tampung PMB.</p>
                    </div>

                    <button type="button" @click="add()"
                        class="inline-flex items-center justify-center gap-2 rounded-2xl bg-[#0F4C81] px-6 py-3 font-semibold text-white shadow-lg shadow-blue-900/10 transition hover:bg-[#0B3A63]">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Prodi
                    </button>
                </div>

                @if (session('success'))
                    <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-700">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-semibold text-red-700">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="mb-8 grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">
                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                        <p class="text-sm font-medium text-slate-500">Total Prodi</p>
                        <h2 class="mt-2 text-3xl font-black text-slate-800">{{ number_format($stats['total']) }}</h2>
                        <p class="mt-4 text-sm text-slate-500">Tersedia pada PMB</p>
                    </div>

                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                        <p class="text-sm font-medium text-slate-500">Total Daya Tampung</p>
                        <h2 class="mt-2 text-3xl font-black text-slate-800">{{ number_format($stats['daya_tampung']) }}</h2>
                        <p class="mt-4 text-sm text-slate-500">Kursi penerimaan</p>
                    </div>

                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                        <p class="text-sm font-medium text-slate-500">Peminat Terbanyak</p>
                        <h2 class="mt-2 truncate text-lg font-black text-slate-800">
                            {{ $stats['favorite'] ?? '-' }}
                        </h2>
                        <p class="mt-4 text-sm text-slate-500">Berdasarkan data peminat</p>
                    </div>

                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                        <p class="text-sm font-medium text-slate-500">Keketatan Tertinggi</p>
                        <h2 class="mt-2 text-3xl font-black text-slate-800">
                            {{ $stats['tightest'] !== null ? number_format((float) $stats['tightest'] * 100, 0) . '%' : '-' }}
                        </h2>
                        <p class="mt-4 text-sm text-slate-500">Persentase persaingan</p>
                    </div>
                </div>

                <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-100 p-6">
                        <div class="mb-5">
                            <h2 class="text-xl font-black text-slate-800">Data Program Studi</h2>
                            <p class="mt-1 text-sm text-slate-500">Daftar program studi yang tersedia untuk pilihan peserta.</p>
                        </div>

                        <form method="GET" action="{{ route('admin.prodi') }}"
                            class="grid grid-cols-1 gap-3 md:grid-cols-[minmax(260px,1fr)_160px_230px_auto_auto]">
                            <div class="relative">
                                <input type="search" name="search" value="{{ request('search') }}"
                                    placeholder="Cari program studi atau jurusan..."
                                    class="w-full rounded-2xl border border-slate-200 bg-white py-3 pl-12 pr-4 text-sm focus:border-[#0F4C81] focus:outline-none focus:ring-2 focus:ring-[#0F4C81]/20">
                                <svg class="absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-4.35-4.35M11 18a7 7 0 100-14 7 7 0 000 14z" />
                                </svg>
                            </div>

                            <select name="tingkat"
                                class="rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#0F4C81]/20">
                                <option value="">Semua Jenjang</option>
                                @foreach (\App\Models\Prodi::TINGKAT as $tingkat)
                                    <option value="{{ $tingkat }}" @selected(request('tingkat') === $tingkat)>
                                        {{ strtoupper($tingkat) }}
                                    </option>
                                @endforeach
                            </select>

                            <select name="jurusan"
                                class="rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#0F4C81]/20">
                                <option value="">Semua Jurusan</option>
                                @foreach ($jurusanOptions as $jurusan)
                                    <option value="{{ $jurusan }}" @selected(request('jurusan') === $jurusan)>
                                        {{ $jurusan }}
                                    </option>
                                @endforeach
                            </select>

                            <button type="submit"
                                class="rounded-2xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-700">
                                Filter
                            </button>

                            <a href="{{ route('admin.prodi') }}"
                                class="inline-flex items-center justify-center rounded-2xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                                Reset
                            </a>
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[920px]">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Program Studi</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Jenjang</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Daya Tampung</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Peminat</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Keketatan</th>
                                    <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider text-slate-500">Aksi</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-slate-100">
                                @forelse ($prodis as $prodi)
                                    <tr class="transition hover:bg-slate-50">
                                        <td class="px-6 py-5">
                                            <p class="font-bold text-slate-800">{{ $prodi->nama_prodi }}</p>
                                            <p class="mt-1 text-sm text-slate-500">{{ $prodi->jurusan }}</p>
                                        </td>
                                        <td class="px-6 py-5">
                                            <span class="rounded-xl bg-blue-100 px-3 py-1 text-xs font-bold text-[#0F4C81]">
                                                {{ strtoupper($prodi->tingkat) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-5 text-sm font-semibold text-slate-700">
                                            {{ number_format($prodi->daya_tampung) }}
                                        </td>
                                        <td class="px-6 py-5 text-sm font-semibold text-slate-700">
                                            {{ number_format($prodi->peminat) }}
                                        </td>
                                        <td class="px-6 py-5">
                                            <span class="rounded-xl bg-red-100 px-3 py-1 text-xs font-bold text-red-600">
                                                {{ number_format((float) $prodi->keketatan * 100, 0) }}%
                                            </span>
                                        </td>
                                        <td class="px-6 py-5">
                                            <div class="flex items-center justify-center gap-2">
                                                <button type="button"
                                                    @click="edit(@js([
                                                        'id' => $prodi->id,
                                                        'nama_prodi' => $prodi->nama_prodi,
                                                        'tingkat' => $prodi->tingkat,
                                                        'jurusan' => $prodi->jurusan,
                                                        'peminat' => $prodi->peminat,
                                                        'daya_tampung' => $prodi->daya_tampung,
                                                        'keketatan_persen' => round((float) $prodi->keketatan * 100, 2),
                                                    ]), @js(route('admin.prodi.update', $prodi)))"
                                                    class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-100 transition hover:bg-amber-200"
                                                    aria-label="Edit {{ $prodi->nama_prodi }}">
                                                    <svg class="h-5 w-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M15.232 5.232l3.536 3.536M9 17l-4 1 1-4L16.5 3.5a2.121 2.121 0 013 3L9 17z" />
                                                    </svg>
                                                </button>

                                                <form method="POST" action="{{ route('admin.prodi.destroy', $prodi) }}"
                                                    onsubmit="return confirm('Hapus program studi {{ $prodi->nama_prodi }}?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="flex h-10 w-10 items-center justify-center rounded-xl bg-red-100 transition hover:bg-red-200"
                                                        aria-label="Hapus {{ $prodi->nama_prodi }}">
                                                        <svg class="h-5 w-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M6 7h12M9 7V4h6v3m-7 4v6m4-6v6m4-6v6" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-14 text-center text-slate-500">
                                            Belum ada program studi yang sesuai dengan filter.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="flex flex-col gap-4 border-t border-slate-100 px-6 py-5 md:flex-row md:items-center md:justify-between">
                        <p class="text-sm text-slate-500">
                            Menampilkan {{ $prodis->firstItem() ?? 0 }} - {{ $prodis->lastItem() ?? 0 }} dari {{ $prodis->total() }} program studi
                        </p>
                        {{ $prodis->links() }}
                    </div>
                </section>
            </main>
        </div>

        <div x-show="modalOpen" x-cloak x-transition.opacity class="fixed inset-0 z-[9999]">
            <div @click="modalOpen = false" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>

            <div class="relative flex min-h-screen items-center justify-center p-4">
                <form method="POST" :action="formAction" @click.stop
                    class="max-h-[92vh] w-full max-w-2xl overflow-y-auto rounded-3xl bg-white shadow-2xl">
                    @csrf
                    <input type="hidden" name="_editing_id" :value="editingId">
                    <template x-if="method === 'PUT'">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <div class="flex items-start justify-between gap-4 border-b border-slate-200 px-6 py-5">
                        <div>
                            <h2 class="text-xl font-black text-slate-900"
                                x-text="method === 'PUT' ? 'Edit Program Studi' : 'Tambah Program Studi'"></h2>
                            <p class="mt-1 text-sm text-slate-500">Isi informasi prodi yang akan tampil pada halaman peserta.</p>
                        </div>
                        <button type="button" @click="modalOpen = false"
                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl text-slate-500 transition hover:bg-slate-100">
                            <span class="text-2xl leading-none">&times;</span>
                        </button>
                    </div>

                    <div class="grid grid-cols-1 gap-5 p-6 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <label class="mb-2 block text-sm font-semibold text-slate-700">Nama Program Studi</label>
                            <input type="text" name="nama_prodi" x-model="form.nama_prodi" required
                                class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:border-[#0F4C81] focus:outline-none focus:ring-2 focus:ring-[#0F4C81]/20">
                            @error('nama_prodi')
                                <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">Jenjang</label>
                            <select name="tingkat" x-model="form.tingkat" required
                                class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:border-[#0F4C81] focus:outline-none focus:ring-2 focus:ring-[#0F4C81]/20">
                                @foreach (\App\Models\Prodi::TINGKAT as $tingkat)
                                    <option value="{{ $tingkat }}">{{ strtoupper($tingkat) }}</option>
                                @endforeach
                            </select>
                            @error('tingkat')
                                <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">Jurusan</label>
                            <input type="text" name="jurusan" x-model="form.jurusan" required
                                class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:border-[#0F4C81] focus:outline-none focus:ring-2 focus:ring-[#0F4C81]/20">
                            @error('jurusan')
                                <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">Daya Tampung</label>
                            <input type="number" name="daya_tampung" x-model="form.daya_tampung" min="1" required
                                class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:border-[#0F4C81] focus:outline-none focus:ring-2 focus:ring-[#0F4C81]/20">
                            @error('daya_tampung')
                                <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">Jumlah Peminat</label>
                            <input type="number" name="peminat" x-model="form.peminat" min="0" required
                                class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:border-[#0F4C81] focus:outline-none focus:ring-2 focus:ring-[#0F4C81]/20">
                            @error('peminat')
                                <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label class="mb-2 block text-sm font-semibold text-slate-700">Keketatan (%)</label>
                            <input type="number" name="keketatan_persen" x-model="form.keketatan_persen"
                                min="0" max="100" step="0.01" required
                                class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:border-[#0F4C81] focus:outline-none focus:ring-2 focus:ring-[#0F4C81]/20">
                            <p class="mt-2 text-xs text-slate-500">Masukkan persen, misalnya 42 untuk disimpan sebagai nilai desimal 0.42.</p>
                            @error('keketatan_persen')
                                <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 border-t border-slate-200 px-6 py-4">
                        <button type="button" @click="modalOpen = false"
                            class="rounded-2xl border border-slate-300 px-5 py-3 font-semibold text-slate-700 transition hover:bg-slate-100">
                            Batal
                        </button>
                        <button type="submit"
                            class="rounded-2xl bg-[#0F4C81] px-5 py-3 font-semibold text-white transition hover:bg-[#0B3A63]"
                            x-text="method === 'PUT' ? 'Simpan Perubahan' : 'Tambah Prodi'"></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
