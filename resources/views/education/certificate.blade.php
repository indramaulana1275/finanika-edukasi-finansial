<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <a href="{{ route('education.index') }}"
               class="inline-flex items-center gap-2 text-xs text-cyan-400 hover:text-cyan-300 transition mb-2">
                <span>←</span> Kembali ke Daftar Modul
            </a>
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Sertifikat Digital') }}
            </h2>
        </div>
    </x-slot>

    <div class="relative min-h-screen py-8 overflow-hidden">
        <div class="relative max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Certificate Card --}}
            <div class="fade-in-up relative rounded-3xl p-8 md:p-12
                        bg-gradient-to-br from-[#1a0b2e] via-[#0B0E14] to-[#0a0a12]
                        border border-cyan-500/30 shadow-[0_0_60px_rgba(6,182,212,0.25)]
                        overflow-hidden">
                {{-- Decorative corners --}}
                <div class="absolute top-0 left-0 w-20 h-20 border-t-2 border-l-2 border-cyan-400/50"></div>
                <div class="absolute top-0 right-0 w-20 h-20 border-t-2 border-r-2 border-purple-400/50"></div>
                <div class="absolute bottom-0 left-0 w-20 h-20 border-b-2 border-l-2 border-emerald-400/50"></div>
                <div class="absolute bottom-0 right-0 w-20 h-20 border-b-2 border-r-2 border-pink-400/50"></div>

                {{-- Seal / Badge --}}
                <div class="absolute top-8 right-8 w-16 h-16 rounded-full bg-gradient-to-br from-emerald-500 to-teal-600
                            flex items-center justify-center text-white text-xl shadow-[0_0_25px_rgba(16,185,129,0.6)] glow-pulse">
                    ✔
                </div>

                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <p class="text-[10px] font-bold text-cyan-400 uppercase tracking-widest">
                                FINANIKA — Edukasi Literasi Finansial
                            </p>
                            <h1 class="text-3xl font-extrabold text-white mt-1 tracking-tight">
                                Sertifikat Keahlian
                            </h1>
                            <p class="text-sm text-gray-400 mt-1">
                                Pendidikan Literasi Finansial Mahasiswa
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-gray-500">Nomor Sertifikat</p>
                            <p class="text-sm font-mono text-white">{{ strtoupper(substr($user->name, 0, 3)) }}-{{ strtoupper(substr($user->email, 0, 4)) }}-{{ $user->id }}</p>
                        </div>
                    </div>

                    <div class="my-10 border border-white/5"></div>

                    <p class="text-gray-300 text-lg mb-6">
                        Ini menyatakan bahwa
                    </p>
                    <div class="flex justify-center mb-8">
                        <h2 class="text-4xl font-bold text-white gradient-text">
                            {{ $user->name }}
                        </h2>
                    </div>
                    <p class="text-gray-400 text-sm mb-8">
                        telah berhasil menyelesaikan seluruh <span class="text-cyan-400 font-semibold">{{ $totalModules }}</span> modul edukasi literasi finansial dengan baik dan terpuji.
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-10">
                        <div class="p-4 rounded-xl bg-white/5 border border-white/10">
                            <p class="text-[10px] font-bold text-gray-500 uppercase mb-1">Tanggal Terbit</p>
                            <p class="text-sm text-white">{{ $lastProgress->completed_at ? $lastProgress->completed_at->format('d M Y') : now()->format('d M Y') }}</p>
                        </div>
                        <div class="p-4 rounded-xl bg-white/5 border border-white/10">
                            <p class="text-[10px] font-bold text-gray-500 uppercase mb-1">Total Modul</p>
                            <p class="text-sm text-white">{{ $completedCount }} / {{ $totalModules }}</p>
                        </div>
                    </div>

                    <div class="flex justify-between mt-12 pt-8 border-t border-white/10">
                        <div>
                            <p class="text-[10px] font-bold text-gray-500 uppercase">Direktorat</p>
                            <p class="text-sm text-white">FINANIKA Edukasi</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-gray-500 uppercase">Tanda Tangan Digital</p>
                            <p class="text-sm text-white">Sistem Automatik</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="fade-in-up fade-in-up-delay-1 flex flex-col sm:flex-row gap-4 justify-center">
                <button onclick="window.print()"
                        class="w-full sm:w-auto px-6 py-3 rounded-xl text-sm font-semibold text-white
                              bg-white/10 border border-white/10 hover:bg-white/20 transition-all
                              flex items-center justify-center gap-2 shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2z"/>
                    </svg>
                    Download PDF (Print)
                </button>
                <a href="{{ route('education.index') }}"
                   class="w-full sm:w-auto px-6 py-3 rounded-xl text-sm font-semibold text-white
                         bg-gradient-to-r from-cyan-600 to-blue-600
                         hover:from-cyan-500 hover:to-blue-500 transition-all
                         flex items-center justify-center gap-2 shadow-[0_0_20px_rgba(6,182,212,0.4)]">
                    Kembali ke Daftar Modul
                </a>
            </div>

        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.body.classList.add('bg-[#0B0E14]');
        });
    </script>
    @endpush
</x-app-layout>
