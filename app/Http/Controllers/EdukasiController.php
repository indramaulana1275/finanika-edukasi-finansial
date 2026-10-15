<?php

namespace App\Http\Controllers;

use App\Models\AssetHistory;
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
        $riskProfile  = RiskProfile::where('nim', $user->nim)->first();
        $profilRisiko = $riskProfile->tipe_risiko ?? 'Moderat';

        // ----------------------------------------------------------
        // 3. ⚡ DATA MARKET — CONCURRENT FETCH (SEKALI JALAN)
        //    Semua API di-request paralel, bukan satu per satu
        // ----------------------------------------------------------
        $startTime = microtime(true);

        // Ambil SEMUA harga saat ini (paralel)
        $marketPrices = $market->getAllCurrentPrices();

        // Ambil SEMUA histori 7 hari (paralel)
        $allHistory = $market->getAllHistoricalPrices(7);

        // Ekstrak historis per aset
        $btcHistory   = $allHistory['bitcoin'] ?? ['success' => false, 'prices' => []];
        $stockHistory = $allHistory['stock']   ?? ['success' => false, 'prices' => []];
        $goldHistory  = $allHistory['gold']    ?? ['success' => false, 'prices' => []];

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
            'nim'  => 'required|string|max:20',
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
                'nama'        => $request->nama,
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
            'nama_aset'         => 'required|string|max:100',
            'harga_saat_ini'    => 'required|numeric|min:1',
            'investasi_bulanan' => 'required|numeric|min:1',
            'jumlah_bulan'      => 'required|integer|min:1|max:120',
        ]);

        $hargaSekarang = (float) $request->harga_saat_ini;
        $investasi     = (float) $request->investasi_bulanan;
        $bulan         = (int) $request->jumlah_bulan;

        $totalUnit = 0;
        $harga     = $hargaSekarang;

        for ($i = 0; $i < $bulan; $i++) {
            $totalUnit += $investasi / $harga;
            $harga     *= 1.01;
        }

        $nilaiAkhir     = $totalUnit * $harga;
        $totalInvestasi = $investasi * $bulan;
        $keuntungan     = $nilaiAkhir - $totalInvestasi;

        AssetHistory::create([
            'id_user'        => Auth::id(),
            'nama_aset'      => $request->nama_aset,
            'harga_saat_ini' => $hargaSekarang,
            'simulasi_dca'   => round($nilaiAkhir, 2),
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

        $btcHistory   = $allHistory['bitcoin'] ?? ['success' => false, 'prices' => []];
        $stockHistory = $allHistory['stock']   ?? ['success' => false, 'prices' => []];
        $goldHistory  = $allHistory['gold']    ?? ['success' => false, 'prices' => []];

        $loadTime = round((microtime(true) - $startTime) * 1000);
        Log::info("Market page loaded in {$loadTime}ms (concurrent fetch)");

        return view('edukasi.market', compact(
            'currentPrices',
            'btcHistory',
            'stockHistory',
            'goldHistory'
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
        $days  = (int) $request->query('days', 7);

        // Ambil semua history sekali (paralel), lalu pilih yang dibutuhkan
        $allHistory = $market->getAllHistoricalPrices($days);

        $data = match ($asset) {
            'btc'   => $allHistory['bitcoin'] ?? ['success' => false, 'error' => 'Data BTC tidak tersedia'],
            'stock' => $allHistory['stock']   ?? ['success' => false, 'error' => 'Data IHSG tidak tersedia'],
            'gold'  => $allHistory['gold']    ?? ['success' => false, 'error' => 'Data Emas tidak tersedia'],
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

        $riskProfile  = RiskProfile::where('nim', $user->nim)->first();
        $profilRisiko = $riskProfile->tipe_risiko ?? 'Moderat';

        $marketData = $market->getAllCurrentPrices();

        $narasi = $nlg->generateNarasiEdukasi($profilRisiko, $marketData);

        return view('edukasi.narasi', compact(
            'profilRisiko',
            'marketData',
            'narasi'
        ));
    }
}