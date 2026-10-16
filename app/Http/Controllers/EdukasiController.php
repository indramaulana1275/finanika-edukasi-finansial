<?php

namespace App\Http\Controllers;

use App\Models\AssetHistory;
use App\Models\PortfolioHolding;
use App\Models\RiskProfile;
use App\Services\MarketDataService;
use App\Services\NlgService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EdukasiController extends Controller
{
    /**
     * ============================================================
     * Halaman Dasbor Edukasi Finansial (user login)
     * ⚡ FASE C: Optimasi Concurrent Request
     * ============================================================
     */
    public function dashboard(MarketDataService $market, NlgService $nlg)
    {
        $user = Auth::user();

        // ----------------------------------------------------------
        // 1. Statistik user
        // ----------------------------------------------------------
        $totalAset = AssetHistory::where('id_user', $user->id)->count();

        $totalSimulasi = AssetHistory::where('id_user', $user->id)
            ->sum('simulasi_dca');

        $riwayatAset = AssetHistory::where('id_user', $user->id)
            ->latest()
            ->take(5)
            ->get();

        // ----------------------------------------------------------
        // 2. Profil risiko user
        // ----------------------------------------------------------
        $riskProfile = RiskProfile::where('nim', $user->nim)->first();
        $profilRisiko = $riskProfile->tipe_risiko ?? 'Moderat';

        // ----------------------------------------------------------
        // 3. ⚡ DATA MARKET — MINIMAL UNTUK DASHBOARD (3 aset saja)
        //    Hanya BTC, IHSG, Gold untuk menghindari rate limit CoinGecko
        // ----------------------------------------------------------
        $startTime = microtime(true);

        // Ambil harga saat ini minimal (3 aset)
        $marketPrices = $market->getAllCurrentPricesMinimal();

        // Ambil histori 7 hari minimal (3 aset)
        $allHistory = $market->getAllHistoricalPricesMinimal(7);

        // Ekstrak historis per aset (hanya 3 aset yang di-fetch)
        $btcHistory = $allHistory['bitcoin'] ?? ['success' => false, 'prices' => []];
        $stockHistory = $allHistory['stock'] ?? ['success' => false, 'prices' => []];
        $goldHistory = $allHistory['gold'] ?? ['success' => false, 'prices' => []];

        // Aset lain (ETH, SOL, BNB, ADA) tidak di-fetch di dashboard untuk hemat API call.
        // Data akan di-load via AJAX saat user ganti chart di halaman dashboard.
        $ethHistory = ['success' => false, 'prices' => []];
        $solHistory = ['success' => false, 'prices' => []];
        $bnbHistory = ['success' => false, 'prices' => []];
        $adaHistory = ['success' => false, 'prices' => []];

        // ----------------------------------------------------------
        // 4. Narasi edukasi dari Gemini (NLG)
        // ----------------------------------------------------------
        $narasi = $nlg->generateNarasiEdukasi($profilRisiko, $marketPrices);

        // ----------------------------------------------------------
        // 5. Log untuk analisis TA (Bab 4)
        // ----------------------------------------------------------
        $loadTime = round((microtime(true) - $startTime) * 1000);
        Log::info("Dashboard loaded in {$loadTime}ms (concurrent fetch)");

        return view('dashboard', compact(
            'user',
            'totalAset',
            'totalSimulasi',
            'riwayatAset',
            'riskProfile',
            'profilRisiko',
            'marketPrices',
            'btcHistory',
            'stockHistory',
            'goldHistory',
            'ethHistory',
            'solHistory',
            'bnbHistory',
            'adaHistory',
            'narasi'
        ));
    }

    /**
     * ============================================================
     * Halaman Kuis Risiko
     * ============================================================
     */
    public function kuisRisiko()
    {
        return view('edukasi.kuis');
    }

    /**
     * ============================================================
     * Simpan hasil kuis
     * ============================================================
     */
    public function simpanKuis(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'nim' => 'required|string|max:20',
            'skor' => 'required|integer|min:0|max:15',
        ]);

        if ($request->skor <= 5) {
            $tipe = 'Konservatif';
        } elseif ($request->skor <= 10) {
            $tipe = 'Moderat';
        } else {
            $tipe = 'Agresif';
        }

        RiskProfile::updateOrCreate(
            ['nim' => $request->nim],
            [
                'nama' => $request->nama,
                'tipe_risiko' => $tipe,
            ]
        );

        return redirect()->route('dashboard')
            ->with('success', "Hasil kuis Anda: $tipe");
    }

    /**
     * ============================================================
     * Halaman Kalkulator Simulasi DCA
     * ============================================================
     */
    public function kalkulatorDCA()
    {
        $riwayatSimulasi = AssetHistory::where('id_user', Auth::id())
            ->latest()
            ->take(5)
            ->get();

        return view('edukasi.kalkulator', compact('riwayatSimulasi'));
    }

    /**
     * ============================================================
     * Proses hitung DCA
     * ============================================================
     */
    public function hitungDCA(Request $request)
    {
        $request->validate([
            'nama_aset' => 'required|string|max:100',
            'harga_saat_ini' => 'required|numeric|min:1',
            'investasi_bulanan' => 'required|numeric|min:1',
            'jumlah_bulan' => 'required|integer|min:1|max:120',
        ]);

        $hargaSekarang = (float) $request->harga_saat_ini;
        $investasi = (float) $request->investasi_bulanan;
        $bulan = (int) $request->jumlah_bulan;

        $totalUnit = 0;
        $harga = $hargaSekarang;

        for ($i = 0; $i < $bulan; $i++) {
            $totalUnit += $investasi / $harga;
            $harga *= 1.01;
        }

        $nilaiAkhir = $totalUnit * $harga;
        $totalInvestasi = $investasi * $bulan;
        $keuntungan = $nilaiAkhir - $totalInvestasi;

        AssetHistory::create([
            'id_user' => Auth::id(),
            'nama_aset' => $request->nama_aset,
            'harga_saat_ini' => $hargaSekarang,
            'simulasi_dca' => round($nilaiAkhir, 2),
        ]);

        $riwayatSimulasi = AssetHistory::where('id_user', Auth::id())
            ->latest()
            ->take(5)
            ->get();

        return view('edukasi.kalkulator', compact(
            'nilaiAkhir',
            'totalInvestasi',
            'keuntungan',
            'bulan',
            'riwayatSimulasi'
        ));
    }

    /**
     * ============================================================
     * Halaman Pasar / Market Data
     * ⚡ FASE C: Concurrent Fetch
     * ============================================================
     */
    public function marketData(MarketDataService $market)
    {
        $startTime = microtime(true);

        // Ambil semua harga saat ini (paralel)
        $currentPrices = $market->getAllCurrentPrices();

        // Ambil semua histori (paralel)
        $allHistory = $market->getAllHistoricalPrices(7);

        $btcHistory = $allHistory['bitcoin'] ?? ['success' => false, 'prices' => []];
        $stockHistory = $allHistory['stock'] ?? ['success' => false, 'prices' => []];
        $goldHistory = $allHistory['gold'] ?? ['success' => false, 'prices' => []];
        $ethHistory = $allHistory['ethereum'] ?? ['success' => false, 'prices' => []];
        $solHistory = $allHistory['solana'] ?? ['success' => false, 'prices' => []];
        $bnbHistory = $allHistory['bnb'] ?? ['success' => false, 'prices' => []];
        $adaHistory = $allHistory['cardano'] ?? ['success' => false, 'prices' => []];

        $loadTime = round((microtime(true) - $startTime) * 1000);
        Log::info("Market page loaded in {$loadTime}ms (concurrent fetch)");

        return view('edukasi.market', compact(
            'currentPrices',
            'btcHistory',
            'stockHistory',
            'goldHistory',
            'ethHistory',
            'solHistory',
            'bnbHistory',
            'adaHistory'
        ));
    }

    /**
     * ============================================================
     * API Endpoint: Harga saat ini (JSON)
     * ⚡ FASE C: Concurrent Fetch
     * ============================================================
     */
    public function apiCurrentPrices(MarketDataService $market)
    {
        return response()->json($market->getAllCurrentPrices());
    }

    /**
     * ============================================================
     * API Endpoint: Historis berdasarkan parameter
     * ⚡ FASE C: Concurrent Fetch
     * ============================================================
     */
    public function apiHistoricalPrices(Request $request, MarketDataService $market)
    {
        $asset = $request->query('asset', 'btc');
        $days = (int) $request->query('days', 7);

        // Ambil semua history sekali (paralel), lalu pilih yang dibutuhkan
        $allHistory = $market->getAllHistoricalPrices($days);

        $data = match ($asset) {
            'btc' => $allHistory['bitcoin'] ?? ['success' => false, 'error' => 'Data BTC tidak tersedia'],
            'eth' => $allHistory['ethereum'] ?? ['success' => false, 'error' => 'Data ETH tidak tersedia'],
            'sol' => $allHistory['solana'] ?? ['success' => false, 'error' => 'Data SOL tidak tersedia'],
            'bnb' => $allHistory['bnb'] ?? ['success' => false, 'error' => 'Data BNB tidak tersedia'],
            'ada' => $allHistory['cardano'] ?? ['success' => false, 'error' => 'Data ADA tidak tersedia'],
            'bch' => $allHistory['bitcoin-cash'] ?? ['success' => false, 'error' => 'Data BCH tidak tersedia'],
            'ltc' => $allHistory['litecoin'] ?? ['success' => false, 'error' => 'Data LTC tidak tersedia'],
            'doge' => $allHistory['dogecoin'] ?? ['success' => false, 'error' => 'Data DOGE tidak tersedia'],
            'dot' => $allHistory['polkadot'] ?? ['success' => false, 'error' => 'Data DOT tidak tersedia'],
            'link' => $allHistory['chainlink'] ?? ['success' => false, 'error' => 'Data LINK tidak tersedia'],
            'stock' => $allHistory['stock'] ?? ['success' => false, 'error' => 'Data IHSG tidak tersedia'],
            'gold' => $allHistory['gold'] ?? ['success' => false, 'error' => 'Data Emas tidak tersedia'],
            default => ['success' => false, 'error' => 'Aset tidak dikenal'],
        };

        return response()->json($data);
    }

    /**
     * ============================================================
     * Halaman Narasi Edukasi AI (NLG via Google Gemini)
     * ============================================================
     */
    public function narasiEdukasi(MarketDataService $market, NlgService $nlg)
    {
        $user = Auth::user();

        $riskProfile = RiskProfile::where('nim', $user->nim)->first();
        $profilRisiko = $riskProfile->tipe_risiko ?? 'Moderat';

        $marketData = $market->getAllCurrentPrices();

        $narasi = $nlg->generateNarasiEdukasi($profilRisiko, $marketData);

        return view('edukasi.narasi', compact(
            'profilRisiko',
            'marketData',
            'narasi'
        ));
    }

    public function cryptoDetail(MarketDataService $market)
    {
        $marketPrices = $market->getAllCurrentPrices();
        $allHistory = $market->getAllHistoricalPrices(7);

        $btcHistory = $allHistory['bitcoin'] ?? ['success' => false, 'prices' => []];
        $ethHistory = $allHistory['ethereum'] ?? ['success' => false, 'prices' => []];
        $solHistory = $allHistory['solana'] ?? ['success' => false, 'prices' => []];
        $bnbHistory = $allHistory['bnb'] ?? ['success' => false, 'prices' => []];
        $adaHistory = $allHistory['cardano'] ?? ['success' => false, 'prices' => []];
        $bchHistory = $allHistory['bitcoin-cash'] ?? ['success' => false, 'prices' => []];
        $ltcHistory = $allHistory['litecoin'] ?? ['success' => false, 'prices' => []];
        $dogeHistory = $allHistory['dogecoin'] ?? ['success' => false, 'prices' => []];
        $dotHistory = $allHistory['polkadot'] ?? ['success' => false, 'prices' => []];
        $linkHistory = $allHistory['chainlink'] ?? ['success' => false, 'prices' => []];

        return view('edukasi.crypto', compact(
            'marketPrices',
            'btcHistory',
            'ethHistory',
            'solHistory',
            'bnbHistory',
            'adaHistory',
            'bchHistory',
            'ltcHistory',
            'dogeHistory',
            'dotHistory',
            'linkHistory'
        ));
    }

    public function forexDetail(MarketDataService $market)
    {
        $forexPrices = $market->getAllCurrentForex();
        $forexHistory = $market->getAllHistoricalForex(7);

        return view('edukasi.forex', compact(
            'forexPrices',
            'forexHistory'
        ));
    }

    public function sahamUsDetail(MarketDataService $market)
    {
        $startTime = microtime(true);

        $currentPrices = $market->getAllCurrentSahamUS();
        $allHistory = $market->getAllHistoricalSahamUS(7);

        $nasdaqHistory = $allHistory['nasdaq'] ?? ['success' => false, 'prices' => []];
        $sp500History = $allHistory['sp500'] ?? ['success' => false, 'prices' => []];
        $dowHistory = $allHistory['dowjones'] ?? ['success' => false, 'prices' => []];
        $russellHistory = $allHistory['russell2000'] ?? ['success' => false, 'prices' => []];
        $vixHistory = $allHistory['vix'] ?? ['success' => false, 'prices' => []];

        $loadTime = round((microtime(true) - $startTime) * 1000);
        Log::info("Saham US page loaded in {$loadTime}ms (concurrent)");

        return view('edukasi.saham-us', compact(
            'currentPrices',
            'nasdaqHistory',
            'sp500History',
            'dowHistory',
            'russellHistory',
            'vixHistory'
        ));
    }

    /**
     * ============================================================
     * Halaman Komoditas
     * ============================================================
     */
    public function commodityDetail(MarketDataService $market)
    {
        $currentPrices = $market->getAllCurrentCommodities();
        $allHistory = $market->getAllHistoricalCommodities(7);

        $silverHistory = $allHistory['silver'] ?? ['success' => false, 'prices' => []];
        $crudeOilHistory = $allHistory['crude-oil'] ?? ['success' => false, 'prices' => []];
        $naturalGasHistory = $allHistory['natural-gas'] ?? ['success' => false, 'prices' => []];
        $goldHistory = $allHistory['gold'] ?? ['success' => false, 'prices' => []];

        return view('edukasi.commodity', compact(
            'currentPrices',
            'silverHistory',
            'crudeOilHistory',
            'naturalGasHistory',
            'goldHistory'
        ));
    }

    /**
     * ============================================================
     * Halaman Detail Per Aset
     * URL: /market/asset/{type}/{symbol}
     * type: crypto | stock | forex | commodity | index
     * ============================================================
     */
    public function assetDetail(string $type, string $symbol, MarketDataService $market, NlgService $nlg)
    {
        // Map symbol dari URL ke kunci data di MarketDataService
        $symbolMap = [
            'crypto' => [
                'btc' => 'bitcoin', 'eth' => 'ethereum', 'sol' => 'solana',
                'bnb' => 'bnb', 'ada' => 'cardano',
                'bch' => 'bitcoin-cash', 'ltc' => 'litecoin',
                'doge' => 'dogecoin', 'dot' => 'polkadot', 'link' => 'chainlink',
            ],
            'index' => [
                'jkse' => 'stock',        // IHSG
                'nasdaq' => 'nasdaq',
                'sp500' => 'sp500',
                'dowjones' => 'dowjones',
            ],
            'forex' => [
                'usdidr' => 'usd', 'euridr' => 'eur', 'jpyidr' => 'jpy',
                'cnYidr' => 'cny', 'gbpidr' => 'gbp', 'sgdidr' => 'sgd',
            ],
            'commodity' => [
                'gold' => 'gold',
                'silver' => 'silver',
                'crude-oil' => 'crude-oil',
                'natural-gas' => 'natural-gas',
            ],
        ];

        $serviceKey = $symbolMap[$type][$symbol] ?? null;

        if (! $serviceKey) {
            abort(404, 'Aset tidak ditemukan.');
        }

        // Tentukan method service yang sesuai untuk harga saat ini
        if ($type === 'forex') {
            $currentPrices = $market->getAllCurrentForex();
        } elseif ($type === 'commodity') {
            // Komoditas dari Yahoo Finance (silver, crude-oil, natural-gas)
            // + Emas dari XAU (xaus.com) via getAllCurrentPrices()
            $currentPrices = array_merge(
                $market->getAllCurrentCommodities(),
                ['gold' => $market->getAllCurrentPrices()['gold'] ?? ['success' => false]]
            );
        } elseif ($type === 'index' && in_array($serviceKey, ['nasdaq', 'sp500', 'dowjones'])) {
            $currentPrices = $market->getAllCurrentSahamUS();
        } else {
            // crypto dan index/jkse pakai getAllCurrentPrices()
            $currentPrices = $market->getAllCurrentPrices();
        }

        $priceData = $currentPrices[$serviceKey] ?? ['success' => false];

        // Tentukan method service untuk histori (hanya 7 hari untuk inisialisasi,
        // agar tidak membanjir API dengan terlalu banyak concurrent request)
        if ($type === 'forex') {
            $history7 = $market->getAllHistoricalForex(7);
        } elseif ($type === 'commodity') {
            $history7 = array_merge(
                $market->getAllHistoricalCommodities(7),
                ['gold' => $market->getAllHistoricalPrices(7)['gold'] ?? ['success' => false, 'prices' => []]]
            );
        } elseif ($type === 'index' && in_array($serviceKey, ['nasdaq', 'sp500', 'dowjones'])) {
            $history7 = $market->getAllHistoricalSahamUS(7);
        } else {
            // crypto dan index/jkse pakai getAllHistoricalPrices(7) hanya sekali
            $history7 = $market->getAllHistoricalPrices(7);
        }

        $history7Data = $history7[$serviceKey] ?? ['success' => false, 'prices' => []];

        // history30Data akan diisi via AJAX saat user klik tab 30H/90H
        $history30Data = ['success' => false, 'prices' => []];

        // Nama tampilan aset
        $nameMap = [
            'bitcoin' => 'Bitcoin', 'ethereum' => 'Ethereum', 'solana' => 'Solana',
            'bnb' => 'BNB', 'cardano' => 'Cardano',
            'bitcoin-cash' => 'Bitcoin Cash', 'litecoin' => 'Litecoin',
            'dogecoin' => 'Dogecoin', 'polkadot' => 'Polkadot', 'chainlink' => 'Chainlink',
            'stock' => 'IHSG (^JKSE)',
            'usd' => 'USD/IDR', 'eur' => 'EUR/IDR', 'jpy' => 'JPY/IDR',
            'cny' => 'CNY/IDR', 'gbp' => 'GBP/IDR', 'sgd' => 'SGD/IDR',
            'gold' => 'Emas (XAU/USD)',
            'silver' => 'Perak (XAG/USD)',
            'crude-oil' => 'Minyak Mentah WTI (CL=F)',
            'natural-gas' => 'Gas Alam (NG=F)',
            'nasdaq' => 'NASDAQ Composite', 'sp500' => 'S&P 500', 'dowjones' => 'Dow Jones',
        ];

        $assetName = $nameMap[$serviceKey] ?? ucfirst($symbol);

        // Narasi AI khusus aset ini
        $narasi = $nlg->generateNarasiAset($assetName, $symbol, $type, $priceData, $history7Data);

        return view('edukasi.asset-detail', compact(
            'type', 'symbol', 'serviceKey', 'assetName',
            'priceData', 'history7Data', 'history30Data', 'narasi'
        ));
    }

    /**
     * ============================================================
     * Halaman Portfolio (Data Analyst Showcase)
     * ============================================================
     */
    public function portfolio(MarketDataService $market, NlgService $nlg)
    {
        $user = Auth::user();
        $holdings = PortfolioHolding::where('id_user', $user->id)->latest()->get();

        // Fetch harga saat ini untuk setiap holding
        $currentPrices = $market->getAllCurrentPrices();
        $forexPrices = $market->getAllCurrentForex();
        $commodityPrices = $market->getAllCurrentCommodities();
        $sahamUSPrices = $market->getAllCurrentSahamUS();

        $totalValue = 0;
        $totalCost = 0;
        $portfolioData = [];

        foreach ($holdings as $holding) {
            $currentPrice = $this->getCurrentPriceForHolding(
                $holding->asset_type,
                $holding->asset_symbol,
                $currentPrices,
                $forexPrices,
                $commodityPrices,
                $sahamUSPrices
            );

            $value = $holding->quantity * $currentPrice;
            $cost = $holding->quantity * $holding->buy_price;
            $pl = $value - $cost;
            $plPercent = $cost > 0 ? ($pl / $cost) * 100 : 0;

            $totalValue += $value;
            $totalCost += $cost;

            $portfolioData[] = [
                'id' => $holding->id,
                'asset_symbol' => $holding->asset_symbol,
                'asset_type' => $holding->asset_type,
                'quantity' => $holding->quantity,
                'buy_price' => $holding->buy_price,
                'current_price' => $currentPrice,
                'value' => $value,
                'cost' => $cost,
                'pl' => $pl,
                'pl_percent' => $plPercent,
                'created_at' => $holding->created_at,
            ];
        }

        $totalPL = $totalValue - $totalCost;
        $totalPLPercent = $totalCost > 0 ? ($totalPL / $totalCost) * 100 : 0;

        // Aset terbaik (berdasarkan return %)
        usort($portfolioData, fn ($a, $b) => $b['pl_percent'] <=> $a['pl_percent']);
        $topAsset = $portfolioData[0]['asset_symbol'] ?? '-';
        $topAssetReturn = $portfolioData[0]['pl_percent'] ?? 0;

        // Narasi AI
        $riskProfile = RiskProfile::where('nim', $user->nim)->first();
        $profilRisiko = $riskProfile->tipe_risiko ?? 'Moderat';

        $narasi = $nlg->generateNarasiPortfolio([
            'total_value' => $totalValue,
            'total_cost' => $totalCost,
            'total_pl' => $totalPL,
            'total_pl_percent' => $totalPLPercent,
            'holding_count' => count($portfolioData),
            'top_asset' => $topAsset,
            'top_asset_return' => $topAssetReturn,
        ], $profilRisiko);

        return view('edukasi.portfolio', compact(
            'holdings', 'portfolioData',
            'totalValue', 'totalCost', 'totalPL', 'totalPLPercent',
            'topAsset', 'topAssetReturn', 'narasi', 'profilRisiko'
        ));
    }

    /**
     * Tambah holding portofolio.
     */
    public function storePortfolio(Request $request)
    {
        $request->validate([
            'asset_symbol' => 'required|string|max:20',
            'asset_type' => 'required|string|in:crypto,stock,forex,commodity,index',
            'quantity' => 'required|numeric|min:0',
            'buy_price' => 'required|numeric|min:0',
        ]);

        PortfolioHolding::create([
            'id_user' => Auth::id(),
            'asset_symbol' => strtoupper($request->asset_symbol),
            'asset_type' => $request->asset_type,
            'quantity' => $request->quantity,
            'buy_price' => $request->buy_price,
        ]);

        return back()->with('success', 'Aset berhasil ditambahkan ke portofolio.');
    }

    /**
     * Hapus holding portofolio.
     */
    public function destroyPortfolio(PortfolioHolding $holding)
    {
        if ($holding->id_user !== Auth::id()) {
            abort(403);
        }

        $holding->delete();

        return back()->with('success', 'Aset berhasil dihapus dari portofolio.');
    }

    /**
     * Ambil harga saat ini untuk holding berdasarkan tipe dan symbol.
     */
    private function getCurrentPriceForHolding(string $type, string $symbol, array $currentPrices, array $forexPrices, array $commodityPrices, array $sahamUSPrices): float
    {
        $keyMap = [
            'crypto' => [
                'BTC' => 'bitcoin', 'ETH' => 'ethereum', 'SOL' => 'solana',
                'BNB' => 'bnb', 'ADA' => 'cardano', 'BCH' => 'bitcoin-cash',
                'LTC' => 'litecoin', 'DOGE' => 'dogecoin', 'DOT' => 'polkadot', 'LINK' => 'chainlink',
            ],
            'forex' => [
                'USDIDR' => 'usd', 'EURIDR' => 'eur', 'JPYIDR' => 'jpy',
                'CNYIDR' => 'cny', 'GBPIDR' => 'gbp', 'SGDIDR' => 'sgd',
            ],
            'commodity' => [
                'XAU' => 'gold', 'XAG' => 'silver', 'CL' => 'crude-oil', 'NG' => 'natural-gas',
            ],
            'index' => [
                'JKSE' => 'stock', 'NASDAQ' => 'nasdaq', 'SP500' => 'sp500',
                'DJI' => 'dowjones', 'RUT' => 'russell2000', 'VIX' => 'vix',
            ],
        ];

        $data = match ($type) {
            'forex' => $forexPrices,
            'commodity' => $commodityPrices,
            'index' => $sahamUSPrices,
            default => $currentPrices,
        };

        $serviceKey = $keyMap[$type][$symbol] ?? null;

        if (! $serviceKey || ! isset($data[$serviceKey]['success']) || ! $data[$serviceKey]['success']) {
            return 0;
        }

        $priceData = $data[$serviceKey];
        $price = $priceData['price_usd'] ?? $priceData['price'] ?? $priceData['rate'] ?? 0;

        return (float) $price;
    }
}
