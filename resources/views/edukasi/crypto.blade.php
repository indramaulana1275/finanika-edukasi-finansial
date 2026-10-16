<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Crypto Market - Detail Realtime') }}
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
                    <p class="text-sm text-cyan-300/80 tracking-wide">Detail Pasar Kripto</p>
                    <h1 class="text-3xl md:text-4xl font-extrabold mt-1 tracking-tight gradient-text">
                        Crypto Market
                    </h1>
                    <p class="text-sm text-gray-400 mt-2">5 aset kripto utama dari CoinGecko dengan chart dinamis.</p>
                </div>
            </div>

            @php
                $cryptoCards = [
                    ['key' => 'btc', 'name' => 'Bitcoin', 'symbol' => 'BTC', 'asset' => 'bitcoin', 'color' => 'orange', 'icon' => '₿'],
                    ['key' => 'eth', 'name' => 'Ethereum', 'symbol' => 'ETH', 'asset' => 'ethereum', 'color' => 'violet', 'icon' => 'Ξ'],
                    ['key' => 'sol', 'name' => 'Solana', 'symbol' => 'SOL', 'asset' => 'solana', 'color' => 'pink', 'icon' => '◎'],
                    ['key' => 'bnb', 'name' => 'BNB', 'symbol' => 'BNB', 'asset' => 'bnb', 'color' => 'amber', 'icon' => 'B'],
                    ['key' => 'ada', 'name' => 'Cardano', 'symbol' => 'ADA', 'asset' => 'cardano', 'color' => 'sky', 'icon' => '₳'],
                    ['key' => 'bch', 'name' => 'Bitcoin Cash', 'symbol' => 'BCH', 'asset' => 'bitcoin-cash', 'color' => 'emerald', 'icon' => 'BCH'],
                    ['key' => 'ltc', 'name' => 'Litecoin', 'symbol' => 'LTC', 'asset' => 'litecoin', 'color' => 'slate', 'icon' => 'Ł'],
                    ['key' => 'doge', 'name' => 'Dogecoin', 'symbol' => 'DOGE', 'asset' => 'dogecoin', 'color' => 'amber', 'icon' => 'Ð'],
                    ['key' => 'dot', 'name' => 'Polkadot', 'symbol' => 'DOT', 'asset' => 'polkadot', 'color' => 'pink', 'icon' => 'DOT'],
                    ['key' => 'link', 'name' => 'Chainlink', 'symbol' => 'LINK', 'asset' => 'chainlink', 'color' => 'blue', 'icon' => 'LINK'],
                ];
                $cryptoStyles = [
                    'orange' => [
                        'card' => 'transition-all duration-300 hover:scale-[1.02] hover:border-orange-400/60 hover:shadow-[0_0_40px_rgba(249,115,22,0.35)]',
                        'box' => 'w-10 h-10 rounded-xl bg-orange-500/20 border border-orange-400/40 flex items-center justify-center shadow-[0_0_15px_rgba(249,115,22,0.4)]',
                        'text' => 'text-orange-400 font-bold text-xs',
                    ],
                    'violet' => [
                        'card' => 'transition-all duration-300 hover:scale-[1.02] hover:border-violet-400/60 hover:shadow-[0_0_40px_rgba(139,92,246,0.35)]',
                        'box' => 'w-10 h-10 rounded-xl bg-violet-500/20 border border-violet-400/40 flex items-center justify-center shadow-[0_0_15px_rgba(139,92,246,0.4)]',
                        'text' => 'text-violet-400 font-bold text-xs',
                    ],
                    'pink' => [
                        'card' => 'transition-all duration-300 hover:scale-[1.02] hover:border-pink-400/60 hover:shadow-[0_0_40px_rgba(236,72,153,0.35)]',
                        'box' => 'w-10 h-10 rounded-xl bg-pink-500/20 border border-pink-400/40 flex items-center justify-center shadow-[0_0_15px_rgba(236,72,153,0.4)]',
                        'text' => 'text-pink-400 font-bold text-xs',
                    ],
                    'amber' => [
                        'card' => 'transition-all duration-300 hover:scale-[1.02] hover:border-amber-400/60 hover:shadow-[0_0_40px_rgba(245,158,11,0.35)]',
                        'box' => 'w-10 h-10 rounded-xl bg-amber-500/20 border border-amber-400/40 flex items-center justify-center shadow-[0_0_15px_rgba(245,158,11,0.4)]',
                        'text' => 'text-amber-400 font-bold text-xs',
                    ],
                    'sky' => [
                        'card' => 'transition-all duration-300 hover:scale-[1.02] hover:border-sky-400/60 hover:shadow-[0_0_40px_rgba(14,165,233,0.35)]',
                        'box' => 'w-10 h-10 rounded-xl bg-sky-500/20 border border-sky-400/40 flex items-center justify-center shadow-[0_0_15px_rgba(14,165,233,0.4)]',
                        'text' => 'text-sky-400 font-bold text-xs',
                    ],
                    'emerald' => [
                        'card' => 'transition-all duration-300 hover:scale-[1.02] hover:border-emerald-400/60 hover:shadow-[0_0_40px_rgba(16,185,129,0.35)]',
                        'box' => 'w-10 h-10 rounded-xl bg-emerald-500/20 border border-emerald-400/40 flex items-center justify-center shadow-[0_0_15px_rgba(16,185,129,0.4)]',
                        'text' => 'text-emerald-400 font-bold text-xs',
                    ],
                    'slate' => [
                        'card' => 'transition-all duration-300 hover:scale-[1.02] hover:border-slate-400/60 hover:shadow-[0_0_40px_rgba(100,116,139,0.35)]',
                        'box' => 'w-10 h-10 rounded-xl bg-slate-500/20 border border-slate-400/40 flex items-center justify-center shadow-[0_0_15px_rgba(100,116,139,0.4)]',
                        'text' => 'text-slate-400 font-bold text-xs',
                    ],
                ];
                $nameClassMap = [
                    'orange' => 'text-orange-400',
                    'violet' => 'text-violet-400',
                    'pink' => 'text-pink-400',
                    'amber' => 'text-amber-400',
                    'sky' => 'text-sky-400',
                    'emerald' => 'text-emerald-400',
                    'slate' => 'text-slate-400',
                ];
            @endphp

            <div class="fade-in-up grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4">
                @foreach($cryptoCards as $cryptoCard)
                    @php
                        $price = $marketPrices[$cryptoCard['asset']] ?? ['success' => false];
                        $change = $price['change_24h'] ?? 0;
                        $style = $cryptoStyles[$cryptoCard['color']] ?? $cryptoStyles['orange'];
                        $nameClass = $nameClassMap[$cryptoCard['color']] ?? 'text-gray-200';
                    @endphp
                    <button type="button"
                            data-type="crypto"
                            data-symbol="{{ $cryptoCard['key'] }}"
                            onclick="window.location.href = '{{ route('edukasi.asset.detail', ['type' => 'crypto', 'symbol' => $cryptoCard['key']]) }}'"
                            class="asset-card group text-left relative overflow-hidden rounded-2xl p-5 min-h-[140px]
                                   bg-white/[0.03] backdrop-blur-xl border border-white/10 {{ $style['card'] }}">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="{{ $style['box'] }}">
                                    <span class="{{ $style['text'] }}">{{ $cryptoCard['icon'] }}</span>
                                </div>
                                <div>
                                    <p class="text-sm font-bold {{ $nameClass }}">{{ $cryptoCard['name'] }}</p>
                                    <p class="text-[10px] text-gray-500 font-mono">{{ $cryptoCard['symbol'] }}</p>
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
                                ${{ number_format($price['price_usd'], 2, '.', ',') }}
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
                    <h3 class="text-base font-bold text-white mb-4">Detail Crypto</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="bg-white/[0.02]">
                                <tr>
                                    <th class="px-4 py-3 text-left text-gray-400 text-[10px] uppercase tracking-wider">Aset</th>
                                    <th class="px-4 py-3 text-right text-gray-400 text-[10px] uppercase tracking-wider">Harga USD</th>
                                    <th class="px-4 py-3 text-right text-gray-400 text-[10px] uppercase tracking-wider">Perubahan 24 Jam</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @foreach($cryptoCards as $cryptoCard)
                                    @php $price = $marketPrices[$cryptoCard['asset']] ?? ['success' => false]; @endphp
                                    <tr class="hover:bg-white/[0.03] transition">
                                        <td class="px-4 py-3 text-white font-medium">
                                            {{ $cryptoCard['name'] }} ({{ $cryptoCard['symbol'] }})
                                        </td>
                                        <td class="px-4 py-3 text-right text-gray-200">
                                            @if($price['success'] ?? false)
                                                ${{ number_format($price['price_usd'], 2, '.', ',') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-right {{ ($price['change_24h'] ?? 0) >= 0 ? 'text-emerald-400' : 'text-red-400' }}">
                                            @if($price['success'] ?? false)
                                                {{ ($price['change_24h'] ?? 0) >= 0 ? '↗' : '↘' }} {{ number_format(abs($price['change_24h'] ?? 0), 2) }}%
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
            btc: { label: 'Pergerakan Bitcoin', symbol: 'BTC', color: '#f97316', gradientTo: '#fb923c', prices: @json($btcHistory['prices'] ?? []), key: 'price_usd', prefix: '$', change: {{ $marketPrices['bitcoin']['change_24h'] ?? 0 }}, isCurrencyIDR: false },
            eth: { label: 'Pergerakan Ethereum', symbol: 'ETH', color: '#8b5cf6', gradientTo: '#6366f1', prices: @json($ethHistory['prices'] ?? []), key: 'price_usd', prefix: '$', change: {{ $marketPrices['ethereum']['change_24h'] ?? 0 }}, isCurrencyIDR: false },
            sol: { label: 'Pergerakan Solana', symbol: 'SOL', color: '#ec4899', gradientTo: '#f43f5e', prices: @json($solHistory['prices'] ?? []), key: 'price_usd', prefix: '$', change: {{ $marketPrices['solana']['change_24h'] ?? 0 }}, isCurrencyIDR: false },
            bnb: { label: 'Pergerakan BNB', symbol: 'BNB', color: '#f59e0b', gradientTo: '#facc15', prices: @json($bnbHistory['prices'] ?? []), key: 'price_usd', prefix: '$', change: {{ $marketPrices['bnb']['change_24h'] ?? 0 }}, isCurrencyIDR: false },
            ada: { label: 'Pergerakan Cardano', symbol: 'ADA', color: '#0ea5e9', gradientTo: '#38bdf8', prices: @json($adaHistory['prices'] ?? []), key: 'price_usd', prefix: '$', change: {{ $marketPrices['cardano']['change_24h'] ?? 0 }}, isCurrencyIDR: false },
            bch: { label: 'Pergerakan Bitcoin Cash', symbol: 'BCH', color: '#22c55e', gradientTo: '#16a34a', prices: @json($bchHistory['prices'] ?? []), key: 'price_usd', prefix: '$', change: {{ $marketPrices['bitcoin-cash']['change_24h'] ?? 0 }}, isCurrencyIDR: false },
            ltc: { label: 'Pergerakan Litecoin', symbol: 'LTC', color: '#64748b', gradientTo: '#475569', prices: @json($ltcHistory['prices'] ?? []), key: 'price_usd', prefix: '$', change: {{ $marketPrices['litecoin']['change_24h'] ?? 0 }}, isCurrencyIDR: false },
            doge: { label: 'Pergerakan Dogecoin', symbol: 'DOGE', color: '#eab308', gradientTo: '#ca8a04', prices: @json($dogeHistory['prices'] ?? []), key: 'price_usd', prefix: '$', change: {{ $marketPrices['dogecoin']['change_24h'] ?? 0 }}, isCurrencyIDR: false },
            dot: { label: 'Pergerakan Polkadot', symbol: 'DOT', color: '#ec4899', gradientTo: '#f472b6', prices: @json($dotHistory['prices'] ?? []), key: 'price_usd', prefix: '$', change: {{ $marketPrices['polkadot']['change_24h'] ?? 0 }}, isCurrencyIDR: false },
            link: { label: 'Pergerakan Chainlink', symbol: 'LINK', color: '#2563eb', gradientTo: '#3b82f6', prices: @json($linkHistory['prices'] ?? []), key: 'price_usd', prefix: '$', change: {{ $marketPrices['chainlink']['change_24h'] ?? 0 }}, isCurrencyIDR: false },
        };

        let chart = null;
        let activeAsset = 'btc';

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
                grid: { borderColor: 'rgba(139,92,246,0.1)', strokeDashArray: 4 },
                tooltip: { theme: 'dark', y: { formatter: val => cfg.prefix + new Intl.NumberFormat('en-US', { maximumFractionDigits: 2 }).format(val) } },
                dataLabels: { enabled: false },
            };
        }

        function switchChart(asset) {
            activeAsset = asset;
            chart.updateOptions(buildOptions(asset), false, true);
        }

        async function loadPeriod(days) {
            try {
                const keys = ['btc', 'eth', 'sol', 'bnb', 'ada', 'bch', 'ltc', 'doge', 'dot', 'link'];
                const responses = await Promise.all(keys.map(key => fetch(`/api/market/history?asset=${key}&days=${days}`, { headers: { 'Accept': 'application/json' } })));
                const data = await Promise.all(responses.map(r => r.json()));
                keys.forEach((key, i) => {
                    if (data[i].prices) chartData[key].prices = data[i].prices;
                });
                chart.updateOptions(buildOptions(activeAsset), false, true);
            } catch (err) {
                console.error('Gagal load period:', err);
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            chart = new ApexCharts(document.querySelector('#market-chart'), buildOptions('btc'));
            chart.render();
            switchChart('btc');
        });
    </script>
</x-app-layout>
