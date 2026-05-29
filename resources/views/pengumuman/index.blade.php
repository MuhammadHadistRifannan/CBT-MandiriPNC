<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengumuman Hasil PMB PNC {{ $year }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-[#F5F8FC] text-slate-800 antialiased">
    <main class="relative min-h-screen overflow-hidden px-4 py-8 sm:px-6 lg:px-8">
        <div class="absolute inset-x-0 top-0 h-72 bg-[#0F4C81]"></div>
        <div class="absolute left-1/2 top-16 h-72 w-72 -translate-x-1/2 rounded-full bg-white/10 blur-3xl"></div>

        <section class="relative mx-auto max-w-4xl">
            <div class="mb-8 flex items-center justify-between text-white">
                <a href="{{ route('home') }}" class="text-sm font-bold hover:underline">Kembali ke Beranda</a>
                <span class="rounded-full bg-white/15 px-4 py-2 text-xs font-black uppercase tracking-[0.22em]">
                    Seleksi Mandiri {{ $year }}
                </span>
            </div>

            <div class="overflow-hidden rounded-[2rem] border border-white/50 bg-white shadow-2xl">
                <div class="border-b border-slate-100 px-6 py-8 text-center sm:px-10">
                    <img src="{{ asset('assets/images/pnc-logo.png') }}" alt="Logo PNC" class="mx-auto h-20 w-20 object-contain">
                    <p class="mt-5 text-xs font-black uppercase tracking-[0.32em] text-[#0F4C81]">Politeknik Negeri Cilacap</p>
                    <h1 class="mt-3 text-3xl font-black tracking-tight text-slate-900 sm:text-4xl">
                        Pengumuman Hasil Seleksi PMB
                    </h1>
                    <p class="mx-auto mt-4 max-w-2xl text-sm font-semibold leading-relaxed text-slate-500">
                        Masukkan nomor peserta untuk melihat hasil akhir seleksi. Halaman ini hanya menampilkan status akhir,
                        bukan nilai mentah CBT atau ranking internal.
                    </p>
                </div>

                <div class="grid gap-0 lg:grid-cols-[1fr_1.1fr]">
                    <aside class="border-b border-slate-100 bg-slate-50 p-6 lg:border-b-0 lg:border-r lg:p-8">
                        <h2 class="text-lg font-black text-slate-800">Informasi Pengumuman</h2>
                        <div class="mt-5 space-y-4">
                            <div class="rounded-2xl bg-white p-4 shadow-sm">
                                <p class="text-xs font-bold uppercase tracking-wide text-slate-400">Tahun Seleksi</p>
                                <p class="mt-1 text-xl font-black text-slate-800">{{ $year }}</p>
                            </div>
                            <div class="rounded-2xl bg-white p-4 shadow-sm">
                                <p class="text-xs font-bold uppercase tracking-wide text-slate-400">Jadwal Dibuka</p>
                                <p class="mt-1 text-sm font-black text-slate-700">
                                    {{ $announcementDate ? $announcementDate->timezone('Asia/Jakarta')->format('d M Y, H:i') . ' WIB' : 'Menunggu jadwal resmi' }}
                                </p>
                            </div>
                            <div class="rounded-2xl bg-white p-4 shadow-sm">
                                <p class="text-xs font-bold uppercase tracking-wide text-slate-400">Status Publikasi</p>
                                <p class="mt-1 text-xl font-black {{ $isPublished ? 'text-emerald-700' : 'text-amber-700' }}">
                                    {{ $isPublished ? 'Sudah Dibuka' : 'Belum Dibuka' }}
                                </p>
                            </div>
                        </div>
                    </aside>

                    <section class="p-6 sm:p-8">
                        <form method="POST" action="{{ route('pengumuman.check') }}" class="space-y-5">
                            @csrf
                            <label class="block">
                                <span class="mb-2 block text-sm font-black text-slate-700">Nomor Peserta</span>
                                <input type="text" name="nomor_peserta" value="{{ old('nomor_peserta', $participantNumber) }}"
                                    placeholder="Contoh: CBT-20260528ABCD"
                                    class="w-full rounded-2xl border-slate-200 px-5 py-4 text-base font-bold uppercase tracking-wide focus:border-[#0F4C81] focus:ring-[#0F4C81]">
                                @error('nomor_peserta')
                                    <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p>
                                @enderror
                            </label>
                            <button type="submit"
                                class="w-full rounded-2xl bg-[#0F4C81] px-6 py-4 text-sm font-black uppercase tracking-widest text-white shadow-lg transition hover:bg-[#0B3A63]">
                                Cek Hasil Seleksi
                            </button>
                        </form>

                        @if($result)
                            <div class="mt-8">
                                @if($result['state'] === 'found')
                                    @php($announcement = $result['announcement'])
                                    <div class="rounded-[1.5rem] border p-6 {{ $announcement->status_hasil->cardClass() }}">
                                        <p class="text-sm font-black uppercase tracking-[0.28em]">{{ $announcement->status_hasil->label() }}</p>
                                        <h2 class="mt-3 text-3xl font-black">
                                            @if($announcement->status_hasil === \App\Enums\AnnouncementResultStatus::Lulus)
                                                SELAMAT!
                                            @else
                                                Tetap Semangat
                                            @endif
                                        </h2>
                                        <p class="mt-3 text-sm font-semibold leading-relaxed">
                                            @if($announcement->status_hasil === \App\Enums\AnnouncementResultStatus::Lulus)
                                                Anda dinyatakan LULUS seleksi PMB PNC {{ $result['participant']['year'] }}.
                                            @else
                                                Mohon maaf, Anda belum dinyatakan lulus pada seleksi PMB ini. Tetap semangat dan sukses selalu.
                                            @endif
                                        </p>
                                    </div>

                                    <div class="mt-5 rounded-[1.5rem] border border-slate-200 bg-white p-5">
                                        <dl class="grid gap-4 sm:grid-cols-2">
                                            <div>
                                                <dt class="text-xs font-bold uppercase tracking-wide text-slate-400">Nama Peserta</dt>
                                                <dd class="mt-1 font-black text-slate-800">{{ $result['participant']['name'] }}</dd>
                                            </div>
                                            <div>
                                                <dt class="text-xs font-bold uppercase tracking-wide text-slate-400">Nomor Peserta</dt>
                                                <dd class="mt-1 font-black text-slate-800">{{ $result['participant']['number'] }}</dd>
                                            </div>
                                            <div>
                                                <dt class="text-xs font-bold uppercase tracking-wide text-slate-400">Program Studi Diterima</dt>
                                                <dd class="mt-1 font-black text-slate-800">{{ $result['participant']['program'] }}</dd>
                                            </div>
                                        </dl>
                                    </div>

                                    @if($announcement->status_hasil === \App\Enums\AnnouncementResultStatus::Lulus)
                                        <div class="mt-5 rounded-[1.5rem] border border-emerald-200 bg-emerald-50 p-5 text-center">
                                            <p class="text-sm font-black text-emerald-800">Silakan scan barcode berikut untuk informasi daftar ulang.</p>
                                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=https%3A%2F%2Fpnc.ac.id"
                                                alt="QR informasi daftar ulang PNC"
                                                class="mx-auto mt-4 h-44 w-44 rounded-2xl border border-emerald-200 bg-white p-3">
                                            <a href="https://pnc.ac.id" target="_blank" rel="noopener"
                                                class="mt-4 inline-flex rounded-2xl bg-emerald-700 px-5 py-3 text-sm font-black text-white">
                                                Buka Informasi Daftar Ulang
                                            </a>
                                        </div>
                                    @endif
                                @else
                                    <div class="rounded-[1.5rem] border border-amber-200 bg-amber-50 p-6 text-amber-800">
                                        <p class="text-sm font-black uppercase tracking-[0.22em]">Informasi</p>
                                        <p class="mt-3 text-base font-bold leading-relaxed">{{ $result['message'] }}</p>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </section>
                </div>
            </div>
        </section>
    </main>
</body>

</html>
