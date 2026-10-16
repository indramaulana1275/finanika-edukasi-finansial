<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <a href="{{ route('education.index') }}"
                   class="inline-flex items-center gap-2 text-xs text-cyan-400 hover:text-cyan-300 transition mb-2">
                    <span>←</span> Kembali ke Daftar Modul
                </a>
                <h2 class="font-semibold text-xl text-white leading-tight">
                    {{ $module->title }}
                </h2>
                <p class="text-xs text-gray-400 mt-0.5">
                    {{ $module->category }} · Modul {{ $module->order_index }}
                </p>
            </div>
            <div class="flex items-center gap-2 px-3 py-1.5 rounded-full border
                        {{ $progress && $progress->completed
                            ? 'bg-emerald-500/10 border-emerald-400/40 text-emerald-300'
                            : 'bg-white/5 border-white/10 text-gray-400' }} min-w-fit">
                @if ($progress && $progress->completed)
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                    <span class="text-[10px] font-semibold">Selesai · {{ $progress->score }}/{{ $progress->max_score }}</span>
                @else
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                    <span class="text-[10px] font-semibold">Belum ujian</span>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="relative min-h-screen py-8 overflow-hidden">
        <div class="relative max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Artikel Modul --}}
            <div class="fade-in-up rounded-2xl p-6 md:p-8
                        bg-white/[0.03] backdrop-blur-xl border border-white/10
                        shadow-[0_0_30px_rgba(6,182,212,0.1)]">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-cyan-400 to-purple-600
                                flex items-center justify-center text-xl shadow-[0_0_20px_rgba(6,182,212,0.5)]">
                        {{ $module->icon }}
                    </div>
                    <div>
                        <span class="text-[10px] font-bold text-cyan-300 uppercase tracking-widest">
                            Modul {{ $module->order_index }}
                        </span>
                        <h3 class="text-lg font-bold text-white mt-0.5">{{ $module->title }}</h3>
                    </div>
                </div>

                <div class="prose prose-invert prose-sm md:prose-base max-w-none text-gray-300 leading-relaxed">
                    {!! $module->content !!}
                </div>

                {{-- Divider --}}
                <div class="my-8 border border-white/5"></div>

                {{-- Quiz Section --}}
                <div class="pt-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h4 class="text-sm font-bold text-white flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-purple-400"></span>
                                Quiz Modul
                            </h4>
                            <p class="text-xs text-gray-400 mt-0.5">
                                {{ $quizzes->count() }} soal pilihan ganda. Selesaikan semua dengan benar untuk menyelesaikan modul.
                            </p>
                        </div>
                        <button @click="openQuizModal()"
                                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold
                                      text-white bg-gradient-to-r from-purple-600 to-pink-600
                                      hover:from-purple-500 hover:to-pink-500 transition-all
                                      shadow-[0_0_20px_rgba(168,85,247,0.4)]">
                            📝 Mulai Quiz
                        </button>
                    </div>

                    @if ($progress && $progress->completed)
                        <div class="mt-4 p-3 rounded-xl bg-emerald-500/10 border border-emerald-400/40">
                            <p class="text-sm text-emerald-300 font-medium flex items-center gap-2">
                                ✅ Kamu sudah menyelesaikan quiz modul ini dengan nilai
                                <span class="font-bold text-emerald-400">{{ $progress->score }}/{{ $progress->max_score }}</span>.
                            </p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    {{-- Quiz Modal (Alpine.js) --}}
    <div x-data="quizModal()" x-show="showModal" x-transition
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-sm p-4">
        <div x-show="!showResult"
             class="bg-white/[0.05] backdrop-blur-xl border border-white/10 rounded-2xl p-6 md:p-8 max-w-lg w-full shadow-[0_0_60px_rgba(0,0,0,0.5)]">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-white">Quiz: {{ $module->title }}</h3>
                <button @click="closeQuizModal()"
                        class="text-gray-400 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="text-xs text-cyan-300/80 mb-6">
                Soal <span x-text="currentQuestion + 1"></span> dari {{ $quizzes->count() }}
            </div>

            <progress class="w-full h-1.5 bg-white/5 rounded-full overflow-hidden mb-8"
                      :value="currentQuestion + 1" max="{{ $quizzes->count() }}"></progress>

            <form @submit.prevent="submitAnswer()">
                <div class="space-y-4">
                    <h4 class="text-white font-bold text-lg leading-relaxed" x-text="questions[currentQuestion].question"></h4>

                    <div class="space-y-2 mt-4">
                        @php $options = ['a', 'b', 'c', 'd']; @endphp
                        @foreach ($options as $opt)
                            <label class="cursor-pointer block">
                                <input type="radio" name="answer" x-model="selectedAnswer" :value="'{{ $opt }}'"
                                       class="sr-only peer"/>
                                <div class="flex items-center gap-3 px-4 py-3 rounded-xl border border-white/10
                                          bg-white/[0.02] hover:border-cyan-400/40 hover:bg-white/[0.05]
                                          transition-all peer-checked:bg-cyan-500/20 peer-checked:border-cyan-400/50">
                                    <span class="text-white font-semibold uppercase tracking-wider text-[10px]">
                                        {{ strtoupper($opt) }}
                                    </span>
                                    <span class="text-gray-200 text-sm" x-text="questions[currentQuestion]['option_{{ $opt }}']"></span>
                                </div>
                            </label>
                        @endforeach
                    </div>

                    <button type="submit"
                            :disabled="selectedAnswer === null"
                            class="w-full mt-6 px-6 py-3 rounded-xl text-sm font-semibold text-white
                                  bg-gradient-to-r from-cyan-600 to-blue-600
                                  disabled:opacity-50 disabled:cursor-not-allowed
                                  hover:from-cyan-500 hover:to-blue-500 transition-all
                                  shadow-[0_0_20px_rgba(6,182,212,0.4)]">
                        {{ $currentQuestion < questions.length - 1 ? 'Berikutnya' : 'Selesai & Lihat Nilai' }}
                    </button>
                </div>
            </form>
        </div>

        {{-- Result Screen --}}
        <div x-show="showResult"
             class="bg-white/[0.05] backdrop-blur-xl border border-white/10 rounded-2xl p-6 md:p-8 max-w-lg w-full text-center shadow-[0_0_60px_rgba(0,0,0,0.5)]">
            <div class="text-5xl mb-3" x-text="score >= maxScore ? '🎉' : '📊'"></div>
            <h3 class="text-xl font-bold text-white mb-2" x-text="score >= maxScore ? 'Lulus dengan Sempurna!' : 'Quiz Selesai'"></h3>
            <p class="text-4xl font-extrabold text-white mb-1" x-text="score + ' / ' + maxScore"></p>
            <p class="text-gray-400 text-sm mb-6">Nilai quiz modul ini</p>

            <div class="w-full bg-white/5 rounded-full h-3 mb-6 overflow-hidden">
                <div class="bg-gradient-to-r from-cyan-500 to-blue-500 h-full"
                     :style="`width: ${Math.round((score / maxScore) * 100)}%`"></div>
            </div>

            <div class="space-y-3 mb-6 text-left" x-for="(r, index) in results" :key="index">
                <div class="flex items-start gap-2 mb-1">
                    <span class="text-[10px] font-bold"
                          :class="r.is_correct ? 'text-emerald-400' : 'text-red-400'"
                          x-text="r.is_correct ? '✅' : '❌'"></span>
                    <span class="text-xs text-gray-300" x-text="r.question"></span>
                </div>
                <p class="text-[11px] text-gray-500 ml-5 mb-2"
                   x-text="`Jawabanmu: ${r.options[r.user_answer]}. ${r.is_correct ? 'Benar!' : 'Salah. Jawaban benar: ' + r.options[r.correct_answer] + '.'} ${r.explanation}`"></p>
            </div>

            <div class="flex gap-3">
                @if ($progress && $progress->completed)
                    <button @click="closeQuizModal()"
                            class="flex-1 px-5 py-2.5 rounded-xl text-sm font-semibold text-white
                                  bg-white/10 border border-white/10 hover:bg-white/20 transition">
                        Tutup
                    </button>
                @else
                    <button @click="closeQuizModal()" x-show="!allCompleted"
                            class="flex-1 px-5 py-2.5 rounded-xl text-sm font-semibold text-white
                                  bg-white/10 border border-white/10 hover:bg-white/20 transition">
                        Kembali ke Daftar Modul
                    </button>
                    <a :href="allCompleted ? certificateUrl : '#'"
                       x-show="allCompleted"
                       class="flex-1 px-5 py-2.5 rounded-xl text-sm font-semibold text-white
                             bg-gradient-to-r from-emerald-500 to-teal-600
                             hover:from-emerald-400 hover:to-teal-500 transition-all shadow-lg">
                        Terima Sertifikat
                    </a>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function quizModal() {
            return {
                showModal: false,
                showResult: false,
                questions: @json($quizzes),
                currentQuestion: 0,
                selectedAnswer: null,
                userAnswers: {},
                score: 0,
                maxScore: {{ $quizzes->count() }},
                results: [],
                allCompleted: {{ $progress && $progress->completed ? 'true' : 'false' }},
                certificateUrl: "{{ route('education.certificate') }}",

                openQuizModal() {
                    this.showModal = true;
                    this.showResult = false;
                    this.currentQuestion = 0;
                    this.selectedAnswer = null;
                    this.userAnswers = {};
                    this.score = 0;
                    this.results = [];
                },

                closeQuizModal() {
                    this.showModal = false;
                },

                submitAnswer() {
                    const q = this.questions[this.currentQuestion];
                    if (this.selectedAnswer === null) return;

                    // Simpan jawaban user
                    this.userAnswers[q.id] = this.selectedAnswer;

                    const isCorrect = this.selectedAnswer === q.correct_answer;
                    if (isCorrect) this.score++;

                    this.results.push({
                        quiz_id: q.id,
                        question: q.question,
                        user_answer: this.selectedAnswer,
                        correct_answer: q.correct_answer,
                        is_correct: isCorrect,
                        explanation: q.explanation,
                    });

                    if (this.currentQuestion < this.questions.length - 1) {
                        this.currentQuestion++;
                        this.selectedAnswer = null;
                    } else {
                        this.showResult = true;

                        @if (!$progress || !$progress->completed)
                        fetch("{{ route('education.submit', $module->slug) }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                answers: this.userAnswers
                            })
                        }).then(r => r.json()).then(data => {
                            if (data.success) {
                                // Gunakan skor dari server sebagai otoritatif
                                this.score = data.score;
                                this.results = data.results;
                                if (data.all_completed) {
                                    this.allCompleted = true;
                                }
                            }
                        }).catch(err => console.error(err));
                        @endif
                    }
                }
            };
        }
    </script>
    @endpush
</x-app-layout>
