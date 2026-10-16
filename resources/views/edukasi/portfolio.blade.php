<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <a href="{{ route('dashboard') }}" class="text-xs text-cyan-400 hover:text-cyan-300 mb-2 inline-block">
                    ← Kembali ke Dasbor
                </a>
                <nav class="flex text-xs space-x-2" aria-label="Breadcrumb">
                    <span class="text-gray-500">Dasbor</span>
                    <span class="text-cyan-400">&gt;</span>
                    <span class="text-white">Portfolio</span>
                </nav>
            </div>
            <a href="{{ route('dashboard') }}" class="text-xs text-cyan-400 hover:text-cyan-300">
                ← Kembali ke Dasbor
            </a>
        </div>
    </x-slot>

    <div class="relative min-h-screen py-8 overflow-hidden">
        <div class="relative max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Header Portfolio --}}
            <div class="fade-in-up fade-in-up-delay-1 relative overflow-hidden rounded-2xl p-6
                        bg-gradient-to-br from-cyan-950/60 via-slate-900/80 to-purple-950/40
                        backdrop-blur-2xl border border-cyan-500/30 shimmer-border
                        shadow-[0_0_50px_rgba(6,182,212,0.2)]">
                <div class="absolute -top-32 -right-32 w-80 h-80 bg-cyan-500/25 rounded-full blur-3xl float-anim"></div>
                <div class="absolute -bottom-32 -left-32 w-80 h-80 bg-purple-500/25 rounded-full blur-3xl float-anim"></div>

                <div class="relative z-10">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <p class="text-sm text-cyan-300/80 tracking-wide uppercase">Portfolio Investasi</p>
                            <h1 class="text-3xl md:text-4xl font-extrabold mt-1 tracking-tight text-white">
                                Portofolio Saya
                            </h1>
                            <p class="text-sm text-gray-400 mt-2">
                                Kelola dan analisis aset investasi kamu secara realtime.
                            </p>
                        </div>

                        @if(count($portfolioData) > 0)
                            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full
                                        bg-{{ $totalPL >= 0 ? 'emerald' : 'red' }}-500/10
                                        border border-{{ $totalPL >= 0 ? 'emerald' : 'red' }}-400/40">
                                <span class="w-1.5 h-1.5 rounded-full bg-{{ $totalPL >= 0 ? 'emerald' : 'red' }}-400"></span>
                                <span class="text-[10px] font-semibold {{ $totalPL >= 0 ? 'text-emerald-300' : 'text-red-300' }} uppercase tracking-wider">
                                    {{ $totalPL >= 0 ? 'Untung' : 'Rugi' }}
                                </span>
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Form Tambah Holding --}}
            <div class="fade-in-up fade-in-up-delay-2">
                <div class="rounded-2xl p-6 bg-white/[0.03] backdrop-blur-xl border border-white/10">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-base font-bold text-white">Tambah Aset ke Portofolio</h3>
                    </div>

                    <form action="{{ route('portfolio.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                        @csrf

                        <div class="col-span-1 md:col-span-1">
                            <label class="block text-xs text-gray-400 uppercase font-semibold mb-1">Aset</label>
                            <select name="asset_symbol" class="w-full bg-white/5 border border-white/10 rounded-xl px-3 py-2 text-sm text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent outline-none">
                                <option value="BTC">Bitcoin (BTC)</option>
                                <option value="ETH">Ethereum (ETH)</option>
                                <option value="SOL">Solana (SOL)</option>
                                <option value="BNB">BNB (BNB)</option>
                                <option value="ADA">Cardano (ADA)</option>
                                <option value="BCH">Bitcoin Cash (BCH)</option>
                                <option value="LTC">Litecoin (LTC)</option>
                                <option value="DOGE">Dogecoin (DOGE)</option>
                                <option value="DOT">Polkadot (DOT)</option>
                                <option value="LINK">Chainlink (LINK)</option>
                                <option value="JKSE">IHSG (^JKSE)</option>
                                <option value="NASDAQ">NASDAQ Composite</option>
                                <option value="SP500">S&P 500</option>
                                <option value="DJI">Dow Jones</option>
                                <option value="RUT">Russell 2000</option>
                                <option value="VIX">VIX Index</option>
                                <option value="USDIDR">USD/IDR</option>
                                <option value="EURIDR">EUR/IDR</option>
                                <option value="JPYIDR">JPY/IDR</option>
                                <option value="XAU">Emas (XAU)</option>
                                <option value="XAG">Perak (XAG)</option>
                                <option value="CL">Minyak Mentah (CL)</option>
                                <option value="NG">Gas Alam (NG)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs text-gray-400 uppercase font-semibold mb-1">Tipe Aset</label>
                            <select name="asset_type" class="w-full bg-white/5 border border-white/10 rounded-xl px-3 py-2 text-sm text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent outline-none">
                                <option value="crypto">Crypto</option>
                                <option value="index">Indeks Saham</option>
                                <option value="forex">Forex</option>
                                <option value="commodity">Komoditas</option>
                                <option value="stock">Saham (IHSG)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs text-gray-400 uppercase font-semibold mb-1">Jumlah (Unit)</label>
                            <input type="number" name="quantity" step="0.00000001" min="0" required
                                   class="w-full bg-white/5 border border-white/10 rounded-xl px-3 py-2 text-sm text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent outline-none"
                                   placeholder="0.00">
                        </div>

                        <div>
                            <label class="block text-xs text-gray-400 uppercase font-semibold mb-1">Harga Beli</label>
                            <input type="number" name="buy_price" step="0.01" min="0" required
                                   class="w-full bg-white/5 border border-white/10 rounded-xl px-3 py-2 text-sm text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent outline-none"
                                   placeholder="0.00">
                        </div>

                        <div class="md:col-span-1 flex">
                            <button type="submit" class="w-full bg-gradient-to-r from-cyan-600 to-blue-600 text-white text-sm font-semibold rounded-xl px-6 py-2 hover:from-cyan-500 hover:to-blue-500 shadow-[0_0_15px_rgba(6,182,212,0.4)] transition-all">
                                Tambah
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Portfolio Summary Cards --}}
            <div class="fade-in-up fade-in-up-delay-3 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="rounded-2xl p-5 bg-white/[0.03] backdrop-blur-xl border border-white/10">
                    <p class="text-xs text-gray-400 uppercase font-semibold mb-1">Total Nilai Portofolio</p>
                    <p class="text-2xl font-bold text-white">
                        Rp {{ number_format($totalValue, 0, ',', '.') }}
                    </p>
                    <p class="text-xs text-gray-500 mt-1">Harga sekarang</p>
                </div>

                <div class="rounded-2xl p-5 bg-white/[0.03] backdrop-blur-xl border border-white/10">
                    <p class="text-xs text-gray-400 uppercase font-semibold mb-1">Total Modal</p>
                    <p class="text-2xl font-bold text-white">
                        Rp {{ number_format($totalCost, 0, ',', '.') }}
                    </p>
                    <p class="text-xs text-gray-500 mt-1">Harga beli rata-rata</p>
                </div>

                <div class="rounded-2xl p-5 bg-white/[0.03] backdrop-blur-xl border border-white/10">
                    <p class="text-xs text-gray-400 uppercase font-semibold mb-1">Profit / Loss</p>
                    <p class="text-2xl font-bold {{ $totalPL >= 0 ? 'text-emerald-400' : 'text-red-400' }}">
                        {{ $totalPL >= 0 ? '+' : '' }}Rp {{ number_format(abs($totalPL), 0, ',', '.') }}
                    </p>
                    <p class="text-xs text-gray-500 mt-1">{{ $totalPLPercent >= 0 ? '+' : '' }}{{ number_format(abs($totalPLPercent), 2) }}%</p>
                </div>

                <div class="rounded-2xl p-5 bg-white/[0.03] backdrop-blur-xl border border-white/10">
                    <p class="text-xs text-gray-400 uppercase font-semibold mb-1">Jumlah Aset</p>
                    <p class="text-2xl font-bold text-white">{{ count($portfolioData) }}</p>
                    <p class="text-xs text-gray-500 mt-1">aset dalam portofolio</p>
                </div>
            </div>

            {{-- Charts Row: Donut + Bar --}}
            <div class="fade-in-up fade-in-up-delay-4 grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- Donut Chart: Alokasi --}}
                <div class="rounded-2xl p-6 bg-white/[0.03] backdrop-blur-xl border border-white/10">
                    <h3 class="text-base font-bold text-white mb-4">Alokasi Aset (%)</h3>
                    <div id="donut-chart" class="w-full h-[300px]"></div>
                </div>

                {{-- Bar Chart: Performa --}}
                <div class="rounded-2xl p-6 bg-white/[0.03] backdrop-blur-xl border border-white/10">
                    <h3 class="text-base font-bold text-white mb-4">Return per Aset (%)</h3>
                    <div id="bar-chart" class="w-full h-[300px]"></div>
                </div>

            </div>

            {{-- Holdings Table --}}
            <div class="fade-in-up fade-in-up-delay-5">
                <div class="rounded-2xl p-6 bg-white/[0.03] backdrop-blur-xl border border-white/10">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-base font-bold text-white">Detail Holding</h3>
                        @if(count($portfolioData) === 0)
                            <span class="text-xs text-gray-400">Belum ada aset. Tambahkan di atas.</span>
                        @endif
                    </div>

                    @if(count($portfolioData) > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead class="bg-white/[0.02]">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-gray-400 text-[10px] uppercase tracking-wider">Aset</th>
                                        <th class="px-4 py-3 text-right text-gray-400 text-[10px] uppercase tracking-wider">Jumlah</th>
                                        <th class="px-4 py-3 text-right text-gray-400 text-[10px] uppercase tracking-wider">Harga Beli</th>
                                        <th class="px-4 py-3 text-right text-gray-400 text-[10px] uppercase tracking-wider">Harga Sekarang</th>
                                        <th class="px-4 py-3 text-right text-gray-400 text-[10px] uppercase tracking-wider">Nilai</th>
                                        <th class="px-4 py-3 text-right text-gray-400 text-[10px] uppercase tracking-wider">P/L</th>
                                        <th class="px-4 py-3 text-center text-gray-400 text-[10px] uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-white/5">
                                    @foreach($portfolioData as $holding)
                                        <tr class="hover:bg-white/[0.03] transition">
                                            <td class="px-4 py-3 text-white font-medium">{{ $holding['asset_symbol'] }}</td>
                                            <td class="px-4 py-3 text-right text-gray-300">{{ number_format($holding['quantity'], 4) }}</td>
                                            <td class="px-4 py-3 text-right text-gray-300">{{ number_format($holding['buy_price'], 2) }}</td>
                                            <td class="px-4 py-3 text-right text-gray-300">{{ number_format($holding['current_price'], 2) }}</td>
                                            <td class="px-4 py-3 text-right text-white font-medium">
                                                ${{ number_format($holding['value'], 2, '.', ',') }}
                                            </td>
                                            <td class="px-4 py-3 text-right {{ $holding['pl'] >= 0 ? 'text-emerald-400' : 'text-red-400' }} font-medium">
                                                {{ $holding['pl'] >= 0 ? '+' : '' }}{{ number_format(abs($holding['pl']), 2) }}
                                                ({{ $holding['pl'] >= 0 ? '+' : '' }}{{ number_format(abs($holding['pl_percent']), 2) }}%)
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <form action="{{ route('portfolio.destroy', $holding['id']) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus aset ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-xs text-red-400 hover:text-red-300">Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Analisis AI --}}
            <div class="fade-in-up fade-in-up-delay-6">
                <div class="rounded-2xl p-6 bg-white/[0.03] backdrop-blur-xl border border-white/10">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-2 h-2 rounded-full bg-cyan-400 animate-pulse"></div>
                        <h3 class="text-base font-bold text-white">Analisis AI FinGo</h3>
                    </div>
                    <div class="prose prose-invert max-w-none">
                        <p class="text-gray-100 leading-relaxed whitespace-pre-line text-sm md:text-sm">
                            {{ $narasi }}
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.45.1/dist/apexcharts.min.js"></script>
    <script>
        @php
            $donutValues = [];
            $donutLabels = [];
            $donutColors = [];
            $assetColors = [
                'BTC' => '#f97316', 'ETH' => '#8b5cf6', 'SOL' => '#ec4899', 'BNB' => '#f59e0b',
                'ADA' => '#0ea5e9', 'BCH' => '#22c55e', 'LTC' => '#64748b', 'DOGE' => '#eab308',
                'DOT' => '#ec4899', 'LINK' => '#2563eb', 'JKSE' => '#a855f7', 'NASDAQ' => '#06b6d4',
                'SP500' => '#10b981', 'DJI' => '#3b82f6', 'RUT' => '#a855f7', 'VIX' => '#ef4444',
                'USDIDR' => '#10b981', 'EURIDR' => '#3b82f6', 'JPYIDR' => '#ef4444', 'XAU' => '#10b981',
                'XAG' => '#64748b', 'CL' => '#eab308', 'NG' => '#22c55e',
            ];

            foreach ($portfolioData as $holding) {
                $donutValues[] = round(($holding['value'] / $totalValue) * 100, 1);
                $donutLabels[] = $holding['asset_symbol'];
                $donutColors[] = $assetColors[$holding['asset_symbol']] ?? '#6b7280';
            }
        @endphp

        const donutValues = @json($donutValues);
        const donutLabels = @json($donutLabels);
        const donutColors = @json($donutColors);

        const barValues = @json(array_column($portfolioData, 'pl_percent'));
        const barLabels = @json(array_column($portfolioData, 'asset_symbol'));

        let donutChart = null;
        let barChart = null;

        function initCharts() {
            if (donutChart) donutChart.destroy();
            if (barChart) barChart.destroy();

            if (donutValues.length > 0) {
                donutChart = new ApexCharts(document.querySelector('#donut-chart'), {
                    chart: { type: 'donut', height: 300, background: 'transparent' },
                    series: donutValues,
                    labels: donutLabels,
                    colors: donutColors,
                    dataLabels: {
                        formatter: val => val + '%',
                        style: { colors: '#6b7280', fontSize: '11px' }
                    },
                    legend: { position: 'bottom', labels: { colors: '#94a3b8', fontSize: '11px' } },
                    plotOptions: {
                        donut: { size: '75%', labels: { show: true, value: { show: true, color: '#fff', fontSize: '16px', fontWeight: 'bold' } } }
                    },
                    tooltip: { theme: 'dark', y: { formatter: val => val + '%' } }
                });
                donutChart.render();
            }

            if (barValues.length > 0) {
                barChart = new ApexCharts(document.querySelector('#bar-chart'), {
                    chart: { type: 'bar', height: 300, background: 'transparent', toolbar: { show: true } },
                    series: [{ name: 'Return %', data: barValues }],
                    labels: barLabels,
                    colors: barValues.map(v => v >= 0 ? '#10b981' : '#ef4444'),
                    plotOptions: { bar: { columnWidth: '60%', distributed: true } },
                    xaxis: { labels: { style: { colors: '#6b7280', fontSize: '10px' }, rotate: -45 } },
                    yaxis: { labels: { style: { colors: '#6b7280', fontSize: '10px' }, formatter: val => val + '%' } },
                    tooltip: { theme: 'dark', y: { formatter: val => val.toFixed(2) + '%' } },
                    grid: { borderColor: 'rgba(6,182,212,0.1)' },
                    dataLabels: { enabled: false }
                });
                barChart.render();
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            initCharts();
        });
    </script>
</x-app-layout>