<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'CBT Mandiri PNC') }} - Halaman Ujian</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="select-none bg-slate-100 font-sans text-slate-900 antialiased"
    x-data="examPage()"
    x-init="init()"
    @contextmenu.prevent
    @copy.prevent
    @cut.prevent
    @paste.prevent>
    <div class="min-h-screen">
        <header class="sticky top-0 z-40 border-b border-slate-200 bg-white/95 px-4 py-4 shadow-sm backdrop-blur lg:px-8">
            <div class="mx-auto flex max-w-7xl flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <p class="text-xs font-black uppercase tracking-[0.3em] text-[#0F4C81]">CBT Mandiri PNC</p>
                    <h1 class="mt-1 text-2xl font-black tracking-tight text-slate-900">Halaman Ujian</h1>
                </div>

                <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                        <p class="text-xs font-bold text-slate-500">Kode Ujian</p>
                        <p class="font-black text-slate-800">{{ $ujian->kode_ujian }}</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                        <p class="text-xs font-bold text-slate-500">Progress</p>
                        <p class="font-black text-slate-800"><span x-text="answeredCount"></span>/{{ $totalQuestions }}</p>
                    </div>
                    <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3">
                        <p class="text-xs font-bold text-emerald-700">Timer Server</p>
                        <p class="font-mono text-lg font-black text-emerald-800" x-text="timerLabel"></p>
                    </div>
                    <div class="rounded-2xl border border-blue-200 bg-blue-50 px-4 py-3">
                        <p class="text-xs font-bold text-blue-700">Status</p>
                        <p class="font-black text-blue-800" x-text="statusLabel"></p>
                    </div>
                </div>
            </div>
        </header>

        <main class="mx-auto grid max-w-7xl gap-6 px-4 py-6 lg:grid-cols-[1fr_320px] lg:px-8">
            <section class="rounded-[2rem] border border-slate-200 bg-white p-5 shadow-sm lg:p-8">
                <div class="flex flex-col gap-4 border-b border-slate-100 pb-6 md:flex-row md:items-start md:justify-between">
                    <div>
                        <p class="text-sm font-black uppercase tracking-widest text-[#0F4C81]">
                            Soal {{ $questions->currentPage() }} dari {{ $totalQuestions }}
                        </p>
                        <h2 class="mt-2 text-xl font-black text-slate-900">{{ $question->kode_soal }} - {{ $question->sub_soal }}</h2>
                    </div>
                    <span class="inline-flex rounded-full bg-slate-100 px-4 py-2 text-xs font-black uppercase tracking-widest text-slate-600">
                        Pilihan Ganda
                    </span>
                </div>

                <article class="prose prose-slate mt-8 max-w-none">
                    <p class="whitespace-pre-line text-lg font-semibold leading-relaxed text-slate-800">{{ $question->pertanyaan }}</p>
                </article>

                <div class="mt-8 space-y-4">
                    @foreach ([
                        'A' => $question->opsi_a,
                        'B' => $question->opsi_b,
                        'C' => $question->opsi_c,
                        'D' => $question->opsi_d,
                        'E' => $question->opsi_e,
                    ] as $key => $option)
                        @continue(blank($option))
                        <label class="group flex cursor-pointer items-start gap-4 rounded-2xl border-2 border-slate-100 bg-slate-50 p-4 transition hover:border-blue-200 hover:bg-blue-50"
                            :class="answer === '{{ $key }}' ? 'border-[#0F4C81] bg-blue-50' : ''">
                            <input type="radio" name="jawaban" value="{{ $key }}" x-model="answer" @change="saveAnswer()"
                                class="mt-1 h-5 w-5 border-slate-300 text-[#0F4C81] focus:ring-[#0F4C81]">
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-white text-sm font-black text-slate-700 shadow-sm">
                                {{ $key }}
                            </span>
                            <span class="whitespace-pre-line text-left font-semibold leading-relaxed text-slate-700">{{ $option }}</span>
                        </label>
                    @endforeach
                </div>

                <div class="mt-8 flex flex-col gap-3 border-t border-slate-100 pt-6 sm:flex-row sm:items-center sm:justify-between">
                    <a href="{{ $questions->previousPageUrl() ?? '#' }}"
                        class="rounded-2xl border border-slate-200 px-5 py-3 text-center font-black text-slate-600 transition hover:bg-slate-50 {{ $questions->onFirstPage() ? 'pointer-events-none opacity-40' : '' }}">
                        Sebelumnya
                    </a>

                    <button type="button" @click="saveAnswer()"
                        class="rounded-2xl bg-[#0F4C81] px-6 py-3 font-black text-white shadow-lg transition hover:bg-[#0b3b64]">
                        Simpan Jawaban
                    </button>

                    <a href="{{ $questions->nextPageUrl() ?? '#' }}"
                        class="rounded-2xl border border-slate-200 px-5 py-3 text-center font-black text-slate-600 transition hover:bg-slate-50 {{ $questions->hasMorePages() ? '' : 'pointer-events-none opacity-40' }}">
                        Berikutnya
                    </a>
                </div>
            </section>

            <aside class="space-y-6">
                <section class="rounded-[2rem] border border-slate-200 bg-white p-5 shadow-sm">
                    <h3 class="text-sm font-black uppercase tracking-widest text-slate-500">Status Ujian</h3>
                    <div class="mt-4 space-y-4">
                        <div>
                            <div class="flex items-center justify-between text-xs font-black uppercase tracking-widest text-slate-500">
                                <span>Progress</span>
                                <span x-text="progressPercentage + '%'"></span>
                            </div>
                            <div class="mt-2 h-3 overflow-hidden rounded-full bg-slate-100">
                                <div class="h-full rounded-full bg-[#0F4C81] transition-all" :style="`width: ${progressPercentage}%`"></div>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="rounded-2xl bg-slate-50 p-4">
                                <p class="text-xs font-bold text-slate-500">Terjawab</p>
                                <p class="text-xl font-black text-slate-800"><span x-text="answeredCount"></span>/<span x-text="totalQuestions"></span></p>
                            </div>
                            <div class="rounded-2xl bg-slate-50 p-4">
                                <p class="text-xs font-bold text-slate-500">Autosave</p>
                                <p class="text-sm font-black text-slate-800" x-text="saveStatus"></p>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="rounded-[2rem] border border-slate-200 bg-white p-5 shadow-sm">
                    <h3 class="text-sm font-black uppercase tracking-widest text-slate-500">Navigasi Soal</h3>
                    <div class="mt-4 grid grid-cols-5 gap-2">
                        @for ($page = 1; $page <= $totalQuestions; $page++)
                            @php($navSoalId = $questionIds[$page - 1] ?? null)
                            <a href="{{ route('ujian.show', ['page' => $page]) }}"
                                class="flex h-11 items-center justify-center rounded-xl text-sm font-black transition
                                    {{ $page === $questions->currentPage() ? 'bg-[#0F4C81] text-white' : (filled($answers[$navSoalId] ?? null) ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' : 'bg-slate-100 text-slate-600 hover:bg-slate-200') }}">
                                {{ $page }}
                            </a>
                        @endfor
                    </div>
                    <p class="mt-4 text-xs font-semibold leading-relaxed text-slate-500">
                        Jawaban tersimpan otomatis saat pilihan diganti dan setiap beberapa detik.
                    </p>
                </section>

                <section class="rounded-[2rem] border border-amber-200 bg-amber-50 p-5">
                    <h3 class="font-black text-amber-800">Mode Ujian Aktif</h3>
                    <p class="mt-2 text-sm font-semibold leading-relaxed text-amber-700">
                        Sistem memantau aktivitas halaman. Jika Anda berpindah tab atau jendela, sesi akan dikembalikan ke portal dan aktivitas tercatat.
                    </p>
                </section>

                <section class="rounded-[2rem] border border-slate-200 bg-white p-5 shadow-sm">
                    <h3 class="text-sm font-black uppercase tracking-widest text-slate-500">Submit Ujian</h3>
                    <p class="mt-3 text-sm font-semibold leading-relaxed text-slate-600">
                        Tombol submit final akan aktif pada tahap submit peserta. Untuk saat ini pastikan semua jawaban sudah tersimpan.
                    </p>
                    <button type="button" @click="showSubmitModal = true" :disabled="submitting"
                        class="mt-4 w-full rounded-2xl bg-red-600 px-5 py-3 font-black text-white shadow-lg transition hover:bg-red-700 disabled:cursor-not-allowed disabled:bg-slate-300 disabled:text-slate-500 disabled:shadow-none">
                        Submit Final
                    </button>
                </section>
            </aside>
        </main>

        <div x-show="showSubmitModal" x-transition style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-slate-950/70 backdrop-blur-sm" @click="showSubmitModal = false"></div>
            <section class="relative w-full max-w-lg overflow-hidden rounded-[2rem] bg-white shadow-2xl">
                <div class="bg-red-600 px-8 py-6 text-white">
                    <p class="text-xs font-black uppercase tracking-[0.3em] text-red-100">Konfirmasi Submit</p>
                    <h2 class="mt-2 text-2xl font-black">Akhiri Ujian?</h2>
                </div>
                <div class="space-y-5 p-8">
                    <p class="font-semibold leading-relaxed text-slate-600">
                        Setelah disubmit, jawaban akan dikunci dan Anda tidak dapat membuka halaman pengerjaan ulang.
                    </p>
                    <div class="rounded-2xl bg-slate-50 p-4 text-sm font-bold text-slate-600">
                        Terjawab: <span x-text="answeredCount"></span>/<span x-text="totalQuestions"></span> soal
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <button type="button" @click="showSubmitModal = false"
                            class="rounded-2xl border border-slate-200 px-5 py-3 font-black text-slate-500 transition hover:bg-slate-50">
                            Batal
                        </button>
                        <button type="button" @click="submitExam('manual')" :disabled="submitting"
                            class="rounded-2xl bg-red-600 px-5 py-3 font-black text-white transition hover:bg-red-700 disabled:cursor-not-allowed disabled:bg-slate-300">
                            <span x-text="submitting ? 'Memproses...' : 'Ya, Submit'"></span>
                        </button>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <script>
        function examPage() {
            return {
                answer: @js($answers[$question->id] ?? null),
                saveStatus: 'Siap',
                answeredCount: @js($answeredCount),
                totalQuestions: @js($totalQuestions),
                progressPercentage: @js($totalQuestions > 0 ? (int) floor(($answeredCount / $totalQuestions) * 100) : 0),
                remainingSeconds: @js($remainingSeconds),
                timerLabel: '00:00:00',
                statusLabel: @js($ujian->status->label()),
                saveTimer: null,
                statusTimer: null,
                activityLogged: false,
                showSubmitModal: false,
                submitting: false,
                submitted: false,
                init() {
                    this.updateTimer();
                    setInterval(() => {
                        this.remainingSeconds = Math.max(0, this.remainingSeconds - 1);
                        this.updateTimer();
                    }, 1000);

                    this.saveTimer = setInterval(() => this.saveAnswer(false), 10000);
                    this.statusTimer = setInterval(() => this.loadStatus(), 5000);
                    this.loadStatus();

                    document.addEventListener('visibilitychange', () => {
                        if (document.hidden) {
                            this.forceExit('tab_hidden');
                        }
                    });

                    window.addEventListener('blur', () => this.forceExit('window_blur'));
                    window.addEventListener('keydown', (event) => this.blockShortcut(event));
                },
                updateTimer() {
                    const hours = Math.floor(this.remainingSeconds / 3600).toString().padStart(2, '0');
                    const minutes = Math.floor((this.remainingSeconds % 3600) / 60).toString().padStart(2, '0');
                    const seconds = Math.floor(this.remainingSeconds % 60).toString().padStart(2, '0');
                    this.timerLabel = `${hours}:${minutes}:${seconds}`;

                    if (this.remainingSeconds <= 0) {
                        this.saveStatus = 'Waktu habis';
                        this.submitExam('auto');
                    }
                },
                async saveAnswer(showFeedback = true) {
                    if (this.remainingSeconds <= 0) return;

                    if (showFeedback) this.saveStatus = 'Menyimpan...';

                    try {
                        const response = await fetch(@js(route('ujian.answers.store')), {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                            },
                            body: JSON.stringify({
                                soal_id: @js($question->id),
                                jawaban: this.answer
                            })
                        });

                        if (!response.ok) throw new Error('Autosave gagal.');

                        const payload = await response.json();
                        this.applyStatus(payload);
                        this.saveStatus = 'Tersimpan';
                    } catch (error) {
                        this.saveStatus = 'Gagal simpan';
                    }
                },
                async loadStatus() {
                    try {
                        const response = await fetch(@js(route('ujian.status')), {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });

                        if (!response.ok) throw new Error('Status gagal dimuat.');

                        this.applyStatus(await response.json());
                    } catch (error) {
                        this.saveStatus = 'Status gagal';
                    }
                },
                applyStatus(payload) {
                    this.answeredCount = payload.answered_count;
                    this.totalQuestions = payload.total_soal ?? payload.total_questions;
                    this.progressPercentage = payload.progress_percentage;
                    this.remainingSeconds = payload.remaining_time ?? payload.remaining_seconds;
                    this.statusLabel = payload.status_label;

                    if (payload.submitted) {
                        this.submitted = true;
                        window.location.href = @js(route('portal.ujian'));
                    }
                },
                async submitExam(submitType = 'manual') {
                    if (this.submitting || this.submitted) return;

                    this.submitting = true;
                    this.saveStatus = submitType === 'auto' ? 'Auto submit...' : 'Submit...';

                    try {
                        const response = await fetch(@js(route('ujian.submit')), {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                            },
                            body: JSON.stringify({ submit_type: submitType })
                        });

                        if (!response.ok) throw new Error('Submit gagal.');

                        this.applyStatus(await response.json());
                        this.submitted = true;
                        window.location.href = @js(route('portal.ujian'));
                    } catch (error) {
                        this.submitting = false;
                        this.saveStatus = 'Submit gagal';
                    }
                },
                async forceExit(eventType) {
                    if (this.activityLogged) return;
                    this.activityLogged = true;

                    try {
                        await fetch(@js(route('participant.activity.store')), {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                            },
                            body: JSON.stringify({ event_type: eventType })
                        });
                    } finally {
                        window.location.href = @js(route('portal.ujian'));
                    }
                },
                blockShortcut(event) {
                    const key = event.key.toLowerCase();
                    const blocked = ['c', 'p', 's', 'u'];

                    if (event.key === 'PrintScreen' || ((event.ctrlKey || event.metaKey) && blocked.includes(key))) {
                        event.preventDefault();
                        this.saveStatus = 'Aksi diblokir';
                    }
                }
            }
        }
    </script>
</body>

</html>
