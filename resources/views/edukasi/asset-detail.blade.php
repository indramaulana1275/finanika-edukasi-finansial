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
                    <span class="text-white">{{ ucfirst($type) }}</span>
                    <span class="text-cyan-400">&gt;</span>
                    <span class="text-white">{{ $assetName }}</span>
                </nav>
            </div>
            <a href="{{ route('dashboard') }}" class="text-xs text-cyan-400 hover:text-cyan-300">
                ← Kembali ke Dasbor
            </a>
        </div>
    </x-slot>

    <div class="relative min-h-screen py-8 overflow-hidden">
        <div class="relative max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Header Aset --}}
            <div class="fade-in-up fade-in-up-delay-1 relative overflow-hidden rounded-2xl p-6
                        bg-gradient-to-br from-cyan-950/60 via-slate-900/80 to-purple-950/40
                        backdrop-blur-2xl border border-cyan-500/30 shimmer-border
                        shadow-[0_0_50px_rgba(6,182,212,0.2)]">
                <div class="absolute -top-32 -right-32 w-80 h-80 bg-cyan-500/25 rounded-full blur-3xl float-anim"></div>
                <div class="absolute -bottom-32 -left-32 w-80 h-80 bg-purple-500/25 rounded-full blur-3xl float-anim"></div>

                <div class="relative z-10">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <p class="text-sm text-cyan-300/80 tracking-wide uppercase">
                                {{ ucfirst($type) }} · {{ $symbol }}
                            </p>
                            <h1 class="text-3xl md:text-4xl font-extrabold mt-1 tracking-tight text-white">
                                {{ $assetName }}
                            </h1>
                            <p class="text-sm text-gray-400 mt-2">
                                Harga realtime dan histori pergerakan {{ strtolower($assetName) }}
                            </p>
                        </div>

                        <div class="flex items-end gap-4">
                            @if($priceData['success'] ?? false)
                                @php
                                    $assetType = request()->route('type') ?? $type;
                                    $price = $priceData['price_usd'] ?? $priceData['price'] ?? $priceData['rate'] ?? null;
                                    $change = $priceData['change_24h'] ?? $priceData['change_percent'] ?? $priceData['change'] ?? 0;
                                @endphp
                                <div class="text-right">
                                    <p class="text-4xl font-bold text-white tracking-tight">
                                        @if(in_array($assetType, ['forex', 'index', 'stock']))
                                            Rp {{ number_format($price, 0, ',', '.') }}
                                        @else
                                            ${{ number_format($price, 2, '.', ',') }}
                                        @endif
                                    </p>
                                    <p class="text-lg font-semibold {{ $change >= 0 ? 'text-emerald-400' : 'text-red-400' }}">
                                        {{ $change >= 0 ? '+' : '' }}{{ number_format(abs($change), 2) }}% (24 jam)
                                    </p>
                                </div>
                            @else
                                <div class="text-right">
                                    <p class="text-4xl font-bold text-white">-</p>
                                    <p class="text-lg text-red-400">Data tidak tersedia</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Tombol Periode Chart --}}
                    <div class="mt-6 flex flex-wrap gap-2">
                        <button type="button" onclick="loadPeriod(1)" class="period-btn px-4 py-2 rounded-lg bg-gradient-to-r from-cyan-600 to-blue-600 text-white text-xs font-semibold shadow-[0_0_15px_rgba(6,182,212,0.5)]" data-period="1">
                            1H
                        </button>
                        <button type="button" onclick="loadPeriod(7)" class="period-btn px-4 py-2 rounded-lg bg-white/[0.05] border border-white/10 text-gray-400 text-xs font-semibold hover:bg-white/[0.1]" data-period="7">
                            7H
                        </button>
                        <button type="button" onclick="loadPeriod(30)" class="period-btn px-4 py-2 rounded-lg bg-white/[0.05] border border-white/10 text-gray-400 text-xs font-semibold hover:bg-white/[0.1]" data-period="30">
                            30H
                        </button>
                        <button type="button" onclick="loadPeriod(90)" class="period-btn px-4 py-2 rounded-lg bg-white/[0.05] border border-white/10 text-gray-400 text-xs font-semibold hover:bg-white/[0.1]" data-period="90">
                            90H
                        </button>
                    </div>
                </div>
            </div>

            {{-- Chart Besar --}}
            <div class="fade-in-up fade-in-up-delay-2">
                <div class="rounded-2xl p-6 bg-white/[0.03] backdrop-blur-xl border border-white/10">
                    <div id="asset-chart" class="w-full h-[500px]"></div>
                </div>
            </div>

            {{-- Statistik Aset --}}
            <div class="fade-in-up fade-in-up-delay-3 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                @php
                    $assetType = request()->route('type') ?? $type;
                    $price = $priceData['price_usd'] ?? $priceData['price'] ?? $priceData['rate'] ?? null;
                    $change = $priceData['change_24h'] ?? $priceData['change_percent'] ?? $priceData['change'] ?? 0;
                    $prices7 = $history7Data['prices'] ?? [];
                    $prices30 = $history30Data['prices'] ?? [];

                    $chartKey = in_array($assetType, ['crypto', 'commodity']) ? 'price_usd' : 'price';
                    $high24 = $prices7 ? max(array_column($prices7, $chartKey)) : null;
                    $low24 = $prices7 ? min(array_column($prices7, $chartKey)) : null;
                @endphp

                <div class="rounded-2xl p-4 bg-white/[0.03] backdrop-blur-xl border border-white/10">
                    <p class="text-xs text-gray-400 uppercase font-semibold mb-1">Harga Sekarang</p>
                    @if($price)
                        <p class="text-xl font-bold text-white">
                            @if(in_array($assetType, ['forex', 'index', 'stock']))
                                Rp {{ number_format($price, 0, ',', '.') }}
                            @else
                                ${{ number_format($price, 2, '.', ',') }}
                            @endif
                        </p>
                    @else
                        <p class="text-sm text-red-400">-</p>
                    @endif
                </div>

                <div class="rounded-2xl p-4 bg-white/[0.03] backdrop-blur-xl border border-white/10">
                    <p class="text-xs text-gray-400 uppercase font-semibold mb-1">Perubahan 24 Jam</p>
                    <p class="text-xl font-bold {{ $change >= 0 ? 'text-emerald-400' : 'text-red-400' }}">
                        {{ $change >= 0 ? '+' : '' }}{{ number_format(abs($change ?? 0), 2) }}%
                    </p>
                </div>

                <div class="rounded-2xl p-4 bg-white/[0.03] backdrop-blur-xl border border-white/10">
                    <p class="text-xs text-gray-400 uppercase font-semibold mb-1">Tertinggi (7H)</p>
                    @if($high24)
                        <p class="text-xl font-bold text-white">
                            @if(in_array($assetType, ['forex', 'index', 'stock']))
                                Rp {{ number_format($high24, 0, ',', '.') }}
                            @else
                                ${{ number_format($high24, 2, '.', ',') }}
                            @endif
                        </p>
                    @else
                        <p class="text-sm text-red-400">-</p>
                    @endif
                </div>

                <div class="rounded-2xl p-4 bg-white/[0.03] backdrop-blur-xl border border-white/10">
                    <p class="text-xs text-gray-400 uppercase font-semibold mb-1">Terendah (7H)</p>
                    @if($low24)
                        <p class="text-xl font-bold text-white">
                            @if(in_array($assetType, ['forex', 'index', 'stock']))
                                Rp {{ number_format($low24, 0, ',', '.') }}
                            @else
                                ${{ number_format($low24, 2, '.', ',') }}
                            @endif
                        </p>
                    @else
                        <p class="text-sm text-red-400">-</p>
                    @endif
                </div>
            </div>

            {{-- Baris Chart + Narasi AI --}}
            <div class="fade-in-up fade-in-up-delay-4 grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- Chart Perubahan 7 Hari (Bar Chart) --}}
                <div class="rounded-2xl p-6 bg-white/[0.03] backdrop-blur-xl border border-white/10">
                    <h3 class="text-base font-bold text-white mb-4">Pergerakan 7 Hari Terakhir</h3>
                    <div id="bar-chart" class="w-full h-[300px]"></div>
                </div>

                {{-- Narasi AI --}}
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

            {{-- Card Berita / Info Tambahan --}}
            <div class="fade-in-up fade-in-up-delay-5">
                <div class="rounded-2xl p-6 bg-white/[0.03] backdrop-blur-xl border border-white/10">
                    <h3 class="text-base font-bold text-white mb-4">Informasi Tambahan</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="rounded-xl p-4 bg-white/[0.02] border border-white/5">
                            <p class="text-xs text-gray-400 uppercase font-semibold mb-2">Sumber Data</p>
                            <p class="text-sm text-gray-300">
                                @switch($type)
                                    @case('crypto')
                                        Data harga dari CoinGecko (konkuren langsung dari pasar kripto global).
                                    @break
                                    @case('forex')
                                        Data kurs dari Yahoo Finance (nilai tukar terhadap Rupiah).
                                    @break
                                    @case('commodity')
                                        Data komoditas dari Yahoo Finance (futures komoditas global).
                                    @break
                                    @case('stock')
                                        Data indeks saham dari Yahoo Finance.
                                    @break
                                    @case('index')
                                        Data indeks pasar dari Yahoo Finance.
                                    @break
                                @endswitch
                            </p>
                        </div>
                        <div class="rounded-xl p-4 bg-white/[0.02] border border-white/5">
                            <p class="text-xs text-gray-400 uppercase font-semibold mb-2">Catatan</p>
                            <p class="text-sm text-gray-300">
                                Data harga diperbarui setiap 5 menit. Historis ditampilkan dalam interval harian.
                                Investasi memiliki risiko, pastikan untuk selalu melakukan riset sendiri (DYOR).
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tombol Kembali di bawah --}}
            <div class="fade-in-up fade-in-up-delay-6">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-white/5 border border-white/10 text-gray-200 rounded-xl text-xs uppercase tracking-widest hover:bg-cyan-500/10 hover:border-cyan-400/40">
                    ← Kembali ke Dasbor
                </a>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.45.1/dist/apexcharts.min.js"></script>
    <script>
        @php
            // Deteksi tipe aset dari URL
            $assetType = request()->route('type') ?? $type;

            // Ambil harga dengan fallback ke berbagai key (generic untuk semua tipe)
            $price = $priceData['price_usd']
                   ?? $priceData['price']
                   ?? $priceData['rate']
                   ?? null;

            $change = $priceData['change_24h']
                    ?? $priceData['change_percent']
                    ?? $priceData['change']
                    ?? 0;

            // Prefix mata uang berdasarkan tipe aset
            $currencyPrefix = match($assetType) {
                'forex', 'index', 'stock' => 'Rp ',
                default => '$ ',
            };

            // Tentukan key yang digunakan untuk chart (price_usd untuk crypto/commodity, price untuk forex/stock/index)
            $chartKey = in_array($assetType, ['crypto', 'commodity']) ? 'price_usd' : 'price';

            $isIDR = in_array($assetType, ['forex', 'index', 'stock']);

            $chartColor = match($serviceKey) {
                'bitcoin' => '#f97316',
                'ethereum' => '#8b5cf6',
                'solana' => '#ec4899',
                'bnb' => '#f59e0b',
                'cardano' => '#0ea5e9',
                'stock' => '#a855f7',
                'gold' => '#10b981',
                'usd' => '#10b981',
                'eur' => '#3b82f6',
                'jpy' => '#ef4444',
                'cny' => '#eab308',
                'gbp' => '#a855f7',
                'sgd' => '#06b6d4',
                default => '#06b6d4',
            };
        @endphp

        const chartData7 = @json($history7Data['prices'] ?? []);
        const chartData30 = @json($history30Data['prices'] ?? []);

        // Debug: cek data di console
        console.log('Asset Detail Debug:', {
            type: '{{ $type }}',
            symbol: '{{ $symbol }}',
            serviceKey: '{{ $serviceKey }}',
            priceData: @json($priceData),
            history7Count: chartData7.length,
            history30Count: chartData30.length,
            chartData7: chartData7,
        });

        let mainChart = null;
        let barChart = null;

        // Helper: ambil harga dari titik data berdasarkan tipe aset
        function getPriceFromPoint(point, assetType) {
            if (assetType === 'crypto' || assetType === 'commodity') {
                return point.price_usd ?? point.price ?? point.rate ?? 0;
            }
            return point.price ?? point.price_usd ?? point.rate ?? 0;
        }

        function buildMainOptions(data) {
            const categories = data.map(p => p.date);
            const values = data.map(p => parseFloat(getPriceFromPoint(p, '{{ $assetType }}')));
            const isIDR = {{ $isIDR ? 'true' : 'false' }};
            const prefix = '{{ $currencyPrefix }}';

            return {
                chart: { type: 'area', height: 500, background: 'transparent', toolbar: { show: true }, zoom: { enabled: true } },
                series: [{ name: '{{ $assetName }}', data: values }],
                xaxis: { categories: categories, labels: { style: { colors: '#6b7280', fontSize: '11px' } } },
                yaxis: {
                    labels: {
                        style: { colors: '#6b7280', fontSize: '11px' },
                        formatter: val => isIDR ? 'Rp ' + new Intl.NumberFormat('id-ID', { maximumFractionDigits: 0 }).format(val) : prefix + new Intl.NumberFormat('en-US', { maximumFractionDigits: 2 }).format(val)
                    }
                },
                colors: ['{{ $chartColor }}'],
                stroke: { curve: 'smooth', width: 3 },
                fill: { type: 'gradient', gradient: { opacityFrom: 0.6, opacityTo: 0.05, stops: [0, 100] } },
                tooltip: { theme: 'dark', y: { formatter: val => isIDR ? 'Rp ' + new Intl.NumberFormat('id-ID', { maximumFractionDigits: 0 }).format(val) : prefix + new Intl.NumberFormat('en-US', { maximumFractionDigits: 2 }).format(val) } },
                grid: { borderColor: 'rgba(6,182,212,0.1)' },
                dataLabels: { enabled: false },
            };
        }

        function buildBarOptions(data) {
            const categories = data.map(p => p.date);
            const values = data.map(p => parseFloat(getPriceFromPoint(p, '{{ $assetType }}')));
            const isIDR = {{ $isIDR ? 'true' : 'false' }};

            return {
                chart: { type: 'bar', height: 300, background: 'transparent', toolbar: { show: true } },
                series: [{ name: 'Harga', data: values }],
                xaxis: { categories: categories, labels: { style: { colors: '#6b7280', fontSize: '10px' }, rotate: -45 } },
                yaxis: {
                    labels: {
                        style: { colors: '#6b7280', fontSize: '10px' },
                        formatter: val => isIDR ? 'Rp ' + new Intl.NumberFormat('id-ID', { maximumFractionDigits: 0 }).format(val) : '{{ $currencyPrefix }}' + new Intl.NumberFormat('en-US', { maximumFractionDigits: 2 }).format(val)
                    }
                },
                colors: ['{{ $chartColor }}'],
                plotOptions: { bar: { columnWidth: '60%', distributed: true } },
                tooltip: { theme: 'dark' },
                grid: { borderColor: 'rgba(6,182,212,0.1)' },
                dataLabels: { enabled: false },
            };
        }

        function initCharts() {
            if (mainChart) mainChart.destroy();
            if (barChart) barChart.destroy();

            const active = document.querySelector('.period-btn.active')?.dataset.period || '7';

            // Untuk 30H/90H, data belum ada di awal, fetch via AJAX
            if (active === '30' || active === '90') {
                if (chartData30.length === 0) {
                    fetchPeriodData(parseInt(active)).then(() => {
                        renderChart(active);
                    });
                    return;
                }
                renderChart(active);
                return;
            }

            const data = chartData7;
            if (data.length === 0) {
                console.warn('Asset Detail: No chart data for asset', '{{ $assetType }}', '{{ $symbol }}');
                document.querySelector('#asset-chart').innerHTML = '<div class="flex h-[500px] items-center justify-center text-gray-400 text-sm">Data sedang dimuat. Silakan refresh dalam 1 menit.</div>';
                document.querySelector('#bar-chart').innerHTML = '<div class="flex h-[300px] items-center justify-center text-gray-400 text-sm">Data sedang dimuat. Silakan refresh dalam 1 menit.</div>';
                return;
            }

            renderChart('7');
        }

        async function fetchPeriodData(days) {
            try {
                const res = await fetch(`/api/market/history?asset={{ $symbol }}&days=${days}`, {
                    headers: { 'Accept': 'application/json' }
                });
                const data = await res.json();
                if (data.prices && data.prices.length > 0) {
                    if (days === 30 || days === 90) {
                        chartData30.splice(0, chartData30.length, ...data.prices);
                    } else {
                        chartData7.splice(0, chartData7.length, ...data.prices);
                    }
                }
            } catch (err) {
                console.error('Gagal fetch data historis:', err);
            }
        }

        function renderChart(period) {
            const data = period === '30' || period === '90' ? chartData30 : chartData7;

            if (data.length === 0) {
                document.querySelector('#asset-chart').innerHTML = '<div class="flex h-[500px] items-center justify-center text-gray-400 text-sm">Data sedang dimuat. Silakan refresh dalam 1 menit.</div>';
                document.querySelector('#bar-chart').innerHTML = '<div class="flex h-[300px] items-center justify-center text-gray-400 text-sm">Data sedang dimuat. Silakan refresh dalam 1 menit.</div>';
                return;
            }

            mainChart = new ApexCharts(document.querySelector('#asset-chart'), buildMainOptions(data));
            mainChart.render();

            const barData = period === '7' ? chartData7 : chartData30;
            barChart = new ApexCharts(document.querySelector('#bar-chart'), buildBarOptions(barData));
            barChart.render();
        }

        function loadPeriod(days) {
            document.querySelectorAll('.period-btn').forEach(btn => {
                const active = btn.dataset.period == days;
                btn.classList.toggle('bg-gradient-to-r', active);
                btn.classList.toggle('from-cyan-600', active);
                btn.classList.toggle('to-blue-600', active);
                btn.classList.toggle('text-white', active);
                btn.classList.toggle('shadow-[0_0_15px_rgba(6,182,212,0.5)]', active);
                btn.classList.toggle('bg-white/[0.05]', !active);
                btn.classList.toggle('border-white/10', !active);
                btn.classList.toggle('text-gray-400', !active);
            });

            // Jika data untuk 30H/90H belum ada, fetch via AJAX
            if ((days === 30 || days === 90) && chartData30.length === 0) {
                fetchPeriodData(days).then(() => {
                    if (mainChart) {
                        mainChart.updateSeries([{ name: '{{ $assetName }}', data: chartData30.map(p => parseFloat(getPriceFromPoint(p, '{{ $assetType }}'))) }]);
                        mainChart.updateOptions({ xaxis: { categories: chartData30.map(p => p.date) } });
                    }
                    if (barChart) {
                        barChart.updateSeries([{ name: 'Harga', data: chartData30.map(p => parseFloat(getPriceFromPoint(p, '{{ $assetType }}'))) }]);
                        barChart.updateOptions({ xaxis: { categories: chartData30.map(p => p.date) } });
                    }
                });
                return;
            }

            const data = days === 30 || days === 90 ? chartData30 : chartData7;
            if (mainChart && data.length > 0) {
                mainChart.updateSeries([{ name: '{{ $assetName }}', data: data.map(p => parseFloat(getPriceFromPoint(p, '{{ $assetType }}'))) }]);
                mainChart.updateOptions({ xaxis: { categories: data.map(p => p.date) } });
            }
            if (barChart && data.length > 0) {
                barChart.updateSeries([{ name: 'Harga', data: data.map(p => parseFloat(getPriceFromPoint(p, '{{ $assetType }}'))) }]);
                barChart.updateOptions({ xaxis: { categories: data.map(p => p.date) } });
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.period-btn').forEach(btn => btn.classList.add('period-btn'));
            document.querySelector('[data-period="7"]').classList.add('bg-gradient-to-r', 'from-cyan-600', 'to-blue-600', 'text-white', 'shadow-[0_0_15px_rgba(6,182,212,0.5)]');
            initCharts();
        });
    </script>
</x-app-layout>