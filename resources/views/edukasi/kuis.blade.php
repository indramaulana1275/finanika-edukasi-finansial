<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Kuis Profil Risiko') }}
        </h2>
    </x-slot>

    <div class="relative min-h-screen py-8 overflow-hidden"
         x-data="{
            phase: @isset($quiz_result) ? 'result' : (@$existing ? 'result' : 'input'),
            nama: @isset($existing) ? {{ json_encode($existing->nama) }} : (@isset(old('nama')) ? {{ json_encode(old('nama')) }} : ''),
            nim: @isset($existing) ? {{ json_encode($existing->nim) }} : (@isset(old('nim')) ? {{ json_encode(old('nim')) }} : ''),
            currentQuestion: 0,
            answers: {},
            skor: @isset($quiz_result) ? {{ $quiz_result['skor'] }} : 0,
            maxSkor: 20,
            level: @isset($quiz_result) ? {{ $quiz_result['level'] }} : 0,
            tipe: @isset($quiz_result) ? {{ json_encode($quiz_result['tipe']) }} : '',
            soal: @json($soal ?? []),

            startQuiz() {
                this.phase = 'quiz';
                this.currentQuestion = 0;
                this.answers = {};
            },

            selectAnswer(score) {
                this.answers[this.currentQuestion] = score;
                if (this.currentQuestion < this.soal.length - 1) {
                    this.currentQuestion++;
                } else {
                    this.finishQuiz();
                }
            },

            finishQuiz() {
                let total = 0;
                Object.values(this.answers).forEach(s => total += parseInt(s));
                this.skor = total;

                if (total <= 4) { this.level = 1; this.tipe = 'Sangat Konservatif'; }
                else if (total <= 8) { this.level = 2; this.tipe = 'Konservatif'; }
                else if (total <= 12) { this.level = 3; this.tipe = 'Moderat'; }
                else if (total <= 16) { this.level = 4; this.tipe = 'Agresif'; }
                else { this.level = 5; this.tipe = 'Sangat Agresif'; }

                this.phase = 'result';
            },

            resetQuiz() {
                this.phase = 'input';
                this.currentQuestion = 0;
                this.answers = {};
                this.skor = 0;
                this.level = 0;
                this.tipe = '';
            },

            allocText() {
                const alloc = {
                    1: 'Tabungan, deposito, obligasi pemerintah, emas.',
                    2: 'Reksadana pendapatan tetap, emas, sedikit saham blue chip.',
                    3: 'Saham blue chip, reksadana campuran, emas, sedikit kripto.',
                    4: 'Saham pertumbuhan, kripto, reksadana saham, sedikit emas.',
                    5: 'Kripto, saham small cap, forex, aset spekulatif.',
                };
                return alloc[this.level] ?? '';
            },
         }">
        <div class="relative max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- PHASE: INPUT (Nama + NIM) --}}
            <div x-show="phase === 'input'" x-transition>
                {{-- Penjelasan 5 Level --}}
                <div class="rounded-2xl p-6 bg-white/[0.03] backdrop-blur-xl border border-white/10">
                    <h3 class="text-base font-bold text-white mb-4 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-purple-400"></span>
                        5 Level Profil Risiko
                    </h3>
                    <p class="text-sm text-gray-400 mb-6">
                        Pilih level yang paling mencerminkan diri kamu. Kuis akan menentukan level secara otomatis berdasarkan 10 soal.
                    </p>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="p-4 rounded-xl border border-purple-500/30 bg-purple-500/5">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="text-xl">1</span>
                                <span class="font-bold text-purple-300 text-sm">Sangat Konservatif</span>
                            </div>
                            <p class="text-xs text-gray-400 leading-relaxed">Skor 0-4. Takut risiko, prioritas keamanan dana.</p>
                        </div>
                        <div class="p-4 rounded-xl border border-emerald-500/30 bg-emerald-500/5">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="text-xl">2</span>
                                <span class="font-bold text-emerald-300 text-sm">Konservatif</span>
                            </div>
                            <p class="text-xs text-gray-400 leading-relaxed">Skor 5-8. Hati-hati, prefer aset stabil.</p>
                        </div>
                        <div class="p-4 rounded-xl border border-yellow-500/30 bg-yellow-500/5">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="text-xl">3</span>
                                <span class="font-bold text-yellow-300 text-sm">Moderat</span>
                            </div>
                            <p class="text-xs text-gray-400 leading-relaxed">Skor 9-12. Seimbang antara aman dan pertumbuhan.</p>
                        </div>
                        <div class="p-4 rounded-xl border border-orange-500/30 bg-orange-500/5">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="text-xl">4</span>
                                <span class="font-bold text-orange-300 text-sm">Agresif</span>
                            </div>
                            <p class="text-xs text-gray-400 leading-relaxed">Skor 13-16. Toleransi risiko tinggi, fokus pertumbuhan.</p>
                        </div>
                        <div class="p-4 rounded-xl border border-red-500/30 bg-red-500/5">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="text-xl">5</span>
                                <span class="font-bold text-red-300 text-sm">Sangat Agresif</span>
                            </div>
                            <p class="text-xs text-gray-400 leading-relaxed">Skor 17-20. Siap risiko tinggi untuk return maksimal.</p>
                        </div>
                    </div>
                </div>

                {{-- Form Input Nama + NIM --}}
                <div class="rounded-2xl p-6 bg-white/[0.03] backdrop-blur-xl border border-white/10">
                    <form action="{{ route('edukasi.kuis.simpan') }}" method="POST" @submit.prevent="startQuiz">
                        @csrf

                        <div class="mb-4">
                            <label for="nama" class="block mb-2 text-sm font-medium text-gray-300">
                                Nama Lengkap
                            </label>
                            <input type="text" id="nama" name="nama" x-model="nama"
                                   value="{{ old('nama', Auth::user()->name ?? '') }}"
                                   class="w-full bg-white/[0.05] border border-white/10 text-white rounded-lg px-4 py-2 focus:border-cyan-400/40 focus:ring-1 focus:ring-cyan-400/40"
                                   placeholder="Masukkan nama lengkap" required>
                        </div>

                        <div class="mb-6">
                            <label for="nim" class="block mb-2 text-sm font-medium text-gray-300">
                                NIM
                            </label>
                            <input type="text" id="nim" name="nim" x-model="nim"
                                   value="{{ old('nim', Auth::user()->nim ?? '') }}"
                                   class="w-full bg-white/[0.05] border border-white/10 text-white rounded-lg px-4 py-2 focus:border-cyan-400/40 focus:ring-1 focus:ring-cyan-400/40"
                                   placeholder="Masukkan NIM" required>
                        </div>

                        <button type="submit"
                                class="w-full bg-gradient-to-r from-cyan-600 to-blue-600 text-white px-6 py-3 rounded-xl font-semibold hover:from-cyan-500 hover:to-blue-500 transition shadow-[0_0_25px_rgba(6,182,212,0.5)]">
                            Mulai Kuis (10 Soal)
                        </button>
                    </form>
                </div>
            </div>

            {{-- PHASE: QUIZ (10 soal satu per satu) --}}
            <div x-show="phase === 'quiz'" x-transition>
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs text-cyan-300 font-medium uppercase tracking-wider">
                        Soal <span x-text="currentQuestion + 1"></span> dari 10
                    </span>
                    <span class="text-xs text-gray-400">
                        Skor terkini: <span x-text="Object.values(answers).reduce((a,b) => a + parseInt(b || 0), 0)"></span>
                    </span>
                </div>

                <div class="w-full bg-white/5 rounded-full h-2 mb-6 overflow-hidden border border-white/10">
                    <div class="bg-gradient-to-r from-cyan-500 to-blue-500 h-full"
                         :style="`width: ${((currentQuestion + 1) / 10) * 100}%`"></div>
                </div>

                <div class="rounded-2xl p-6 bg-white/[0.03] backdrop-blur-xl border border-white/10 min-h-[300px]">
                    <h3 class="text-white font-bold text-lg leading-relaxed mb-6" x-text="soal[currentQuestion].question"></h3>

                    <div class="space-y-3">
                        <template x-for="(o, idx) in soal[currentQuestion].options" :key="idx">
                            <button @click="selectAnswer(o.score)"
                                    :class="`
                                        w-full px-5 py-3 rounded-xl border text-left transition-all
                                        text-sm font-medium
                                        bg-white/[0.02] border-white/10 text-gray-300
                                        hover:border-cyan-400/40 hover:bg-white/[0.05] hover:text-white
                                    `">
                                <span class="font-bold text-cyan-300 mr-2 uppercase text-[10px]">
                                    {{ 'ABC'[idx] }}
                                </span>
                                <span x-text="o.text"></span>
                            </button>
                        </template>
                    </div>

                    <div class="mt-6 text-[10px] text-gray-500 text-center">
                        Jawaban semua 10 soal untuk melihat profil risiko kamu.
                    </div>
                </div>
            </div>

            {{-- PHASE: RESULT (Hasil) --}}
            <div x-show="phase === 'result'" x-transition>
                <div class="rounded-2xl p-6 bg-white/[0.03] backdrop-blur-xl border border-white/10 text-center">
                    <div class="text-5xl mb-4" x-text="level >= 4 ? '🔥' : (level >= 3 ? '⚡' : '🛡️')"></div>
                    <h3 class="text-2xl font-bold text-white mb-1" x-text="tipe"></h3>
                    <p class="text-gray-400 text-sm mb-6">
                        Skor: <span class="font-bold text-white" x-text="skor"></span> / <span x-text="maxSkor"></span>
                    </p>

                    <div class="w-full bg-white/5 rounded-full h-3 mb-6 overflow-hidden">
                        <div class="bg-gradient-to-r from-cyan-500 to-blue-500 h-full"
                             :style="`width: ${(skor / maxSkor) * 100}%`"></div>
                    </div>

                    <div class="text-left mb-6">
                        <h4 class="text-sm font-bold text-white mb-2">Rekomendasi Alokasi Aset:</h4>
                        <p class="text-sm text-gray-300" x-text="allocText()"></p>
                    </div>

                    <div class="flex gap-3">
                        <button @click="resetQuiz()"
                                class="flex-1 px-5 py-2.5 rounded-xl text-sm font-semibold text-white
                                      bg-white/10 border border-white/10 hover:bg-white/20 transition">
                            Ulangi Kuis
                        </button>

                        <form action="{{ route('edukasi.kuis.simpan') }}" method="POST" @submit.prevent>
                            @csrf
                            <input type="hidden" name="nama" :value="nama">
                            <input type="hidden" name="nim" :value="nim">
                            <input type="hidden" name="answers" :value="JSON.stringify(Object.values(answers))">
                            <button type="submit"
                                    class="flex-1 px-5 py-2.5 rounded-xl text-sm font-semibold text-white
                                          bg-gradient-to-r from-emerald-500 to-teal-600
                                          hover:from-emerald-400 hover:to-teal-500 transition-all shadow-lg">
                                Simpan ke Profil
                            </button>
                        </form>
                    </div>
                </div>

                @if(session('quiz_result'))
                    <div class="mt-6 rounded-xl p-4 bg-emerald-500/10 border border-emerald-400/20 text-emerald-300 text-sm">
                        <strong>Disimpan:</strong> Profil risiko "{{ session('quiz_result')['tipe'] }}" telah tersimpan untuk NIM {{ session('quiz_result')['nim'] }}.
                    </div>
                @endif
            </div>

            {{-- Existing profile already saved --}}
            @if($existing && !isset($quiz_result))
                <div class="mt-6 rounded-xl p-4 bg-blue-500/10 border border-blue-400/20 text-blue-300 text-sm text-center">
                    <strong>Profil sudah ada:</strong> Kamu sudah memiliki profil "{{ $existing->tipe_risiko }}" (Level {{ $existing->level }}). Silakan ulangi kuis jika ingin memperbarui.
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
