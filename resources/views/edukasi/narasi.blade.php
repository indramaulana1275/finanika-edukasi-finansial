<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Narasi Edukasi AI') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-[#0B0E14] min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Card Profil Risiko --}}
            <div class="bg-white/[0.03] backdrop-blur-xl border border-white/10 rounded-2xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-400">Profil Risiko Kamu</p>
                        @php
                            $warna = match($profilRisiko ?? '') {
                                'Konservatif' => 'text-emerald-400',
                                'Moderat'     => 'text-yellow-400',
                                'Agresif'     => 'text-orange-400',
                                default       => 'text-gray-400',
                            };
                        @endphp
                        <p class="text-2xl font-bold {{ $warna }}">
                            {{ $profilRisiko }}
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-gray-500">Dianalisis oleh</p>
                        <p class="text-sm font-semibold text-cyan-400">
                            Gemini 1.5 Flash
                        </p>
                    </div>
                </div>
            </div>

            {{-- Card Data Pasar Ringkas --}}
            <div class="bg-white/[0.03] backdrop-blur-xl border border-white/10 rounded-2xl p-6">
                <h3 class="text-md font-semibold text-white mb-4">
                    📊 Data Pasar yang Dianalisis
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    {{-- Bitcoin --}}
                    <div class="bg-orange-500/10 border border-orange-400/20 p-4 rounded-xl">
                        <p class="text-xs text-gray-400">Bitcoin (BTC)</p>
                        @if($marketData['bitcoin']['success'] ?? false)
                            <p class="text-lg font-bold text-orange-300">
                                $ {{ number_format($marketData['bitcoin']['price_usd'], 2, '.', ',') }}
                            </p>
                            @php $btcChange = $marketData['bitcoin']['change_24h'] ?? 0; @endphp
                            <p class="text-xs {{ $btcChange >= 0 ? 'text-emerald-400' : 'text-red-400' }}">
                                {{ $btcChange >= 0 ? '▲' : '▼' }} {{ number_format(abs($btcChange), 2) }}%
                            </p>
                        @else
                            <p class="text-sm text-red-400">Data tidak tersedia</p>
                        @endif
                    </div>

                    {{-- IHSG --}}
                    <div class="bg-blue-500/10 border border-blue-400/20 p-4 rounded-xl">
                        <p class="text-xs text-gray-400">IHSG (^JKSE)</p>
                        @if($marketData['stock']['success'] ?? false)
                            <p class="text-lg font-bold text-blue-300">
                                Rp {{ number_format($marketData['stock']['price'], 0, ',', '.') }}
                            </p>
                            @php $ihsgChange = $marketData['stock']['change_percent'] ?? 0; @endphp
                            <p class="text-xs {{ $ihsgChange >= 0 ? 'text-emerald-400' : 'text-red-400' }}">
                                {{ $ihsgChange >= 0 ? '▲' : '▼' }} {{ number_format(abs($ihsgChange), 2) }}%
                            </p>
                        @else
                            <p class="text-sm text-red-400">Data tidak tersedia</p>
                        @endif
                    </div>

                    {{-- Emas --}}
                    <div class="bg-yellow-500/10 border border-yellow-400/20 p-4 rounded-xl">
                        <p class="text-xs text-gray-400">Emas (XAU/USD)</p>
                        @if($marketData['gold']['success'] ?? false)
                            <p class="text-lg font-bold text-yellow-300">
                                $ {{ number_format($marketData['gold']['price_usd'], 2, '.', ',') }}
                            </p>
                            <p class="text-xs text-gray-500">per troy ounce</p>
                        @else
                            <p class="text-sm text-red-400">Data tidak tersedia</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Card Narasi AI --}}
            <div class="bg-gradient-to-br from-indigo-500/10 via-purple-500/5 to-cyan-500/10
                        backdrop-blur-xl border border-indigo-400/20 rounded-2xl p-6
                        shadow-[0_0_40px_rgba(99,102,241,0.15)]">
                <div class="flex items-center gap-2 mb-4">
                    <span class="text-2xl">🤖</span>
                    <h3 class="text-md font-semibold text-white">
                        Analisis & Rekomendasi dari FinGo AI
                    </h3>
                </div>

                <div class="prose prose-invert max-w-none">
                    <p class="text-gray-200 leading-relaxed whitespace-pre-line">
                        {{ $narasi }}
                    </p>
                </div>
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('edukasi.narasi') }}"
                   class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600
                          border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest
                          hover:from-indigo-500 hover:to-purple-500 transition
                          shadow-[0_0_20px_rgba(99,102,241,0.4)]">
                    🔄 Generate Ulang
                </a>

                <a href="{{ route('edukasi.market') }}"
                   class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-emerald-600 to-cyan-600
                          border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest
                          hover:from-emerald-500 hover:to-cyan-500 transition
                          shadow-[0_0_20px_rgba(16,185,129,0.4)]">
                    📈 Lihat Market Data
                </a>

                <a href="{{ route('dashboard') }}"
                   class="inline-flex items-center px-4 py-2 bg-white/5 border border-white/10
                          rounded-md font-semibold text-xs text-gray-200 uppercase tracking-widest
                          backdrop-blur-xl hover:bg-white/10 transition">
                    ← Kembali ke Dasbor
                </a>
            </div>

        </div>
    </div>
</x-app-layout>