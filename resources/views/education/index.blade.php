<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-white leading-tight">
                    {{ __('Modul Edukasi') }}
                </h2>
                <p class="text-xs text-gray-400 mt-0.5">
                    Belajar literasi finansial dari nol hingga mahir
                </p>
            </div>
            <div class="flex items-center gap-2 px-4 py-2 rounded-full bg-white/5 border border-white/10 backdrop-blur-xl">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span class="text-sm font-semibold text-white">{{ $completedCount }} dari {{ $totalModules }}</span>
                <span class="text-xs text-gray-500">modul selesai</span>
            </div>
        </div>
    </x-slot>

    <div class="relative min-h-screen py-8 overflow-hidden">
        <div class="relative max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Overall Progress Bar --}}
            <div class="fade-in-up">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-semibold text-white">Progres Belajar Kamu</span>
                    <span class="text-sm text-cyan-400 font-bold">
                        {{ $completedCount }} / {{ $totalModules }}
                    </span>
                </div>
                <div class="w-full bg-white/5 rounded-full h-3 overflow-hidden border border-white/10">
                    <div class="bg-gradient-to-r from-cyan-500 to-blue-500 h-full
                                {{ $completedCount >= $totalModules ? 'glow-pulse' : '' }}
                                transition-all duration-1000"
                         style="width: {{ $totalModules > 0 ? round(($completedCount / $totalModules) * 100) : 0 }}%"></div>
                </div>
                @if ($completedCount >= $totalModules)
                    <a href="{{ route('education.certificate') }}"
                       class="mt-3 inline-flex items-center px-5 py-2.5 rounded-xl text-sm font-semibold text-white
                              bg-gradient-to-r from-emerald-500 to-teal-600
                              hover:from-emerald-400 hover:to-teal-500 transition-all shadow-[0_0_20px_rgba(16,185,129,0.4)]">
                        🏆 Terima Sertifikat
                    </a>
                @endif
            </div>

            {{-- Grid Modul --}}
            <h3 class="fade-in-up text-base font-bold text-white mb-4 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-cyan-400 animate-pulse"></span>
                Daftar Modul
            </h3>

            <div class="fade-in-up fade-in-up-delay-1 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                @foreach ($modules as $module)
                    <div class="group relative overflow-hidden rounded-2xl p-5
                                bg-white/[0.03] backdrop-blur-xl border border-white/10
                                transition-all duration-300
                                hover:border-cyan-400/50 hover:shadow-[0_0_40px_rgba(6,182,212,0.25)]
                                {{ $module->is_completed ? 'bg-emerald-500/10 border-emerald-400/40' : '' }}">

                        {{-- Icon --}}
                        <div class="w-12 h-12 rounded-xl bg-white/[0.05] border border-white/10
                                    flex items-center justify-center text-2xl
                                    group-hover:scale-110 transition-transform
                                    {{ $module->is_completed ? 'bg-emerald-500/20 border-emerald-400/40 text-emerald-400' : 'text-cyan-300' }} mb-4">
                            {{ $module->icon }}
                        </div>

                        <div class="z-10">
                            <p class="text-sm font-bold text-white mb-1 group-hover:text-cyan-400 transition">
                                {{ $module->title }}
                            </p>
                            <p class="text-xs text-gray-400 mb-3 line-clamp-2">
                                {{ $module->description }}
                            </p>

                            {{-- Status --}}
                            @if ($module->is_completed)
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded text-[10px] font-semibold
                                            bg-emerald-500/15 text-emerald-400 border border-emerald-400/40 mb-3 inline-block">
                                    ✅ Selesai · Nilai {{ $module->score }}/{{ $module->progress->max_score }}
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded text-[10px] font-semibold
                                            bg-amber-500/15 text-amber-400 border border-amber-400/40 mb-3 inline-block">
                                    ⏳ Belum selesai
                                </span>
                            @endif

                            {{-- Action Button --}}
                            <a href="{{ route('education.show', $module->slug) }}"
                               class="w-full inline-flex items-center justify-center px-4 py-2.5 rounded-xl text-sm font-semibold
                                      text-white bg-gradient-to-r from-cyan-600 to-blue-600
                                      hover:from-cyan-500 hover:to-blue-500 transition-all
                                      shadow-[0_0_20px_rgba(6,182,212,0.4)]">
                                {{ $module->is_completed ? 'Ulangi Modul' : 'Mulai Belajar' }}
                            </a>
                        </div>

                        {{-- Overlay badge kalau selesai --}}
                        @if ($module->is_completed)
                            <div class="absolute top-2 right-2 w-8 h-8 rounded-full bg-emerald-500
                                        flex items-center justify-center text-white text-xs shadow-lg">
                                ✔
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            @if ($completedCount >= $totalModules)
                <div class="fade-in-up fade-in-up-delay-2 rounded-2xl p-6
                            bg-gradient-to-br from-emerald-500/20 via-teal-500/10 to-emerald-500/20
                            border border-emerald-400/40 text-center">
                    <div class="text-4xl mb-3">🎉</div>
                    <h4 class="text-lg font-bold text-emerald-200">Selamat! Semua modul telah selesai</h4>
                    <p class="text-sm text-emerald-100 mt-1">
                        Kamu telah menyelesaikan seluruh modul edukasi. Klik tombol di atas untuk menerima sertifikat digital.
                    </p>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
