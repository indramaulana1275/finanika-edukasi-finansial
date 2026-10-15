<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Market Data - Harga Pasar Terkini') }}
            </h2>
            <span class="text-xs text-gray-500 dark:text-gray-400">
                Update otomatis tiap 5 menit
            </span>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- ============================================================ --}}
            {{-- Kartu Harga Saat Ini --}}
            {{-- ============================================================ --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <h3 class="text-md font-semibold text-gray-800 dark:text-gray-200 mb-4">
                    💰 Harga Saat Ini
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                    {{-- Bitcoin --}}
                    <div class="bg-gradient-to-br from-orange-50 to-orange-100 dark:from-orange-900/30 dark:to-orange-800/20 p-4 rounded-lg border border-orange-200 dark:border-orange-800">
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-xs font-medium text-gray-600 dark:text-gray-400">Bitcoin (BTC)</p>
                            <span class="text-xs px-2 py-0.5 bg-orange-200 dark:bg-orange-800 text-orange-800 dark:text-orange-200 rounded">Crypto</span>
                        </div>
                        @if($currentPrices['bitcoin']['success'] ?? false)
                            <p class="text-2xl font-bold text-orange-700 dark:text-orange-300">
                                $ {{ number_format($currentPrices['bitcoin']['price_usd'], 2, '.', ',') }}
                            </p>
                            @php $btcChange = $currentPrices['bitcoin']['change_24h'] ?? 0; @endphp
                            <p class="text-xs mt-1 {{ $btcChange >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                {{ $btcChange >= 0 ? '▲' : '▼' }} {{ number_format(abs($btcChange), 2) }}% (24 jam)
                            </p>
                        @else
                            <p class="text-sm text-red-500">Data tidak tersedia</p>
                        @endif
                    </div>

                    {{-- Saham BBCA --}}
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/30 dark:to-blue-800/20 p-4 rounded-lg border border-blue-200 dark:border-blue-800">
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-xs font-medium text-gray-600 dark:text-gray-400">Saham BBCA</p>
                            <span class="text-xs px-2 py-0.5 bg-blue-200 dark:bg-blue-800 text-blue-800 dark:text-blue-200 rounded">Stock</span>
                        </div>
                        @if($currentPrices['stock']['success'] ?? false)
                            <p class="text-2xl font-bold text-blue-700 dark:text-blue-300">
                                Rp {{ number_format($currentPrices['stock']['price'], 0, ',', '.') }}
                            </p>
                            @php $stockChange = $currentPrices['stock']['change_percent'] ?? 0; @endphp
                            <p class="text-xs mt-1 {{ $stockChange >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                {{ $stockChange >= 0 ? '▲' : '▼' }} {{ number_format(abs($stockChange), 2) }}%
                            </p>
                        @else
                            <p class="text-sm text-red-500">Data tidak tersedia</p>
                        @endif
                    </div>

                    {{-- Emas --}}
                    <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 dark:from-yellow-900/30 dark:to-yellow-800/20 p-4 rounded-lg border border-yellow-200 dark:border-yellow-800">
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-xs font-medium text-gray-600 dark:text-gray-400">Emas (XAU/USD)</p>
                            <span class="text-xs px-2 py-0.5 bg-yellow-200 dark:bg-yellow-800 text-yellow-800 dark:text-yellow-200 rounded">Commodity</span>
                        </div>
                        @if($currentPrices['gold']['success'] ?? false)
                            <p class="text-2xl font-bold text-yellow-700 dark:text-yellow-300">
                                $ {{ number_format($currentPrices['gold']['price_usd'], 2, '.', ',') }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">per troy ounce</p>
                        @else
                            <p class="text-sm text-red-500">Data tidak tersedia</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- ============================================================ --}}
            {{-- Filter Periode Historis --}}
            {{-- ============================================================ --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <div class="flex flex-wrap items-center gap-3">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Periode Historis:</span>
                    <button onclick="loadHistory(7)" class="periode-btn px-4 py-2 rounded-lg text-sm font-medium transition bg-indigo-600 text-white" data-days="7">
                        7 Hari
                    </button>
                    <button onclick="loadHistory(14)" class="periode-btn px-4 py-2 rounded-lg text-sm font-medium transition bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300" data-days="14">
                        14 Hari
                    </button>
                    <button onclick="loadHistory(30)" class="periode-btn px-4 py-2 rounded-lg text-sm font-medium transition bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300" data-days="30">
                        30 Hari
                    </button>
                    <span id="loadingIndicator" class="hidden text-sm text-gray-500 dark:text-gray-400 ml-2">
                        ⏳ Memuat data...
                    </span>
                </div>
            </div>

            {{-- ============================================================ --}}
            {{-- Chart: Bitcoin --}}
            {{-- ============================================================ --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-md font-semibold text-gray-800 dark:text-gray-200">
                        📈 Bitcoin (BTC) - Harga Historis
                    </h3>
                    <span class="text-xs text-gray-500 dark:text-gray-400">USD</span>
                </div>
                <div id="chart-btc" class="w-full h-80"></div>
            </div>

            {{-- ============================================================ --}}
            {{-- Chart: Saham BBCA --}}
            {{-- ============================================================ --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-md font-semibold text-gray-800 dark:text-gray-200">
                        📈 Saham BBCA - Harga Historis
                    </h3>
                    <span class="text-xs text-gray-500 dark:text-gray-400">IDR</span>
                </div>
                <div id="chart-stock" class="w-full h-80"></div>
            </div>

            {{-- ============================================================ --}}
            {{-- Chart: Emas --}}
            {{-- ============================================================ --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-md font-semibold text-gray-800 dark:text-gray-200">
                        📈 Emas (XAU/USD) - Harga Historis
                    </h3>
                    <span class="text-xs text-gray-500 dark:text-gray-400">USD / troy ounce</span>
                </div>
                <div id="chart-gold" class="w-full h-80"></div>
            </div>

            {{-- ============================================================ --}}
            {{-- Tombol Kembali --}}
            {{-- ============================================================ --}}
            <div>
                <a href="{{ route('dashboard') }}"
                   class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-50 dark:hover:bg-gray-600 transition">
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
        // Deteksi dark mode
        // =========================================================
        function isDarkMode() {
            return document.documentElement.classList.contains('dark')
                || (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches
                    && !document.documentElement.classList.contains('light'));
        }

        // =========================================================
        // Warna tema (Tailwind palette)
        // =========================================================
        function getThemeColors() {
            const dark = isDarkMode();
            return {
                text:        dark ? '#e5e7eb' : '#374151', // gray-200 : gray-700
                grid:        dark ? '#374151' : '#e5e7eb', // gray-700 : gray-200
                tooltipBg:   dark ? '#1f2937' : '#ffffff', // gray-800 : white
                tooltipText: dark ? '#f9fafb' : '#111827', // gray-50 : gray-900
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
                    theme: isDarkMode() ? 'dark' : 'light',
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
                buildChartOptions('chart-stock', 'BBCA (IDR)', data.stock || [], c.stock, true)
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
                btn.classList.toggle('bg-indigo-600', active);
                btn.classList.toggle('text-white', active);
                btn.classList.toggle('bg-gray-100', !active);
                btn.classList.toggle('dark:bg-gray-700', !active);
                btn.classList.toggle('text-gray-700', !active);
                btn.classList.toggle('dark:text-gray-300', !active);
            });

            // Tampilkan loading
            const loader = document.getElementById('loadingIndicator');
            loader.classList.remove('hidden');

            try {
                // Fetch parallel
                const [btcRes, stockRes, goldRes] = await Promise.all([
                    fetch(`/api/market/history?asset=btc&days=${days}`, { headers: { 'Accept': 'application/json' } }),
                    fetch(`/api/market/history?asset=stock&days=${days}`, { headers: { 'Accept': 'application/json' } }),
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
                    series: [{ name: 'BBCA (IDR)', data: (stockData.prices || []).map(p => parseFloat(p.price)) }],
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
        // Deteksi perubahan dark mode → re-render chart
        // =========================================================
        const observer = new MutationObserver(() => {
            const c = getThemeColors();
            [chartBtc, chartStock, chartGold].forEach(chart => {
                if (!chart) return;
                chart.updateOptions({
                    chart: { foreColor: c.text },
                    xaxis: { labels: { style: { colors: c.text } }, axisBorder: { color: c.grid } },
                    yaxis: { labels: { style: { colors: c.text } } },
                    grid: { borderColor: c.grid },
                    tooltip: { theme: isDarkMode() ? 'dark' : 'light' },
                });
            });
        });

        observer.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });

        // =========================================================
        // Boot: render chart saat halaman siap
        // =========================================================
        document.addEventListener('DOMContentLoaded', () => {
            initCharts(initialData);
        });
    </script>
</x-app-layout> 