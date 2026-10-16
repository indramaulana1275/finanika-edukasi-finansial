<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Market Data - Harga Pasar Terkini') }}
            </h2>
            <span class="text-xs text-gray-400">
                Update otomatis tiap 5 menit
            </span>
        </div>
    </x-slot>

    <div class="relative min-h-screen py-8 overflow-hidden">
        <div class="relative max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- ============================================================ --}}
            {{-- Kartu Harga Saat Ini --}}
            {{-- ============================================================ --}}
            <div class="rounded-2xl p-6 bg-white/[0.03] backdrop-blur-xl border border-white/10">
                <h3 class="text-md font-semibold text-white mb-4">
                    💰 Harga Saat Ini
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">

                    {{-- Bitcoin --}}
                    <div class="bg-gradient-to-br from-orange-500/10 to-orange-500/20 p-4 rounded-lg border border-orange-500/20">
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-xs font-medium text-gray-400">Bitcoin (BTC)</p>
                            <span class="text-xs px-2 py-0.5 bg-orange-500/20 text-orange-400 rounded">Crypto</span>
                        </div>
                        @if($currentPrices['bitcoin']['success'] ?? false)
                            <p class="text-2xl font-bold text-orange-400">
                                $ {{ number_format($currentPrices['bitcoin']['price_usd'], 2, '.', ',') }}
                            </p>
                            @php $btcChange = $currentPrices['bitcoin']['change_24h'] ?? 0; @endphp
                            <p class="text-xs mt-1 {{ $btcChange >= 0 ? 'text-emerald-400' : 'text-red-400' }}">
                                {{ $btcChange >= 0 ? '▲' : '▼' }} {{ number_format(abs($btcChange), 2) }}% (24 jam)
                            </p>
                        @else
                            <p class="text-sm text-red-400">Data tidak tersedia</p>
                        @endif
                    </div>

                    {{-- Ethereum --}}
                    <div class="bg-gradient-to-br from-violet-500/10 to-violet-500/20 p-4 rounded-lg border border-violet-500/20">
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-xs font-medium text-gray-400">Ethereum (ETH)</p>
                            <span class="text-xs px-2 py-0.5 bg-violet-500/20 text-violet-400 rounded">Crypto</span>
                        </div>
                        @if($currentPrices['ethereum']['success'] ?? false)
                            <p class="text-2xl font-bold text-violet-400">
                                $ {{ number_format($currentPrices['ethereum']['price_usd'], 2, '.', ',') }}
                            </p>
                            @php $ethChange = $currentPrices['ethereum']['change_24h'] ?? 0; @endphp
                            <p class="text-xs mt-1 {{ $ethChange >= 0 ? 'text-emerald-400' : 'text-red-400' }}">
                                {{ $ethChange >= 0 ? '+' : '' }}{{ number_format(abs($ethChange), 2) }}% (24 jam)
                            </p>
                        @else
                            <p class="text-sm text-red-400">Data tidak tersedia</p>
                        @endif
                    </div>

                    {{-- Solana --}}
                    <div class="bg-gradient-to-br from-pink-500/10 to-pink-500/20 p-4 rounded-lg border border-pink-500/20">
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-xs font-medium text-gray-400">Solana (SOL)</p>
                            <span class="text-xs px-2 py-0.5 bg-pink-500/20 text-pink-400 rounded">Crypto</span>
                        </div>
                        @if($currentPrices['solana']['success'] ?? false)
                            <p class="text-2xl font-bold text-pink-400">
                                $ {{ number_format($currentPrices['solana']['price_usd'], 2, '.', ',') }}
                            </p>
                            @php $solChange = $currentPrices['solana']['change_24h'] ?? 0; @endphp
                            <p class="text-xs mt-1 {{ $solChange >= 0 ? 'text-emerald-400' : 'text-red-400' }}">
                                {{ $solChange >= 0 ? '+' : '' }}{{ number_format(abs($solChange), 2) }}% (24 jam)
                            </p>
                        @else
                            <p class="text-sm text-red-400">Data tidak tersedia</p>
                        @endif
                    </div>

                    {{-- BNB --}}
                    <div class="bg-gradient-to-br from-amber-500/10 to-amber-500/20 p-4 rounded-lg border border-amber-500/20">
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-xs font-medium text-gray-400">BNB (BNB)</p>
                            <span class="text-xs px-2 py-0.5 bg-amber-500/20 text-amber-400 rounded">Crypto</span>
                        </div>
                        @if($currentPrices['bnb']['success'] ?? false)
                            <p class="text-2xl font-bold text-amber-400">
                                $ {{ number_format($currentPrices['bnb']['price_usd'], 2, '.', ',') }}
                            </p>
                            @php $bnbChange = $currentPrices['bnb']['change_24h'] ?? 0; @endphp
                            <p class="text-xs mt-1 {{ $bnbChange >= 0 ? 'text-emerald-400' : 'text-red-400' }}">
                                {{ $bnbChange >= 0 ? '+' : '' }}{{ number_format(abs($bnbChange), 2) }}% (24 jam)
                            </p>
                        @else
                            <p class="text-sm text-red-400">Data tidak tersedia</p>
                        @endif
                    </div>

                    {{-- Cardano --}}
                    <div class="bg-gradient-to-br from-sky-500/10 to-sky-500/20 p-4 rounded-lg border border-sky-500/20">
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-xs font-medium text-gray-400">Cardano (ADA)</p>
                            <span class="text-xs px-2 py-0.5 bg-sky-500/20 text-sky-400 rounded">Crypto</span>
                        </div>
                        @if($currentPrices['cardano']['success'] ?? false)
                            <p class="text-2xl font-bold text-sky-400">
                                $ {{ number_format($currentPrices['cardano']['price_usd'], 2, '.', ',') }}
                            </p>
                            @php $adaChange = $currentPrices['cardano']['change_24h'] ?? 0; @endphp
                            <p class="text-xs mt-1 {{ $adaChange >= 0 ? 'text-emerald-400' : 'text-red-400' }}">
                                {{ $adaChange >= 0 ? '+' : '' }}{{ number_format(abs($adaChange), 2) }}% (24 jam)
                            </p>
                        @else
                            <p class="text-sm text-red-400">Data tidak tersedia</p>
                        @endif
                    </div>

                    {{-- Saham IHSG --}}
                    <div class="bg-gradient-to-br from-blue-500/10 to-blue-500/20 p-4 rounded-lg border border-blue-500/20">
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-xs font-medium text-gray-400">IHSG (^JKSE)</p>
                            <span class="text-xs px-2 py-0.5 bg-blue-500/20 text-blue-400 rounded">Stock</span>
                        </div>
                        @if($currentPrices['stock']['success'] ?? false)
                            <p class="text-2xl font-bold text-blue-400">
                                Rp {{ number_format($currentPrices['stock']['price'], 0, ',', '.') }}
                            </p>
                            @php $stockChange = $currentPrices['stock']['change_percent'] ?? 0; @endphp
                            <p class="text-xs mt-1 {{ $stockChange >= 0 ? 'text-emerald-400' : 'text-red-400' }}">
                                {{ $stockChange >= 0 ? '▲' : '▼' }} {{ number_format(abs($stockChange), 2) }}%
                            </p>
                        @else
                            <p class="text-sm text-red-400">Data tidak tersedia</p>
                        @endif
                    </div>

                    {{-- Emas --}}
                    <div class="bg-gradient-to-br from-yellow-500/10 to-yellow-500/20 p-4 rounded-lg border border-yellow-500/20">
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-xs font-medium text-gray-400">Emas (XAU/USD)</p>
                            <span class="text-xs px-2 py-0.5 bg-yellow-500/20 text-yellow-400 rounded">Commodity</span>
                        </div>
                        @if($currentPrices['gold']['success'] ?? false)
                            <p class="text-2xl font-bold text-yellow-400">
                                $ {{ number_format($currentPrices['gold']['price_usd'], 2, '.', ',') }}
                            </p>
                            <p class="text-xs text-gray-400 mt-1">per troy ounce</p>
                        @else
                            <p class="text-sm text-red-400">Data tidak tersedia</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- ============================================================ --}}
            {{-- Filter Periode Historis --}}
            {{-- ============================================================ --}}
            <div class="rounded-2xl p-6 bg-white/[0.03] backdrop-blur-xl border border-white/10">
                <div class="flex flex-wrap items-center gap-3">
                    <span class="text-sm font-medium text-gray-300">Periode Historis:</span>
                    <button onclick="loadHistory(7)" class="periode-btn px-4 py-2 rounded-lg text-sm font-medium transition bg-gradient-to-r from-cyan-600 to-blue-600 text-white" data-days="7">
                        7 Hari
                    </button>
                    <button onclick="loadHistory(14)" class="periode-btn px-4 py-2 rounded-lg text-sm font-medium transition bg-white/[0.05] border border-white/10 text-gray-300" data-days="14">
                        14 Hari
                    </button>
                    <button onclick="loadHistory(30)" class="periode-btn px-4 py-2 rounded-lg text-sm font-medium transition bg-white/[0.05] border border-white/10 text-gray-300" data-days="30">
                        30 Hari
                    </button>
                    <span id="loadingIndicator" class="hidden text-sm text-gray-400 ml-2">
                        ⏳ Memuat data...
                    </span>
                </div>
            </div>

            {{-- ============================================================ --}}
            {{-- Chart: Bitcoin --}}
            {{-- ============================================================ --}}
            <div class="rounded-2xl p-6 bg-white/[0.03] backdrop-blur-xl border border-white/10">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-md font-semibold text-white">
                        📈 Bitcoin (BTC) - Harga Historis
                    </h3>
                    <span class="text-xs text-gray-400">USD</span>
                </div>
                <div id="chart-btc" class="w-full h-80"></div>
            </div>

            {{-- ============================================================ --}}
            {{-- Chart: Saham IHSG --}}
            {{-- ============================================================ --}}
            <div class="rounded-2xl p-6 bg-white/[0.03] backdrop-blur-xl border border-white/10">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-md font-semibold text-white">
                        📈 IHSG (^JKSE) - Harga Historis
                    </h3>
                    <span class="text-xs text-gray-400">IDR</span>
                </div>
                <div id="chart-stock" class="w-full h-80"></div>
            </div>

            {{-- ============================================================ --}}
            {{-- Chart: Emas --}}
            {{-- ============================================================ --}}
            <div class="rounded-2xl p-6 bg-white/[0.03] backdrop-blur-xl border border-white/10">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-md font-semibold text-white">
                        📈 Emas (XAU/USD) - Harga Historis
                    </h3>
                    <span class="text-xs text-gray-400">USD / troy ounce</span>
                </div>
                <div id="chart-gold" class="w-full h-80"></div>
            </div>

            {{-- ============================================================ --}}
            {{-- Tombol Kembali --}}
            {{-- ============================================================ --}}
            <div>
                <a href="{{ route('dashboard') }}"
                   class="inline-flex items-center px-4 py-2 bg-white/5 border border-white/10 text-gray-300 rounded-xl text-xs uppercase tracking-widest hover:bg-cyan-500/10 hover:border-cyan-400/40 transition">
                    {{ __('← Kembali ke Dasbor') }}
                </a>
            </div>

        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- ApexCharts CDN --}}
    {{-- ============================================================ --}}
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.45.1/dist/apexcharts.min.js"></script>

    <script>
        // =========================================================
        // Data awal dari server (Blade → JS)
        // =========================================================
        const initialData = {
            btc:   @json($btcHistory['prices'] ?? []),
            stock: @json($stockHistory['prices'] ?? []),
            gold:  @json($goldHistory['prices'] ?? []),
        };

        // =========================================================
        // Warna tema (Tailwind palette) - selalu dark
        // =========================================================
        function getThemeColors() {
            return {
                text:        '#e5e7eb', // gray-200
                grid:        '#374151', // gray-700
                tooltipBg:   '#1f2937', // gray-800
                tooltipText: '#f9fafb', // gray-50
                btc:   '#f97316', // orange-500
                stock: '#3b82f6', // blue-500
                gold:  '#eab308', // yellow-500
            };
        }

        // =========================================================
        // Base config untuk line chart
        // =========================================================
        function buildChartOptions(elementId, seriesName, seriesData, color, isCurrencyIDR = false) {
            const c = getThemeColors();

            const categories = seriesData.map(p => p.date);
            const values     = seriesData.map(p => isCurrencyIDR
                ? parseFloat(p.price)
                : parseFloat(p.price_usd ?? p.price));

            return {
                chart: {
                    type: 'line',
                    height: 320,
                    toolbar: { show: true },
                    zoom: { enabled: true },
                    background: 'transparent',
                    foreColor: c.text,
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 600,
                    },
                },
                series: [{
                    name: seriesName,
                    data: values,
                }],
                xaxis: {
                    categories: categories,
                    labels: {
                        style: { colors: c.text, fontSize: '11px' },
                        rotate: -45,
                    },
                    axisBorder: { color: c.grid },
                    axisTicks: { color: c.grid },
                },
                yaxis: {
                    labels: {
                        style: { colors: c.text, fontSize: '11px' },
                        formatter: (val) => isCurrencyIDR
                            ? 'Rp ' + new Intl.NumberFormat('id-ID', { maximumFractionDigits: 0 }).format(val)
                            : '$ ' + new Intl.NumberFormat('en-US', { maximumFractionDigits: 2 }).format(val),
                    },
                },
                stroke: {
                    curve: 'smooth',
                    width: 3,
                },
                colors: [color],
                markers: {
                    size: 4,
                    colors: [color],
                    strokeColors: c.tooltipBg,
                    strokeWidth: 2,
                    hover: { size: 7 },
                },
                grid: {
                    borderColor: c.grid,
                    strokeDashArray: 4,
                },
                tooltip: {
                    theme: 'dark',
                    style: {
                        fontSize: '12px',
                    },
                    y: {
                        formatter: (val) => isCurrencyIDR
                            ? 'Rp ' + new Intl.NumberFormat('id-ID', { maximumFractionDigits: 0 }).format(val)
                            : '$ ' + new Intl.NumberFormat('en-US', { maximumFractionDigits: 2 }).format(val),
                    },
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 0.5,
                        opacityFrom: 0.3,
                        opacityTo: 0.05,
                        stops: [0, 100],
                    },
                },
                dataLabels: { enabled: false },
                responsive: [{
                    breakpoint: 640,
                    options: {
                        chart: { height: 260 },
                        xaxis: { labels: { rotate: -60, style: { fontSize: '9px' } } },
                    },
                }],
            };
        }

        // =========================================================
        // Inisialisasi chart
        // =========================================================
        let chartBtc, chartStock, chartGold;

        function initCharts(data) {
            const c = getThemeColors();

            // Bitcoin
            chartBtc = new ApexCharts(
                document.querySelector('#chart-btc'),
                buildChartOptions('chart-btc', 'BTC (USD)', data.btc || [], c.btc, false)
            );
            chartBtc.render();

            // Saham
            chartStock = new ApexCharts(
                document.querySelector('#chart-stock'),
                buildChartOptions('chart-stock', 'IHSG (IDR)', data.stock || [], c.stock, true)
            );
            chartStock.render();

            // Emas
            chartGold = new ApexCharts(
                document.querySelector('#chart-gold'),
                buildChartOptions('chart-gold', 'XAU (USD)', data.gold || [], c.gold, false)
            );
            chartGold.render();
        }

        // =========================================================
        // Update chart saat ganti periode
        // =========================================================
        async function loadHistory(days) {
            // Update UI tombol
            document.querySelectorAll('.periode-btn').forEach(btn => {
                const active = btn.dataset.days == days;
                btn.classList.toggle('bg-gradient-to-r', active);
                btn.classList.toggle('from-cyan-600', active);
                btn.classList.toggle('to-blue-600', active);
                btn.classList.toggle('text-white', active);
                btn.classList.toggle('shadow-[0_0_15px_rgba(6,182,212,0.5)]', active);
                btn.classList.toggle('bg-white/[0.05]', !active);
                btn.classList.toggle('border-white/10', !active);
                btn.classList.toggle('text-gray-300', !active);
            });

            // Tampilkan loading
            const loader = document.getElementById('loadingIndicator');
            loader.classList.remove('hidden');

            try {
                // Fetch parallel
                const [btcRes, stockRes, goldRes] = await Promise.all([
                    fetch(`/api/market/history?asset=btc&days=${days}`, { headers: { 'Accept': 'application/json' } }),
                    fetch(`/api/market/history?asset=stock&days=${days}`, { headers { 'Accept': 'application/json' } }),
                    fetch(`/api/market/history?asset=gold&days=${days}`, { headers: { 'Accept': 'application/json' } }),
                ]);

                const [btcData, stockData, goldData] = await Promise.all([
                    btcRes.json(), stockRes.json(), goldRes.json()
                ]);

                // Update series
                const c = getThemeColors();
                chartBtc.updateOptions({
                    xaxis: { categories: (btcData.prices || []).map(p => p.date) },
                    series: [{ name: 'BTC (USD)', data: (btcData.prices || []).map(p => parseFloat(p.price_usd)) }],
                });

                chartStock.updateOptions({
                    xaxis: { categories: (stockData.prices || []).map(p => p.date) },
                    series: [{ name: 'IHSG (IDR)', data: (stockData.prices || []).map(p => parseFloat(p.price)) }],
                });

                chartGold.updateOptions({
                    xaxis: { categories: (goldData.prices || []).map(p => p.date) },
                    series: [{ name: 'XAU (USD)', data: (goldData.prices || []).map(p => parseFloat(p.price_usd)) }],
                });
            } catch (err) {
                console.error('Gagal memuat data historis:', err);
                alert('Gagal memuat data historis. Cek koneksi atau log server.');
            } finally {
                loader.classList.add('hidden');
            }
        }

        // =========================================================
        // Boot: render chart saat halaman siap
        // =========================================================
        document.addEventListener('DOMContentLoaded', () => {
            initCharts(initialData);
        });
    </script>
</x-app-layout>