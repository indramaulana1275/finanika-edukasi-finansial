<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Komoditas - Detail Realtime') }}
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
                    <p class="text-sm text-cyan-300/80 tracking-wide">Detail Komoditas</p>
                    <h1 class="text-3xl md:text-4xl font-extrabold mt-1 tracking-tight gradient-text">
                        Komoditas
                    </h1>
                    <p class="text-sm text-gray-400 mt-2">Emas, Perak, Minyak Mentah, dan Gas Alam dari xaus.com dan Yahoo Finance.</p>
                </div>
            </div>

            @php
                $commodityCards = [
                    ['key' => 'gold', 'name' => 'Emas', 'symbol' => 'XAU', 'asset' => 'gold', 'color' => 'amber', 'icon' => 'Au'],
                    ['key' => 'silver', 'name' => 'Perak', 'symbol' => 'XAG', 'asset' => 'silver', 'color' => 'slate', 'icon' => 'Ag'],
                    ['key' => 'crude-oil', 'name' => 'Minyak Mentah WTI', 'symbol' => 'CL', 'asset' => 'crude-oil', 'color' => 'amber', 'icon' => 'OIL'],
                    ['key' => 'natural-gas', 'name' => 'Gas Alam', 'symbol' => 'NG', 'asset' => 'natural-gas', 'color' => 'emerald', 'icon' => 'GAS'],
                ];
                $commodityStyles = [
                    'amber' => [
                        'card' => 'transition-all duration-300 hover:scale-[1.02] hover:border-amber-400/60 hover:shadow-[0_0_40px_rgba(245,158,11,0.35)]',
                        'box' => 'w-10 h-10 rounded-xl bg-amber-500/20 border border-amber-400/40 flex items-center justify-center shadow-[0_0_15px_rgba(245,158,11,0.4)]',
                        'text' => 'text-amber-400 font-bold text-xs',
                    ],
                    'slate' => [
                        'card' => 'transition-all duration-300 hover:scale-[1.02] hover:border-slate-400/60 hover:shadow-[0_0_40px_rgba(100,116,139,0.35)]',
                        'box' => 'w-10 h-10 rounded-xl bg-slate-500/20 border border-slate-400/40 flex items-center justify-center shadow-[0_0_15px_rgba(100,116,139,0.4)]',
                        'text' => 'text-slate-400 font-bold text-xs',
                    ],
                    'emerald' => [
                        'card' => 'transition-all duration-300 hover:scale-[1.02] hover:border-emerald-400/60 hover:shadow-[0_0_40px_rgba(16,185,129,0.35)]',
                        'box' => 'w-10 h-10 rounded-xl bg-emerald-500/20 border border-emerald-400/40 flex items-center justify-center shadow-[0_0_15px_rgba(16,185,129,0.4)]',
                        'text' => 'text-emerald-400 font-bold text-xs',
                    ],
                ];
                $nameClassMap = [
                    'amber' => 'text-amber-400',
                    'slate' => 'text-slate-400',
                    'emerald' => 'text-emerald-400',
                ];
            @endphp

            <div class="fade-in-up grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach($commodityCards as $commodityCard)
                    @php
                        $price = $currentPrices[$commodityCard['asset']] ?? ['success' => false];
                        $change = $price['change_percent'] ?? 0;
                        $style = $commodityStyles[$commodityCard['color']] ?? $commodityStyles['slate'];
                        $nameClass = $nameClassMap[$commodityCard['color']] ?? 'text-gray-200';
                    @endphp
                    <button type="button"
                            data-type="commodity"
                            data-symbol="{{ $commodityCard['key'] }}"
                            onclick="window.location.href = '{{ route('edukasi.asset.detail', ['type' => 'commodity', 'symbol' => $commodityCard['key']]) }}'"
                            class="asset-card group text-left relative overflow-hidden rounded-2xl p-5 min-h-[140px]
                                   bg-white/[0.03] backdrop-blur-xl border border-white/10 {{ $style['card'] }}">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="{{ $style['box'] }}">
                                    <span class="{{ $style['text'] }}">{{ $commodityCard['icon'] }}</span>
                                </div>
                                <div>
                                    <p class="text-sm font-bold {{ $nameClass }}">{{ $commodityCard['name'] }}</p>
                                    <p class="text-[10px] text-gray-500 font-mono">{{ $commodityCard['symbol'] }}</p>
                                </div>
                            </div>
                            @if($price['success'] ?? false)
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-[10px] font-semibold {{ $change >= 0 ? 'bg-emerald-500/15 text-emerald-400 border border-emerald-400/40' : 'bg-red-500/15 text-red-400 border border-red-400/40' }}">
                                    {{ $change >= 0 ? '↗' : '↘' }} {{ number_format(abs($change), 2) }}%
                                </span>
                            @endif
                        </div>
                        @if($price['success'] ?? false)
                            @php
                                $priceValue = $price['price_usd'] ?? $price['price'] ?? 0;
                            @endphp
                            <p class="text-xl font-bold text-white tracking-tight">
                                ${{ number_format($priceValue, 2, '.', ',') }}
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
                    <h3 class="text-base font-bold text-white mb-4">Detail Komoditas</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="bg-white/[0.02]">
                                <tr>
                                    <th class="px-4 py-3 text-left text-gray-400 text-[10px] uppercase tracking-wider">Komoditas</th>
                                    <th class="px-4 py-3 text-right text-gray-400 text-[10px] uppercase tracking-wider">Harga USD</th>
                                    <th class="px-4 py-3 text-right text-gray-400 text-[10px] uppercase tracking-wider">Perubahan 24 Jam</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @foreach($commodityCards as $commodityCard)
                                    @php $price = $currentPrices[$commodityCard['asset']] ?? ['success' => false]; @endphp
                                    @php
                                        $priceValue = $price['price_usd'] ?? $price['price'] ?? 0;
                                        $priceChange = $price['change_percent'] ?? 0;
                                    @endphp
                                    <tr class="hover:bg-white/[0.03] transition">
                                        <td class="px-4 py-3 text-white font-medium">
                                            {{ $commodityCard['name'] }} ({{ $commodityCard['symbol'] }})
                                        </td>
                                        <td class="px-4 py-3 text-right text-gray-200">
                                            @if($price['success'] ?? false)
                                                ${{ number_format($priceValue, 2, '.', ',') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-right {{ $priceChange >= 0 ? 'text-emerald-400' : 'text-red-400' }}">
                                            @if($price['success'] ?? false)
                                                {{ $priceChange >= 0 ? '↗' : '↘' }} {{ number_format(abs($priceChange), 2) }}%
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
            gold: { label: 'Pergerakan Emas', symbol: 'XAU', color: '#f59e0b', gradientTo: '#d97706', prices: @json($goldHistory['prices'] ?? []), key: 'price_usd', prefix: '$', change: {{ $currentPrices['gold']['change_percent'] ?? 0 }}, isCurrencyIDR: false },
            silver: { label: 'Pergerakan Perak', symbol: 'XAG', color: '#64748b', gradientTo: '#475569', prices: @json($silverHistory['prices'] ?? []), key: 'price', prefix: '$', change: {{ $currentPrices['silver']['change_percent'] ?? 0 }}, isCurrencyIDR: false },
            'crude-oil': { label: 'Pergerakan Minyak Mentah', symbol: 'CL', color: '#eab308', gradientTo: '#ca8a04', prices: @json($crudeOilHistory['prices'] ?? []), key: 'price', prefix: '$', change: {{ $currentPrices['crude-oil']['change_percent'] ?? 0 }}, isCurrencyIDR: false },
            'natural-gas': { label: 'Pergerakan Gas Alam', symbol: 'NG', color: '#10b981', gradientTo: '#059669', prices: @json($naturalGasHistory['prices'] ?? []), key: 'price', prefix: '$', change: {{ $currentPrices['natural-gas']['change_percent'] ?? 0 }}, isCurrencyIDR: false },
        };

        let chart = null;
        let activeAsset = 'gold';

        function buildOptions(asset) {
            const cfg = chartData[asset];
            const categories = (cfg.prices || []).map(p => p.date);
            const values = (cfg.prices || []).map(p => parseFloat(p[cfg.key]));
            return {
                chart: { type: 'area', height: 360, background: 'transparent', toolbar: { show: true }, zoom: { enabled: false }, animations: { enabled: true, speed: 700 } },
                series: [{ name: cfg.symbol, data: values }],
                xaxis: { categories: categories, labels: { style: { colors: '#6b7280', fontSize: '10px' } } },
                yaxis: { labels: { style: { colors: '#6b7280', fontSize: '10px' }, formatter: val => cfg.prefix + new Intl.NumberFormat('en-US', { maximumFractionDigits: 2 }).format(val) } },
                colors: [cfg.color],
                stroke: { curve: 'smooth', width: 3, colors: [cfg.color] },
                fill: { type: 'gradient', gradient: { opacityFrom: 0.5, opacityTo: 0.02, stops: [0, 100] } },
                tooltip: { theme: 'dark', y: { formatter: val => cfg.prefix + new Intl.NumberFormat('en-US', { maximumFractionDigits: 2 }).format(val) } },
                dataLabels: { enabled: false },
            };
        }

        function switchChart(asset) {
            activeAsset = asset;
            chart.updateOptions(buildOptions(asset), false, true);
        }

        document.addEventListener('DOMContentLoaded', () => {
            chart = new ApexCharts(document.querySelector('#market-chart'), buildOptions('gold'));
            chart.render();
            switchChart('gold');
        });
    </script>
</x-app-layout>