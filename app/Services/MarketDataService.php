<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class MarketDataService
{
    /**
     * Base URL untuk masing-masing API
     */
    protected string $coingeckoBase = 'https://api.coingecko.com/api/v3';
    protected string $yahooBase     = 'https://query1.finance.yahoo.com';
    protected string $xausBase      = 'https://xaus.com/api/v1';

    /**
     * Timeout request (detik)
     */
    protected int $timeout = 15;

    /**
     * Cache TTL dalam detik (5 menit untuk harga saat ini)
     */
    protected int $cacheTtl = 300;

    /**
     * User-Agent agar Yahoo Finance tidak memblokir
     */
    protected string $userAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36';

    // ============================================================
    // BITCOIN (CoinGecko)
    // ============================================================

    /**
     * Ambil harga Bitcoin saat ini (USD)
     */
    public function getBitcoinCurrentPrice(): array
    {
        $cacheKey = 'market.btc.current';

        return Cache::remember($cacheKey, $this->cacheTtl, function () {
            try {
                $response = Http::timeout($this->timeout)
                    ->get("{$this->coingeckoBase}/simple/price", [
                        'ids'                     => 'bitcoin',
                        'vs_currencies'           => 'usd',
                        'include_24hr_change'     => 'true',
                        'include_last_updated_at' => 'true',
                    ]);

                if ($response->successful()) {
                    $data = $response->json();

                    return [
                        'success'      => true,
                        'asset'        => 'BTC',
                        'name'         => 'Bitcoin',
                        'price_usd'    => $data['bitcoin']['usd'] ?? null,
                        'change_24h'   => $data['bitcoin']['usd_24h_change'] ?? null,
                        'last_updated' => $data['bitcoin']['last_updated_at'] ?? null,
                    ];
                }

                return $this->errorResponse('BTC', 'CoinGecko', $response->status());
            } catch (\Exception $e) {
                Log::error('MarketDataService::getBitcoinCurrentPrice - ' . $e->getMessage());
                return $this->errorResponse('BTC', 'CoinGecko', 0, $e->getMessage());
            }
        });
    }

    /**
     * Ambil harga historis Bitcoin (7-30 hari terakhir)
     */
    public function getBitcoinHistoricalPrices(int $days = 7): array
    {
        $validDays = [1, 7, 14, 30, 90, 180, 365];
        if (!in_array($days, $validDays)) {
            $days = 7;
        }

        $cacheKey = "market.btc.history.{$days}";

        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($days) {
            try {
                $response = Http::timeout($this->timeout)
                    ->get("{$this->coingeckoBase}/coins/bitcoin/market_chart", [
                        'vs_currency' => 'usd',
                        'days'        => $days,
                        'interval'    => 'daily',
                    ]);

                if ($response->successful()) {
                    $data   = $response->json();
                    $prices = $data['prices'] ?? [];

                    return [
                        'success' => true,
                        'asset'   => 'BTC',
                        'name'    => 'Bitcoin',
                        'days'    => $days,
                        'prices'  => array_map(function ($point) {
                            return [
                                'timestamp' => $point[0],
                                'date'      => date('Y-m-d', (int)($point[0] / 1000)),
                                'price_usd' => round($point[1], 2),
                            ];
                        }, $prices),
                    ];
                }

                return $this->errorResponse('BTC', 'CoinGecko', $response->status());
            } catch (\Exception $e) {
                Log::error('MarketDataService::getBitcoinHistoricalPrices - ' . $e->getMessage());
                return $this->errorResponse('BTC', 'CoinGecko', 0, $e->getMessage());
            }
        });
    }

    // ============================================================
    // SAHAM / IHSG (Yahoo Finance)
    // ============================================================

    /**
     * Ambil harga saham/index saat ini
     * Default: IHSG (^JKSE)
     */
    public function getStockCurrentPrice(string $symbol = '^JKSE'): array
    {
        $cacheKey = 'market.stock.' . md5($symbol) . '.current';

        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($symbol) {
            try {
                $response = Http::timeout($this->timeout)
                    ->withHeaders([
                        'User-Agent' => $this->userAgent,
                        'Accept'     => 'application/json',
                    ])
                    ->get("{$this->yahooBase}/v8/finance/chart/{$symbol}", [
                        'interval' => '1d',
                        'range'    => '1d',
                    ]);

                if ($response->successful()) {
                    $data   = $response->json();
                    $result = $data['chart']['result'][0] ?? null;

                    if (!$result) {
                        return $this->errorResponse($symbol, 'Yahoo Finance', 0, 'Data chart kosong');
                    }

                    $meta  = $result['meta'] ?? [];
                    $quote = $result['indicators']['quote'][0] ?? [];

                    $currentPrice  = $meta['regularMarketPrice'] ?? ($quote['close'][0] ?? null);
                    $previousClose = $meta['chartPreviousClose'] ?? ($quote['close'][1] ?? null);

                    $change        = null;
                    $changePercent = null;

                    if ($currentPrice && $previousClose) {
                        $change        = round($currentPrice - $previousClose, 2);
                        $changePercent = round(($change / $previousClose) * 100, 2);
                    }

                    return [
                        'success'        => true,
                        'asset'          => $symbol,
                        'name'           => $meta['longName'] ?? $symbol,
                        'currency'       => $meta['currency'] ?? 'IDR',
                        'price'          => $currentPrice,
                        'previous_close' => $previousClose,
                        'change'         => $change,
                        'change_percent' => $changePercent,
                        'exchange'       => $meta['exchangeName'] ?? null,
                    ];
                }

                return $this->errorResponse($symbol, 'Yahoo Finance', $response->status());
            } catch (\Exception $e) {
                Log::error('MarketDataService::getStockCurrentPrice - ' . $e->getMessage());
                return $this->errorResponse($symbol, 'Yahoo Finance', 0, $e->getMessage());
            }
        });
    }

    /**
     * Ambil harga historis saham/index (7-30 hari terakhir)
     * Default: IHSG (^JKSE)
     */
    public function getStockHistoricalPrices(string $symbol = '^JKSE', int $days = 7): array
    {
        $days  = max(1, min($days, 30));
        $range = $days <= 5 ? '5d' : ($days <= 30 ? '1mo' : '3mo');

        $cacheKey = 'market.stock.' . md5($symbol) . ".history.{$days}";

        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($symbol, $range, $days) {
            try {
                $response = Http::timeout($this->timeout)
                    ->withHeaders([
                        'User-Agent' => $this->userAgent,
                        'Accept'     => 'application/json',
                    ])
                    ->get("{$this->yahooBase}/v8/finance/chart/{$symbol}", [
                        'interval' => '1d',
                        'range'    => $range,
                    ]);

                if ($response->successful()) {
                    $data   = $response->json();
                    $result = $data['chart']['result'][0] ?? null;

                    if (!$result) {
                        return $this->errorResponse($symbol, 'Yahoo Finance', 0, 'Data chart kosong');
                    }

                    $timestamps = $result['timestamp'] ?? [];
                    $quote      = $result['indicators']['quote'][0] ?? [];
                    $closes     = $quote['close'] ?? [];

                    $prices = [];
                    foreach ($timestamps as $i => $ts) {
                        if (isset($closes[$i]) && $closes[$i] !== null) {
                            $prices[] = [
                                'timestamp' => $ts * 1000,
                                'date'      => date('Y-m-d', $ts),
                                'price'     => round($closes[$i], 2),
                            ];
                        }
                    }

                    $prices = array_slice($prices, -$days);

                    return [
                        'success' => true,
                        'asset'   => $symbol,
                        'days'    => $days,
                        'prices'  => array_values($prices),
                    ];
                }

                return $this->errorResponse($symbol, 'Yahoo Finance', $response->status());
            } catch (\Exception $e) {
                Log::error('MarketDataService::getStockHistoricalPrices - ' . $e->getMessage());
                return $this->errorResponse($symbol, 'Yahoo Finance', 0, $e->getMessage());
            }
        });
    }

    // ============================================================
    // EMAS (XAUS Gold Data API - xaus.com)
    // ============================================================

    /**
     * Ambil harga emas (XAU/USD) saat ini
     */
    public function getGoldCurrentPrice(): array
    {
        $cacheKey = 'market.gold.current';

        return Cache::remember($cacheKey, $this->cacheTtl, function () {
            try {
                $response = Http::timeout($this->timeout)
                    ->get("{$this->xausBase}/spot", [
                        'unit' => 'oz',
                    ]);

                if ($response->successful()) {
                    $data = $response->json();

                    return [
                        'success'    => true,
                        'asset'      => 'XAU',
                        'name'       => 'Gold (XAU/USD)',
                        'price_usd'  => $data['spot_usd_oz'] ?? ($data['xau']['price'] ?? null),
                        'unit'       => 'oz',
                        'data_state' => $data['data_state']['status'] ?? null,
                        'as_of'      => $data['data_state']['as_of'] ?? null,
                        'updated_at' => $data['updated_at'] ?? null,
                    ];
                }

                return $this->errorResponse('XAU', 'xaus.com', $response->status());
            } catch (\Exception $e) {
                Log::error('MarketDataService::getGoldCurrentPrice - ' . $e->getMessage());
                return $this->errorResponse('XAU', 'xaus.com', 0, $e->getMessage());
            }
        });
    }

    /**
     * Ambil harga historis emas (daily closes)
     */
    public function getGoldHistoricalPrices(int $days = 7): array
    {
        $days     = max(1, min($days, 30));
        $cacheKey = "market.gold.history.{$days}";

        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($days) {
            try {
                $response = Http::timeout($this->timeout)
                    ->get("{$this->xausBase}/history");

                if ($response->successful()) {
                    $data = $response->json();

                    $history = $data['history']
                        ?? $data['daily_closes']
                        ?? $data['data']
                        ?? [];

                    $prices = [];
                    foreach ($history as $entry) {
                        $prices[] = [
                            'timestamp' => strtotime($entry['date'] ?? $entry['timestamp']) * 1000,
                            'date'      => $entry['date'] ?? date('Y-m-d', strtotime($entry['timestamp'])),
                            'price_usd' => round((float)($entry['close'] ?? $entry['price'] ?? 0), 2),
                        ];
                    }

                    $prices = array_slice($prices, -$days);

                    return [
                        'success' => true,
                        'asset'   => 'XAU',
                        'days'    => $days,
                        'prices'  => array_values($prices),
                    ];
                }

                return $this->errorResponse('XAU', 'xaus.com', $response->status());
            } catch (\Exception $e) {
                Log::error('MarketDataService::getGoldHistoricalPrices - ' . $e->getMessage());
                return $this->errorResponse('XAU', 'xaus.com', 0, $e->getMessage());
            }
        });
    }

    // ============================================================
    // METODE BANTU (Helper)
    // ============================================================

    /**
     * Ambil semua harga saat ini untuk 3 aset
     */
    public function getAllCurrentPrices(): array
    {
        return [
            'bitcoin' => $this->getBitcoinCurrentPrice(),
            'stock'   => $this->getStockCurrentPrice('^JKSE'),  // ← IHSG
            'gold'    => $this->getGoldCurrentPrice(),
        ];
    }

    /**
     * Format response error standar
     */
    protected function errorResponse(string $asset, string $source, int $status = 0, string $message = ''): array
    {
        return [
            'success' => false,
            'asset'   => $asset,
            'source'  => $source,
            'error'   => $message ?: "Gagal mengambil data dari {$source} (HTTP {$status})",
        ];
    }

    /**
     * Bersihkan cache untuk semua data market
     */
    public function clearCache(): void
    {
        Cache::forget('market.btc.current');
        Cache::forget('market.gold.current');
        foreach ([1, 7, 14, 30] as $d) {
            Cache::forget("market.btc.history.{$d}");
            Cache::forget("market.gold.history.{$d}");
        }
    }
}