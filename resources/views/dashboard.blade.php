<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Dasbor') }}
            </h2>
        </div>
    </x-slot>

    {{-- ============================================================ --}}
    {{-- WRAPPER --}}
    {{-- ============================================================ --}}
    <div class="relative min-h-screen py-8 overflow-hidden">

        <div class="relative max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- ============================================================ --}}
            {{-- HEADER SAPAAN + BADGE PROFIL --}}
            {{-- ============================================================ --}}
            <div class="fade-in-up flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <p class="text-sm text-cyan-300/80 tracking-wide">Selamat siang,</p>
                    <h1 class="text-3xl md:text-5xl font-extrabold mt-1 tracking-tight gradient-text">
                        {{ $user->name }}
                    </h1>
                </div>

                @php
                    $badgeStyle = match($profilRisiko) {
                        'Konservatif' => 'bg-emerald-500/10 border-emerald-400/50 text-emerald-300 shadow-[0_0_25px_rgba(16,185,129,0.5)]',
                        'Moderat'     => 'bg-yellow-500/10 border-yellow-400/50 text-yellow-300 shadow-[0_0_25px_rgba(234,179,8,0.5)]',
                        'Agresif'     => 'bg-orange-500/10 border-orange-400/50 text-orange-300 shadow-[0_0_25px_rgba(249,115,22,0.5)]',
                        default       => 'bg-gray-500/10 border-gray-400/50 text-gray-300',
                    };
                    $iconFire = match($profilRisiko) {
                        'Agresif' => '🔥',
                        'Moderat' => '⚡',
                        default   => '🛡️',
                    };
                @endphp

                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-2 px-4 py-2 rounded-full border {{ $badgeStyle }}">
                        <span class="text-sm">{{ $iconFire }}</span>
                        <span class="text-xs font-semibold tracking-wider uppercase">
                            Profil: {{ $profilRisiko }}
                        </span>
                    </div>

                    <button class="w-10 h-10 rounded-full bg-white/5 border border-white/10
                                   flex items-center justify-center text-gray-400
                                   hover:bg-cyan-500/10 hover:border-cyan-400/40 hover:text-cyan-300 transition">
                        🔔
                    </button>
                </div>
            </div>

            {{-- ============================================================ --}}
            {{-- KARTU AI FINANCIAL ADVISOR --}}
            {{-- ============================================================ --}}
            <div class="fade-in-up fade-in-up-delay-1 relative overflow-hidden rounded-2xl p-6
                        bg-gradient-to-br from-cyan-950/60 via-slate-900/80 to-purple-950/40
                        backdrop-blur-2xl border border-cyan-500/30 shimmer-border
                        shadow-[0_0_50px_rgba(6,182,212,0.2)]">

                <div class="absolute -top-32 -right-32 w-80 h-80 bg-cyan-500/25 rounded-full blur-3xl float-anim"></div>
                <div class="absolute -bottom-32 -left-32 w-80 h-80 bg-purple-500/25 rounded-full blur-3xl float-anim"></div>

                <div class="relative">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="relative w-11 h-11 rounded-xl bg-gradient-to-br from-cyan-400 to-purple-600
                                        flex items-center justify-center
                                        shadow-[0_0_25px_rgba(6,182,212,0.7)] glow-pulse">
                                <span class="text-lg relative z-10">✨</span>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-white">
                                    AI Financial Advisor
                                </h3>
                                <p class="text-xs text-cyan-300/80">
                                    Dianalisis oleh <span class="font-semibold text-cyan-300">Gemini 1.5 Flash</span>
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 px-3 py-1 rounded-full
                                    bg-emerald-500/10 border border-emerald-400/40
                                    shadow-[0_0_15px_rgba(16,185,129,0.4)]">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            <span class="text-[10px] font-semibold text-emerald-300 uppercase tracking-wider">Live</span>
                        </div>
                    </div>

                    {{-- Narasi AI dengan Typewriter (Optimized: Kata per Kata) --}}
                    <div class="prose prose-invert max-w-none">
                        <p id="narasi-text"
                           class="text-gray-100 leading-relaxed whitespace-pre-line text-sm md:text-[15px] fade-in-up">
                            {{ $narasi }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- ============================================================ --}}
            {{-- MARKET DATA — 3 KARTU INTERAKTIF --}}
            {{-- ============================================================ --}}
            <div class="fade-in-up fade-in-up-delay-2">
                <h3 class="text-base font-bold text-white mb-4 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-cyan-400 animate-pulse"></span>
                    Market Data
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                    {{-- Kartu Bitcoin --}}
                    <button type="button"
                            data-asset="btc"
                            onclick="switchChart('btc')"
                            class="asset-card group text-left relative overflow-hidden rounded-2xl p-5
                                   bg-white/[0.03] backdrop-blur-xl border border-white/10
                                   transition-all duration-300
                                   hover:border-cyan-400/60 hover:shadow-[0_0_40px_rgba(6,182,212,0.35)]
                                   hover:scale-[1.02]">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-orange-500/20 border border-orange-400/40
                                            flex items-center justify-center
                                            shadow-[0_0_15px_rgba(249,115,22,0.4)]">
                                    <span class="text-orange-400 font-bold">₿</span>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-white">Bitcoin</p>
                                    <p class="text-[10px] text-gray-500 font-mono">BTC</p>
                                </div>
                            </div>

                            @if($marketPrices['bitcoin']['success'] ?? false)
                                @php $btcChange = $marketPrices['bitcoin']['change_24h'] ?? 0; @endphp
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-[10px] font-semibold
                                             {{ $btcChange >= 0 ? 'bg-emerald-500/15 text-emerald-400 border border-emerald-400/40' : 'bg-red-500/15 text-red-400 border border-red-400/40' }}">
                                    {{ $btcChange >= 0 ? '↗' : '↘' }} {{ number_format(abs($btcChange), 2) }}%
                                </span>
                            @endif
                        </div>

                        @if($marketPrices['bitcoin']['success'] ?? false)
                            <p class="text-2xl font-bold text-white tracking-tight">
                                ${{ number_format($marketPrices['bitcoin']['price_usd'], 2, '.', ',') }}
                            </p>
                        @else
                            <p class="text-sm text-red-400">Data tidak tersedia</p>
                        @endif

                        <div class="active-bar absolute bottom-0 left-0 h-[2px] w-0
                                    bg-gradient-to-r from-cyan-400 via-blue-400 to-transparent
                                    transition-all duration-500
                                    shadow-[0_0_10px_rgba(6,182,212,0.8)]"></div>
                    </button>

                    {{-- Kartu IHSG --}}
                    <button type="button"
                            data-asset="stock"
                            onclick="switchChart('stock')"
                            class="asset-card group text-left relative overflow-hidden rounded-2xl p-5
                                   bg-white/[0.03] backdrop-blur-xl border border-white/10
                                   transition-all duration-300
                                   hover:border-purple-400/60 hover:shadow-[0_0_40px_rgba(168,85,247,0.35)]
                                   hover:scale-[1.02]">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-purple-500/20 border border-purple-400/40
                                            flex items-center justify-center
                                            shadow-[0_0_15px_rgba(168,85,247,0.4)]">
                                    <span class="text-purple-400 font-bold text-xs">🏛️</span>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-white">IHSG</p>
                                    <p class="text-[10px] text-gray-500 font-mono">^JKSE</p>
                                </div>
                            </div>

                            @if($marketPrices['stock']['success'] ?? false)
                                @php $ihsgChange = $marketPrices['stock']['change_percent'] ?? 0; @endphp
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-[10px] font-semibold
                                             {{ $ihsgChange >= 0 ? 'bg-emerald-500/15 text-emerald-400 border border-emerald-400/40' : 'bg-red-500/15 text-red-400 border border-red-400/40' }}">
                                    {{ $ihsgChange >= 0 ? '↗' : '↘' }} {{ number_format(abs($ihsgChange), 2) }}%
                                </span>
                            @endif
                        </div>

                        @if($marketPrices['stock']['success'] ?? false)
                            <p class="text-2xl font-bold text-white tracking-tight">
                                {{ number_format($marketPrices['stock']['price'], 0, ',', '.') }}
                            </p>
                        @else
                            <p class="text-sm text-red-400">Data tidak tersedia</p>
                        @endif

                        <div class="active-bar absolute bottom-0 left-0 h-[2px] w-0
                                    bg-gradient-to-r from-purple-400 via-pink-400 to-transparent
                                    transition-all duration-500
                                    shadow-[0_0_10px_rgba(168,85,247,0.8)]"></div>
                    </button>

                    {{-- Kartu Emas --}}
                    <button type="button"
                            data-asset="gold"
                            onclick="switchChart('gold')"
                            class="asset-card group text-left relative overflow-hidden rounded-2xl p-5
                                   bg-white/[0.03] backdrop-blur-xl border border-white/10
                                   transition-all duration-300
                                   hover:border-emerald-400/60 hover:shadow-[0_0_40px_rgba(16,185,129,0.35)]
                                   hover:scale-[1.02]">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-emerald-500/20 border border-emerald-400/40
                                            flex items-center justify-center
                                            shadow-[0_0_15px_rgba(16,185,129,0.4)]">
                                    <span class="text-emerald-400 font-bold text-xs">💎</span>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-white">Emas</p>
                                    <p class="text-[10px] text-gray-500 font-mono">XAU</p>
                                </div>
                            </div>

                            @if($marketPrices['gold']['success'] ?? false)
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-[10px] font-semibold
                                             bg-emerald-500/15 text-emerald-400 border border-emerald-400/40">
                                    ↗ +0.87%
                                </span>
                            @endif
                        </div>

                        @if($marketPrices['gold']['success'] ?? false)
                            <p class="text-2xl font-bold text-white tracking-tight">
                                ${{ number_format($marketPrices['gold']['price_usd'], 2, '.', ',') }}
                            </p>
                        @else
                            <p class="text-sm text-red-400">Data tidak tersedia</p>
                        @endif

                        <div class="active-bar absolute bottom-0 left-0 h-[2px] w-0
                                    bg-gradient-to-r from-emerald-400 via-teal-400 to-transparent
                                    transition-all duration-500
                                    shadow-[0_0_10px_rgba(16,185,129,0.8)]"></div>
                    </button>
                </div>
            </div>

            {{-- ============================================================ --}}
            {{-- CHART DINAMIS --}}
            {{-- ============================================================ --}}
            <div class="fade-in-up fade-in-up-delay-3 rounded-2xl p-6
                        bg-white/[0.03] backdrop-blur-xl border border-cyan-500/20
                        shadow-[0_0_40px_rgba(6,182,212,0.1)]">

                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-white" id="chart-title">
                            Pergerakan Bitcoin <span class="text-gray-500 font-mono text-sm">(BTC)</span>
                        </h3>
                        <p class="text-sm mt-1" id="chart-subtitle">
                            <span class="text-emerald-400" id="chart-change">+0.00% hari ini</span>
                        </p>
                    </div>

                    <div class="inline-flex items-center gap-1 p-1 rounded-xl bg-white/5 border border-white/10">
                        <button onclick="loadPeriod(7)" data-period="7"
                                class="period-btn px-4 py-2 rounded-lg text-xs font-semibold transition-all
                                       bg-gradient-to-r from-cyan-600 to-blue-600 text-white
                                       shadow-[0_0_15px_rgba(6,182,212,0.5)]">
                            7H
                        </button>
                        <button onclick="loadPeriod(30)" data-period="30"
                                class="period-btn px-4 py-2 rounded-lg text-xs font-semibold transition-all
                                       text-gray-400 hover:text-white">
                            1B
                        </button>
                        <button onclick="loadPeriod(90)" data-period="90"
                                class="period-btn px-4 py-2 rounded-lg text-xs font-semibold transition-all
                                       text-gray-400 hover:text-white">
                            3B
                        </button>
                    </div>
                </div>

                <div id="market-chart" class="w-full h-80"></div>
            </div>

            {{-- ============================================================ --}}
            {{-- STATISTIK + RIWAYAT --}}
            {{-- ============================================================ --}}
            <div class="fade-in-up fade-in-up-delay-4 grid grid-cols-1 lg:grid-cols-3 gap-6">

                <div class="space-y-4">
                    <div class="rounded-2xl p-5 bg-white/[0.03] backdrop-blur-xl border border-cyan-500/20
                                hover:border-cyan-400/40 transition">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="w-2 h-2 rounded-full bg-cyan-400 animate-pulse"></span>
                            <p class="text-xs text-gray-400 uppercase tracking-wider">Total Aset</p>
                        </div>
                        <p class="text-3xl font-bold text-white mt-1">{{ $totalAset }}</p>
                        <p class="text-xs text-gray-500 mt-1">dianalisis</p>
                    </div>

                    <div class="rounded-2xl p-5 bg-white/[0.03] backdrop-blur-xl border border-emerald-500/20
                                hover:border-emerald-400/40 transition">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            <p class="text-xs text-gray-400 uppercase tracking-wider">Total Simulasi DCA</p>
                        </div>
                        <p class="text-xl font-bold text-emerald-400 mt-1">
                            Rp {{ number_format($totalSimulasi ?? 0, 0, ',', '.') }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1">akumulatif</p>
                    </div>
                </div>

                <div class="lg:col-span-2 rounded-2xl overflow-hidden
                            bg-white/[0.03] backdrop-blur-xl border border-white/10">
                    <div class="p-5 border-b border-white/5 flex items-center justify-between">
                        <h4 class="text-sm font-bold text-white">Riwayat Aset Terbaru</h4>
                        <a href="{{ route('edukasi.kalkulator') }}" class="text-xs text-cyan-400 hover:text-cyan-300">
                            Lihat semua →
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="bg-white/[0.02]">
                                <tr>
                                    <th class="px-4 py-3 text-left text-gray-400 font-medium text-[10px] uppercase tracking-wider">Aset</th>
                                    <th class="px-4 py-3 text-right text-gray-400 font-medium text-[10px] uppercase tracking-wider">Harga</th>
                                    <th class="px-4 py-3 text-right text-gray-400 font-medium text-[10px] uppercase tracking-wider">DCA</th>
                                    <th class="px-4 py-3 text-left text-gray-400 font-medium text-[10px] uppercase tracking-wider">Tanggal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @forelse($riwayatAset as $aset)
                                    <tr class="hover:bg-white/[0.03] transition">
                                        <td class="px-4 py-3 text-gray-200 font-medium">{{ $aset->nama_aset }}</td>
                                        <td class="px-4 py-3 text-right text-gray-300">
                                            Rp {{ number_format($aset->harga_saat_ini, 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-3 text-right text-emerald-400 font-semibold">
                                            Rp {{ number_format($aset->simulasi_dca ?? 0, 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-3 text-gray-500 text-xs">
                                            {{ $aset->created_at->format('d M Y') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-6 text-center text-gray-500 text-sm">
                                            Belum ada riwayat aset.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- ============================================================ --}}
            {{-- AKSI CEPAT --}}
            {{-- ============================================================ --}}
            <div class="fade-in-up fade-in-up-delay-4 flex flex-wrap gap-3">
                <a href="{{ route('edukasi.kuis') }}"
                   class="inline-flex items-center px-5 py-3 rounded-xl font-semibold text-xs uppercase tracking-widest
                          bg-gradient-to-r from-cyan-600 to-blue-600 text-white
                          hover:from-cyan-500 hover:to-blue-500 transition-all
                          shadow-[0_0_25px_rgba(6,182,212,0.5)]
                          hover:shadow-[0_0_35px_rgba(6,182,212,0.7)]">
                    📝 Kuis Risiko
                </a>
                <a href="{{ route('edukasi.kalkulator') }}"
                   class="inline-flex items-center px-5 py-3 rounded-xl font-semibold text-xs uppercase tracking-widest
                          bg-gradient-to-r from-emerald-600 to-teal-600 text-white
                          hover:from-emerald-500 hover:to-teal-500 transition-all
                          shadow-[0_0_25px_rgba(16,185,129,0.5)]
                          hover:shadow-[0_0_35px_rgba(16,185,129,0.7)]">
                    💹 Kalkulator DCA
                </a>
                <a href="{{ route('edukasi.market') }}"
                   class="inline-flex items-center px-5 py-3 rounded-xl font-semibold text-xs uppercase tracking-widest
                          bg-white/5 border border-white/10 text-gray-200 backdrop-blur-xl
                          hover:bg-cyan-500/10 hover:border-cyan-400/40 hover:text-cyan-300 transition-all">
                    📈 Market Lengkap
                </a>
                <a href="{{ route('edukasi.narasi') }}"
                   class="inline-flex items-center px-5 py-3 rounded-xl font-semibold text-xs uppercase tracking-widest
                          bg-white/5 border border-white/10 text-gray-200 backdrop-blur-xl
                          hover:bg-purple-500/10 hover:border-purple-400/40 hover:text-purple-300 transition-all">
                    🤖 Narasi AI
                </a>
            </div>

        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- APEXCHARTS CDN --}}
    {{-- ============================================================ --}}
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.45.1/dist/apexcharts.min.js"></script>

    <script>
        // =========================================================
        // FADE-IN NARASI AI
        // =========================================================
        document.addEventListener('DOMContentLoaded', () => {
            const narasiEl = document.getElementById('narasi-text');
            if (!narasiEl) return;
            narasiEl.style.opacity = '0';
            narasiEl.style.transition = 'opacity 0.8s ease';
            setTimeout(() => {
                narasiEl.style.opacity = '1';
            }, 200);
        });

        // =========================================================
        // Data chart
        // =========================================================
        const chartData = {
            btc: {
                label: 'Pergerakan Bitcoin',
                symbol: 'BTC',
                color: '#06b6d4',
                gradientTo: '#3b82f6',
                prices: @json($btcHistory['prices'] ?? []),
                key: 'price_usd',
                prefix: '$',
                change: {{ $marketPrices['bitcoin']['change_24h'] ?? 0 }},
                isCurrencyIDR: false,
            },
            stock: {
                label: 'Pergerakan IHSG',
                symbol: '^JKSE',
                color: '#a855f7',
                gradientTo: '#ec4899',
                prices: @json($stockHistory['prices'] ?? []),
                key: 'price',
                prefix: '',
                change: {{ $marketPrices['stock']['change_percent'] ?? 0 }},
                isCurrencyIDR: true,
            },
            gold: {
                label: 'Pergerakan Emas',
                symbol: 'XAU',
                color: '#10b981',
                gradientTo: '#14b8a6',
                prices: @json($goldHistory['prices'] ?? []),
                key: 'price_usd',
                prefix: '$',
                change: 0.87,
                isCurrencyIDR: false,
            },
        };

        let chart = null;
        let activeAsset = 'btc';

        function formatValue(asset, val) {
            const cfg = chartData[asset];
            const num = parseFloat(val);
            if (cfg.isCurrencyIDR) {
                return new Intl.NumberFormat('id-ID', { maximumFractionDigits: 0 }).format(num);
            }
            return cfg.prefix + new Intl.NumberFormat('en-US', { maximumFractionDigits: 2 }).format(num);
        }

        function buildOptions(asset) {
            const cfg = chartData[asset];
            const categories = cfg.prices.map(p => p.date);
            const values     = cfg.prices.map(p => parseFloat(p[cfg.key]));

            return {
                chart: {
                    type: 'area',
                    height: 320,
                    background: 'transparent',
                    foreColor: '#6b7280',
                    toolbar: { show: false },
                    zoom: { enabled: false },
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 800,
                        animateGradually: { enabled: true, delay: 150 },
                        dynamicAnimation: { enabled: true, speed: 500 },
                    },
                },
                series: [{ name: cfg.symbol, data: values }],
                xaxis: {
                    categories: categories,
                    labels: {
                        style: { colors: '#6b7280', fontSize: '10px', fontFamily: 'monospace' },
                        rotate: 0,
                        hideOverlappingLabels: true,
                    },
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                    tooltip: { enabled: false },
                },
                yaxis: {
                    labels: {
                        style: { colors: '#6b7280', fontSize: '10px', fontFamily: 'monospace' },
                        formatter: (val) => formatValue(asset, val),
                    },
                },
                stroke: {
                    curve: 'smooth',
                    width: 3,
                    colors: [cfg.color],
                    shadow: {
                        enabled: true,
                        color: cfg.color,
                        top: 0,
                        left: 0,
                        blur: 15,
                        opacity: 0.8,
                    },
                },
                colors: [cfg.color],
                fill: {
                    type: 'gradient',
                    gradient: {
                        type: 'vertical',
                        shadeIntensity: 0.4,
                        gradientToColors: [cfg.gradientTo],
                        inverseColors: false,
                        opacityFrom: 0.6,
                        opacityTo: 0.0,
                        stops: [0, 80, 100],
                    },
                },
                markers: {
                    size: 0,
                    hover: { size: 6 },
                    colors: [cfg.color],
                },
                grid: {
                    borderColor: 'rgba(6,182,212,0.08)',
                    strokeDashArray: 3,
                    xaxis: { lines: { show: false } },
                    yaxis: { lines: { show: true } },
                },
                tooltip: {
                    theme: 'dark',
                    style: { fontSize: '12px', fontFamily: 'monospace' },
                    x: { show: true },
                    y: {
                        formatter: (val) => formatValue(asset, val),
                        title: { formatter: () => cfg.symbol + ': ' },
                    },
                    marker: { show: true },
                },
                dataLabels: { enabled: false },
            };
        }

        function switchChart(asset) {
            if (!chart) return;
            activeAsset = asset;
            const cfg = chartData[asset];

            document.getElementById('chart-title').innerHTML =
                `${cfg.label} <span class="text-gray-500 font-mono text-sm">(${cfg.symbol})</span>`;

            const changeEl = document.getElementById('chart-change');
            const change = cfg.change || 0;
            changeEl.textContent = `${change >= 0 ? '+' : ''}${change.toFixed(2)}% hari ini`;
            changeEl.className = change >= 0 ? 'text-emerald-400' : 'text-red-400';

            chart.updateOptions(buildOptions(asset), false, true);

            document.querySelectorAll('.asset-card').forEach(card => {
                const isActive = card.dataset.asset === asset;
                card.classList.toggle('bg-white/[0.03]', !isActive);
                card.classList.toggle('bg-white/[0.08]', isActive);

                const bar = card.querySelector('.active-bar');
                if (bar) {
                    bar.classList.toggle('w-full', isActive);
                    bar.classList.toggle('w-0', !isActive);
                }
            });
        }

        async function loadPeriod(days) {
            document.querySelectorAll('.period-btn').forEach(btn => {
                const active = btn.dataset.period == days;
                btn.classList.toggle('bg-gradient-to-r', active);
                btn.classList.toggle('from-cyan-600', active);
                btn.classList.toggle('to-blue-600', active);
                btn.classList.toggle('text-white', active);
                btn.classList.toggle('shadow-[0_0_15px_rgba(6,182,212,0.5)]', active);
                btn.classList.toggle('text-gray-400', !active);
            });

            try {
                const [btcRes, stockRes, goldRes] = await Promise.all([
                    fetch(`/api/market/history?asset=btc&days=${days}`, { headers: { 'Accept': 'application/json' } }),
                    fetch(`/api/market/history?asset=stock&days=${days}`, { headers: { 'Accept': 'application/json' } }),
                    fetch(`/api/market/history?asset=gold&days=${days}`, { headers: { 'Accept': 'application/json' } }),
                ]);

                const [btcData, stockData, goldData] = await Promise.all([
                    btcRes.json(), stockRes.json(), goldRes.json()
                ]);

                if (btcData.prices)   chartData.btc.prices   = btcData.prices;
                if (stockData.prices) chartData.stock.prices = stockData.prices;
                if (goldData.prices)  chartData.gold.prices  = goldData.prices;

                chart.updateOptions(buildOptions(activeAsset), false, true);
            } catch (err) {
                console.error('Gagal load period:', err);
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            chart = new ApexCharts(
                document.querySelector('#market-chart'),
                buildOptions('btc')
            );
            chart.render();
            switchChart('btc');
        });
    </script>
</x-app-layout>