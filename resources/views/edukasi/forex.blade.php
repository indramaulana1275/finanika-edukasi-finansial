<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Kurs Mata Uang Lengkap') }}
            </h2>
            <a href="{{ route('dashboard') }}" class="text-xs text-cyan-400 hover:text-cyan-300">
                ← Kembali ke Dasbor
            </a>
        </div>
    </x-slot>

    <div class="relative min-h-screen py-8 overflow-hidden">
        <div class="relative max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div>
                <p class="text-sm text-cyan-300/80 tracking-wide">Detail Kurs</p>
                <h1 class="text-3xl md:text-4xl font-extrabold mt-1 gradient-text">Kurs Mata Uang</h1>
                <p class="text-sm text-gray-400 mt-2">Pergerakan kurs terhadap Rupiah dari Yahoo Finance.</p>
            </div>

            <div class="fade-in-up grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <button type="button"
                        data-type="forex"
                        data-symbol="usdidr"
                        onclick="window.location.href = '{{ route('edukasi.asset.detail', ['type' => 'forex', 'symbol' => 'usdidr']) }}'"
                        class="asset-card group text-left relative overflow-hidden rounded-2xl p-5 min-h-[136px] bg-white/[0.03] backdrop-blur-xl border border-white/10 transition-all duration-300 hover:scale-[1.02] hover:border-emerald-400/60 hover:shadow-[0_0_40px_rgba(16,185,129,0.35)]">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-emerald-500/20 border border-emerald-400/40 flex items-center justify-center shadow-[0_0_15px_rgba(16,185,129,0.4)]">
                                <span class="text-emerald-400 font-bold text-lg">$</span>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-emerald-400">Dolar AS</p>
                                <p class="text-[10px] text-gray-500 font-mono">USD/IDR</p>
                            </div>
                        </div>
                        @if(($forexPrices['usd']['success'] ?? false))
                            @php $usdChange = $forexPrices['usd']['change_percent'] ?? 0; @endphp
                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-[10px] font-semibold {{ $usdChange >= 0 ? 'bg-emerald-500/15 text-emerald-400 border border-emerald-400/40' : 'bg-red-500/15 text-red-400 border border-red-400/40' }}">
                                {{ $usdChange >= 0 ? '↗' : '↘' }} {{ number_format(abs($usdChange), 2) }}%
                            </span>
                        @endif
                    </div>
                    @if(($forexPrices['usd']['success'] ?? false))
                        <p class="text-xl font-bold text-white tracking-tight">
                            Rp {{ number_format($forexPrices['usd']['rate'], 0, ',', '.') }}
                        </p>
                    @else
                        <p class="text-sm text-red-400">Data tidak tersedia</p>
                    @endif
                </button>

                <button type="button"
                        data-type="forex"
                        data-symbol="euridr"
                        onclick="window.location.href = '{{ route('edukasi.asset.detail', ['type' => 'forex', 'symbol' => 'euridr']) }}'"
                        class="asset-card group text-left relative overflow-hidden rounded-2xl p-5 min-h-[136px] bg-white/[0.03] backdrop-blur-xl border border-white/10 transition-all duration-300 hover:scale-[1.02] hover:border-blue-400/60 hover:shadow-[0_0_40px_rgba(59,130,246,0.35)]">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-500/20 border border-blue-400/40 flex items-center justify-center shadow-[0_0_15px_rgba(59,130,246,0.4)]">
                                <span class="text-blue-400 font-bold text-lg">€</span>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-blue-400">Euro</p>
                                <p class="text-[10px] text-gray-500 font-mono">EUR/IDR</p>
                            </div>
                        </div>
                        @if(($forexPrices['eur']['success'] ?? false))
                            @php $eurChange = $forexPrices['eur']['change_percent'] ?? 0; @endphp
                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-[10px] font-semibold {{ $eurChange >= 0 ? 'bg-emerald-500/15 text-emerald-400 border border-emerald-400/40' : 'bg-red-500/15 text-red-400 border border-red-400/40' }}">
                                {{ $eurChange >= 0 ? '↗' : '↘' }} {{ number_format(abs($eurChange), 2) }}%
                            </span>
                        @endif
                    </div>
                    @if(($forexPrices['eur']['success'] ?? false))
                        <p class="text-xl font-bold text-white tracking-tight">
                            Rp {{ number_format($forexPrices['eur']['rate'], 0, ',', '.') }}
                        </p>
                    @else
                        <p class="text-sm text-red-400">Data tidak tersedia</p>
                    @endif
                </button>

                <button type="button"
                        data-type="forex"
                        data-symbol="jpyidr"
                        onclick="window.location.href = '{{ route('edukasi.asset.detail', ['type' => 'forex', 'symbol' => 'jpyidr']) }}'"
                        class="asset-card group text-left relative overflow-hidden rounded-2xl p-5 min-h-[136px] bg-white/[0.03] backdrop-blur-xl border border-white/10 transition-all duration-300 hover:scale-[1.02] hover:border-red-400/60 hover:shadow-[0_0_40px_rgba(239,68,68,0.35)]">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-red-500/20 border border-red-400/40 flex items-center justify-center shadow-[0_0_15px_rgba(239,68,68,0.4)]">
                                <span class="text-red-400 font-bold text-lg">¥</span>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-red-400">Yen Jepang</p>
                                <p class="text-[10px] text-gray-500 font-mono">JPY/IDR</p>
                            </div>
                        </div>
                        @if(($forexPrices['jpy']['success'] ?? false))
                            @php $jpyChange = $forexPrices['jpy']['change_percent'] ?? 0; @endphp
                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-[10px] font-semibold {{ $jpyChange >= 0 ? 'bg-emerald-500/15 text-emerald-400 border border-emerald-400/40' : 'bg-red-500/15 text-red-400 border border-red-400/40' }}">
                                {{ $jpyChange >= 0 ? '↗' : '↘' }} {{ number_format(abs($jpyChange), 2) }}%
                            </span>
                        @endif
                    </div>
                    @if(($forexPrices['jpy']['success'] ?? false))
                        <p class="text-xl font-bold text-white tracking-tight">
                            Rp {{ number_format($forexPrices['jpy']['rate'], 0, ',', '.') }}
                        </p>
                    @else
                        <p class="text-sm text-red-400">Data tidak tersedia</p>
                    @endif
                </button>

                <button type="button"
                        data-type="forex"
                        data-symbol="cnYidr"
                        onclick="window.location.href = '{{ route('edukasi.asset.detail', ['type' => 'forex', 'symbol' => 'cnYidr']) }}'"
                        class="asset-card group text-left relative overflow-hidden rounded-2xl p-5 min-h-[136px] bg-white/[0.03] backdrop-blur-xl border border-white/10 transition-all duration-300 hover:scale-[1.02] hover:border-yellow-400/60 hover:shadow-[0_0_40px_rgba(234,179,8,0.35)]">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-yellow-500/20 border border-yellow-400/40 flex items-center justify-center shadow-[0_0_15px_rgba(234,179,8,0.4)]">
                                <span class="text-yellow-400 font-bold text-lg">¥</span>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-yellow-400">Yuan Tiongkok</p>
                                <p class="text-[10px] text-gray-500 font-mono">CNY/IDR</p>
                            </div>
                        </div>
                        @if(($forexPrices['cny']['success'] ?? false))
                            @php $cnyChange = $forexPrices['cny']['change_percent'] ?? 0; @endphp
                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-[10px] font-semibold {{ $cnyChange >= 0 ? 'bg-emerald-500/15 text-emerald-400 border border-emerald-400/40' : 'bg-red-500/15 text-red-400 border border-red-400/40' }}">
                                {{ $cnyChange >= 0 ? '↗' : '↘' }} {{ number_format(abs($cnyChange), 2) }}%
                            </span>
                        @endif
                    </div>
                    @if(($forexPrices['cny']['success'] ?? false))
                        <p class="text-xl font-bold text-white tracking-tight">
                            Rp {{ number_format($forexPrices['cny']['rate'], 0, ',', '.') }}
                        </p>
                    @else
                        <p class="text-sm text-red-400">Data tidak tersedia</p>
                    @endif
                </button>

                <button type="button"
                        data-type="forex"
                        data-symbol="gbpidr"
                        onclick="window.location.href = '{{ route('edukasi.asset.detail', ['type' => 'forex', 'symbol' => 'gbpidr']) }}'"
                        class="asset-card group text-left relative overflow-hidden rounded-2xl p-5 min-h-[136px] bg-white/[0.03] backdrop-blur-xl border border-white/10 transition-all duration-300 hover:scale-[1.02] hover:border-purple-400/60 hover:shadow-[0_0_40px_rgba(168,85,247,0.35)]">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-purple-500/20 border border-purple-400/40 flex items-center justify-center shadow-[0_0_15px_rgba(168,85,247,0.4)]">
                                <span class="text-purple-400 font-bold text-lg">£</span>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-purple-400">Pound Inggris</p>
                                <p class="text-[10px] text-gray-500 font-mono">GBP/IDR</p>
                            </div>
                        </div>
                        @if(($forexPrices['gbp']['success'] ?? false))
                            @php $gbpChange = $forexPrices['gbp']['change_percent'] ?? 0; @endphp
                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-[10px] font-semibold {{ $gbpChange >= 0 ? 'bg-emerald-500/15 text-emerald-400 border border-emerald-400/40' : 'bg-red-500/15 text-red-400 border border-red-400/40' }}">
                                {{ $gbpChange >= 0 ? '↗' : '↘' }} {{ number_format(abs($gbpChange), 2) }}%
                            </span>
                        @endif
                    </div>
                    @if(($forexPrices['gbp']['success'] ?? false))
                        <p class="text-xl font-bold text-white tracking-tight">
                            Rp {{ number_format($forexPrices['gbp']['rate'], 0, ',', '.') }}
                        </p>
                    @else
                        <p class="text-sm text-red-400">Data tidak tersedia</p>
                    @endif
                </button>

                <button type="button"
                        data-type="forex"
                        data-symbol="sgdidr"
                        onclick="window.location.href = '{{ route('edukasi.asset.detail', ['type' => 'forex', 'symbol' => 'sgdidr']) }}'"
                        class="asset-card group text-left relative overflow-hidden rounded-2xl p-5 min-h-[136px] bg-white/[0.03] backdrop-blur-xl border border-white/10 transition-all duration-300 hover:scale-[1.02] hover:border-cyan-400/60 hover:shadow-[0_0_40px_rgba(6,182,212,0.35)]">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-cyan-500/20 border border-cyan-400/40 flex items-center justify-center shadow-[0_0_15px_rgba(6,182,212,0.4)]">
                                <span class="text-cyan-400 font-bold text-sm">S$</span>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-cyan-400">Dolar Singapura</p>
                                <p class="text-[10px] text-gray-500 font-mono">SGD/IDR</p>
                            </div>
                        </div>
                        @if(($forexPrices['sgd']['success'] ?? false))
                            @php $sgdChange = $forexPrices['sgd']['change_percent'] ?? 0; @endphp
                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-[10px] font-semibold {{ $sgdChange >= 0 ? 'bg-emerald-500/15 text-emerald-400 border border-emerald-400/40' : 'bg-red-500/15 text-red-400 border border-red-400/40' }}">
                                {{ $sgdChange >= 0 ? '↗' : '↘' }} {{ number_format(abs($sgdChange), 2) }}%
                            </span>
                        @endif
                    </div>
                    @if(($forexPrices['sgd']['success'] ?? false))
                        <p class="text-xl font-bold text-white tracking-tight">
                            Rp {{ number_format($forexPrices['sgd']['rate'], 0, ',', '.') }}
                        </p>
                    @else
                        <p class="text-sm text-red-400">Data tidak tersedia</p>
                    @endif
                </button>
            </div>

            <div class="fade-in-up rounded-2xl p-6 bg-white/[0.03] backdrop-blur-xl border border-white/10">
                <div id="forex-chart" class="w-full h-80"></div>
            </div>

            <div class="fade-in-up rounded-2xl p-6 bg-white/[0.03] backdrop-blur-xl border border-white/10">
                <h3 class="text-base font-bold text-white mb-4">Detail Kurs</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-white/[0.02]">
                            <tr>
                                <th class="px-4 py-3 text-left text-gray-400 text-[10px] uppercase tracking-wider">Mata Uang</th>
                                <th class="px-4 py-3 text-right text-gray-400 text-[10px] uppercase tracking-wider">Kurs ke IDR</th>
                                <th class="px-4 py-3 text-right text-gray-400 text-[10px] uppercase tracking-wider">Perubahan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @foreach(['usd','eur','jpy','cny','gbp','sgd'] as $code)
                                @php $forexRow = $forexPrices[$code] ?? ['success' => false]; @endphp
                                <tr class="hover:bg-white/[0.03]">
                                    <td class="px-4 py-3 text-white font-medium">{{ strtoupper($code) }}/IDR</td>
                                    <td class="px-4 py-3 text-right text-gray-200">
                                        @if($forexRow['success'] ?? false)
                                            Rp {{ number_format($forexRow['rate'], 0, ',', '.') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-right {{ ($forexRow['change_percent'] ?? 0) >= 0 ? 'text-emerald-400' : 'text-red-400' }}">
                                        @if($forexRow['success'] ?? false)
                                            {{ ($forexRow['change_percent'] ?? 0) >= 0 ? '↗' : '↘' }} {{ number_format(abs($forexRow['change_percent'] ?? 0), 2) }}%
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="fade-in-up">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-white/5 border border-white/10 text-gray-200 rounded-xl text-xs uppercase tracking-widest hover:bg-cyan-500/10 hover:border-cyan-400/40">
                    ← Kembali ke Dasbor
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.45.1/dist/apexcharts.min.js"></script>
    <script>
        const chartData = {
            usd: { label: 'USD/IDR', symbol: 'USD', color: '#10b981', prices: @json($forexHistory['usd']['prices'] ?? []), key: 'price' },
            eur: { label: 'EUR/IDR', symbol: 'EUR', color: '#3b82f6', prices: @json($forexHistory['eur']['prices'] ?? []), key: 'price' },
            jpy: { label: 'JPY/IDR', symbol: 'JPY', color: '#ef4444', prices: @json($forexHistory['jpy']['prices'] ?? []), key: 'price' },
            cny: { label: 'CNY/IDR', symbol: 'CNY', color: '#eab308', prices: @json($forexHistory['cny']['prices'] ?? []), key: 'price' },
            gbp: { label: 'GBP/IDR', symbol: 'GBP', color: '#a855f7', prices: @json($forexHistory['gbp']['prices'] ?? []), key: 'price' },
            sgd: { label: 'SGD/IDR', symbol: 'SGD', color: '#06b6d4', prices: @json($forexHistory['sgd']['prices'] ?? []), key: 'price' },
        };

        let chart = null;
        let activeAsset = 'usd';

        function buildOptions(asset) {
            const cfg = chartData[asset];
            const categories = (cfg.prices || []).map(p => p.date);
            const values = (cfg.prices || []).map(p => parseFloat(p[cfg.key]));
            return {
                chart: { type: 'area', height: 320, background: 'transparent', toolbar: { show: true }, animations: { enabled: true, speed: 700 } },
                series: [{ name: cfg.label, data: values }],
                xaxis: { categories: categories, labels: { style: { colors: '#6b7280', fontSize: '10px' } } },
                yaxis: { labels: { style: { colors: '#6b7280', fontSize: '10px' }, formatter: val => 'Rp ' + new Intl.NumberFormat('id-ID', { maximumFractionDigits: 0 }).format(val) } },
                colors: [cfg.color],
                stroke: { curve: 'smooth', width: 3, colors: [cfg.color] },
                fill: { type: 'gradient', gradient: { opacityFrom: 0.5, opacityTo: 0.02, stops: [0, 100] } },
                grid: { borderColor: 'rgba(139,92,246,0.1)', strokeDashArray: 4 },
                tooltip: { theme: 'dark', y: { formatter: val => 'Rp ' + new Intl.NumberFormat('id-ID', { maximumFractionDigits: 0 }).format(val) } },
                dataLabels: { enabled: false },
            };
        }

        function switchChart(asset) {
            activeAsset = asset;
            chart.updateOptions(buildOptions(asset), false, true);
        }

        document.addEventListener('DOMContentLoaded', () => {
            chart = new ApexCharts(document.querySelector('#forex-chart'), buildOptions('usd'));
            chart.render();
        });
    </script>
</x-app-layout>