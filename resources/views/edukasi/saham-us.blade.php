<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Saham US - Detail Realtime') }}
            </h2>
            <a href="{{ route('dashboard') }}" class="text-xs text-cyan-400 hover:text-cyan-300">
                ← Kembali ke Dasbor
            </a>
        </div>
    </x-slot>

    <div class="relative min-h-screen py-8 overflow-hidden">
        <div class="relative max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="fade-in-up flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <p class="text-sm text-cyan-300/80 tracking-wide">Detail Pasar Saham US</p>
                    <h1 class="text-3xl md:text-4xl font-extrabold mt-1 tracking-tight gradient-text">
                        Saham US
                    </h1>
                    <p class="text-sm text-gray-400 mt-2">3 indeks saham Amerika Serikat dari Yahoo Finance dengan chart dinamis.</p>
                </div>
            </div>

            @php
                $sahamCards = [
                    ['key' => 'nasdaq', 'name' => 'NASDAQ Composite', 'symbol' => 'IXIC', 'asset' => 'nasdaq', 'color' => 'cyan', 'icon' => 'N'],
                    ['key' => 'sp500', 'name' => 'S&P 500', 'symbol' => 'GSPC', 'asset' => 'sp500', 'color' => 'emerald', 'icon' => 'S'],
                    ['key' => 'dowjones', 'name' => 'Dow Jones Industrial Average', 'symbol' => 'DJI', 'asset' => 'dowjones', 'color' => 'blue', 'icon' => 'D'],
                    ['key' => 'russell2000', 'name' => 'Russell 2000', 'symbol' => 'RUT', 'asset' => 'russell2000', 'color' => 'purple', 'icon' => 'R'],
                    ['key' => 'vix', 'name' => 'VIX Volatility Index', 'symbol' => 'VIX', 'asset' => 'vix', 'color' => 'red', 'icon' => 'V'],
                ];
                $sahamStyles = [
                    'cyan' => [
                        'card' => 'transition-all duration-300 hover:scale-[1.02] hover:border-cyan-400/60 hover:shadow-[0_0_40px_rgba(6,182,212,0.35)]',
                        'box' => 'w-10 h-10 rounded-xl bg-cyan-500/20 border border-cyan-400/40 flex items-center justify-center shadow-[0_0_15px_rgba(6,182,212,0.4)]',
                        'text' => 'text-cyan-400 font-bold text-xs',
                    ],
                    'emerald' => [
                        'card' => 'transition-all duration-300 hover:scale-[1.02] hover:border-emerald-400/60 hover:shadow-[0_0_40px_rgba(16,185,129,0.35)]',
                        'box' => 'w-10 h-10 rounded-xl bg-emerald-500/20 border border-emerald-400/40 flex items-center justify-center shadow-[0_0_15px_rgba(16,185,129,0.4)]',
                        'text' => 'text-emerald-400 font-bold text-xs',
                    ],
                    'blue' => [
                        'card' => 'transition-all duration-300 hover:scale-[1.02] hover:border-blue-400/60 hover:shadow-[0_0_40px_rgba(59,130,246,0.35)]',
                        'box' => 'w-10 h-10 rounded-xl bg-blue-500/20 border border-blue-400/40 flex items-center justify-center shadow-[0_0_15px_rgba(59,130,246,0.4)]',
                        'text' => 'text-blue-400 font-bold text-xs',
                    ],
                    'purple' => [
                        'card' => 'transition-all duration-300 hover:scale-[1.02] hover:border-purple-400/60 hover:shadow-[0_0_40px_rgba(168,85,247,0.35)]',
                        'box' => 'w-10 h-10 rounded-xl bg-purple-500/20 border border-purple-400/40 flex items-center justify-center shadow-[0_0_15px_rgba(168,85,247,0.4)]',
                        'text' => 'text-purple-400 font-bold text-xs',
                    ],
                    'red' => [
                        'card' => 'transition-all duration-300 hover:scale-[1.02] hover:border-red-400/60 hover:shadow-[0_0_40px_rgba(239,68,68,0.35)]',
                        'box' => 'w-10 h-10 rounded-xl bg-red-500/20 border border-red-400/40 flex items-center justify-center shadow-[0_0_15px_rgba(239,68,68,0.4)]',
                        'text' => 'text-red-400 font-bold text-xs',
                    ],
                ];
                $nameClassMap = [
                    'cyan' => 'text-cyan-400',
                    'emerald' => 'text-emerald-400',
                    'blue' => 'text-blue-400',
                    'purple' => 'text-purple-400',
                    'red' => 'text-red-400',
                ];
            @endphp

            <div class="fade-in-up grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($sahamCards as $sahamCard)
                    @php
                        $price = $currentPrices[$sahamCard['asset']] ?? ['success' => false];
                        $change = $price['change_percent'] ?? 0;
                        $style = $sahamStyles[$sahamCard['color']] ?? $sahamStyles['cyan'];
                        $nameClass = $nameClassMap[$sahamCard['color']] ?? 'text-gray-200';
                    @endphp
                    <button type="button"
                            data-type="index"
                            data-symbol="{{ $sahamCard['key'] }}"
                            onclick="window.location.href = '{{ route('edukasi.asset.detail', ['type' => 'index', 'symbol' => $sahamCard['key']]) }}'"
                            class="asset-card group text-left relative overflow-hidden rounded-2xl p-5 min-h-[140px]
                                   bg-white/[0.03] backdrop-blur-xl border border-white/10 {{ $style['card'] }}">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="{{ $style['box'] }}">
                                    <span class="{{ $style['text'] }}">{{ $sahamCard['icon'] }}</span>
                                </div>
                                <div>
                                    <p class="text-sm font-bold {{ $nameClass }}">{{ $sahamCard['name'] }}</p>
                                    <p class="text-[10px] text-gray-500 font-mono">{{ $sahamCard['symbol'] }}</p>
                                </div>
                            </div>
                            @if($price['success'] ?? false)
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-[10px] font-semibold {{ $change >= 0 ? 'bg-emerald-500/15 text-emerald-400 border border-emerald-400/40' : 'bg-red-500/15 text-red-400 border border-red-400/40' }}">
                                    {{ $change >= 0 ? '↗' : '↘' }} {{ number_format(abs($change), 2) }}%
                                </span>
                            @endif
                        </div>
                        @if($price['success'] ?? false)
                            <p class="text-xl font-bold text-white tracking-tight">
                                ${{ number_format($price['price'], 2, '.', ',') }}
                            </p>
                        @else
                            <p class="text-sm text-red-400">Data tidak tersedia</p>
                        @endif
                        <div class="active-bar absolute bottom-0 left-0 h-[2px] w-0 transition-all duration-500"></div>
                    </button>
                @endforeach
            </div>

            <div class="fade-in-up grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="rounded-2xl p-6 bg-white/[0.03] backdrop-blur-xl border border-white/10">
                    <div id="market-chart" class="w-full h-[360px]"></div>
                </div>
                <div class="rounded-2xl p-6 bg-white/[0.03] backdrop-blur-xl border border-white/10">
                    <h3 class="text-base font-bold text-white mb-4">Detail Saham US</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="bg-white/[0.02]">
                                <tr>
                                    <th class="px-4 py-3 text-left text-gray-400 text-[10px] uppercase tracking-wider">Indeks</th>
                                    <th class="px-4 py-3 text-right text-gray-400 text-[10px] uppercase tracking-wider">Harga</th>
                                    <th class="px-4 py-3 text-right text-gray-400 text-[10px] uppercase tracking-wider">Perubahan 24 Jam</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @foreach($sahamCards as $sahamCard)
                                    @php $price = $currentPrices[$sahamCard['asset']] ?? ['success' => false]; @endphp
                                    <tr class="hover:bg-white/[0.03] transition">
                                        <td class="px-4 py-3 text-white font-medium">{{ $sahamCard['name'] }} ({{ $sahamCard['symbol'] }})</td>
                                        <td class="px-4 py-3 text-right text-gray-200">
                                            @if($price['success'] ?? false)
                                                ${{ number_format($price['price'], 2, '.', ',') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-right {{ ($price['change_percent'] ?? 0) >= 0 ? 'text-emerald-400' : 'text-red-400' }}">
                                            @if($price['success'] ?? false)
                                                {{ ($price['change_percent'] ?? 0) >= 0 ? '↗' : '↘' }} {{ number_format(abs($price['change_percent'] ?? 0), 2) }}%
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
            nasdaq: { label: 'Pergerakan NASDAQ', symbol: 'IXIC', color: '#06b6d4', gradientTo: '#0891b2', prices: @json($nasdaqHistory['prices'] ?? []), key: 'price', prefix: '$', change: {{ $currentPrices['nasdaq']['change_percent'] ?? 0 }}, isCurrencyIDR: false },
            sp500: { label: 'Pergerakan S&P 500', symbol: 'GSPC', color: '#10b981', gradientTo: '#059669', prices: @json($sp500History['prices'] ?? []), key: 'price', prefix: '$', change: {{ $currentPrices['sp500']['change_percent'] ?? 0 }}, isCurrencyIDR: false },
            dowjones: { label: 'Pergerakan Dow Jones', symbol: 'DJI', color: '#3b82f6', gradientTo: '#2563eb', prices: @json($dowHistory['prices'] ?? []), key: 'price', prefix: '$', change: {{ $currentPrices['dowjones']['change_percent'] ?? 0 }}, isCurrencyIDR: false },
            russell2000: { label: 'Pergerakan Russell 2000', symbol: 'RUT', color: '#a855f7', gradientTo: '#7e22ce', prices: @json($russellHistory['prices'] ?? []), key: 'price', prefix: '$', change: {{ $currentPrices['russell2000']['change_percent'] ?? 0 }}, isCurrencyIDR: false },
            vix: { label: 'Pergerakan VIX', symbol: 'VIX', color: '#ef4444', gradientTo: '#b91c1c', prices: @json($vixHistory['prices'] ?? []), key: 'price', prefix: '$', change: {{ $currentPrices['vix']['change_percent'] ?? 0 }}, isCurrencyIDR: false },
        };

        let chart = null;
        let activeAsset = 'nasdaq';

        function buildOptions(asset) {
            const cfg = chartData[asset];
            const categories = (cfg.prices || []).map(p => p.date);
            const values = (cfg.prices || []).map(p => parseFloat(p[cfg.key]));
            return {
                chart: { type: 'area', height: 360, background: 'transparent', toolbar: { show: true }, zoom: { enabled: false }, animations: { enabled: true, speed: 700 } },
                series: [{ name: cfg.symbol, data: values }],
                xaxis: { categories, labels: { style: { colors: '#6b7280', fontSize: '10px' } } },
                yaxis: { labels: { style: { colors: '#6b7280', fontSize: '10px' }, formatter: val => cfg.prefix + new Intl.NumberFormat('en-US', { maximumFractionDigits: 2 }).format(val) } },
                colors: [cfg.color],
                stroke: { curve: 'smooth', width: 3, colors: [cfg.color] },
                fill: { type: 'gradient', gradient: { opacityFrom: 0.5, opacityTo: 0.02, stops: [0, 100] } },
                grid: { borderColor: 'rgba(139,92,246,0.1)', strokeDashArray: 4 },
                tooltip: { theme: 'dark', y: { formatter: val => cfg.prefix + new Intl.NumberFormat('en-US', { maximumFractionDigits: 2 }).format(val) } },
                dataLabels: { enabled: false },
            };
        }

        function switchChart(asset) {
            activeAsset = asset;
            chart.updateOptions(buildOptions(asset), false, true);
        }

        document.addEventListener('DOMContentLoaded', () => {
            chart = new ApexCharts(document.querySelector('#market-chart'), buildOptions('nasdaq'));
            chart.render();
            switchChart('nasdaq');
        });
    </script>
</x-app-layout>