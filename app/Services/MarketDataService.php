<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\Response;

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
     * Cache TTL dalam detik (5 menit)
     */
    protected int $cacheTtl = 300;

    /**
     * User-Agent agar Yahoo Finance tidak memblokir
     */
    protected string $userAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36';

    // ============================================================
    // ⚡ CONCURRENT FETCH — KUNCI OPTIMASI FASE C
    // ============================================================

    /**
     * Ambil SEMUA harga saat ini secara PARALEL (concurrent)
     * Ini yang bikin aplikasi ngebut 5-10x lipat
     *
     * @return array
     */
    public function getAllCurrentPrices(): array
    {
        $cacheKey = 'market.all.current.v2';

        return Cache::remember($cacheKey, $this->cacheTtl, function () {
            $startTime = microtime(true);

            $requests = [
                'bitcoin' => [
                    'url' => "{$this->coingeckoBase}/simple/price",
                    'params' => [
                        'ids'                     => 'bitcoin',
                        'vs_currencies'           => 'usd',
                        'include_24hr_change'     => 'true',
                        'include_last_updated_at' => 'true',
                    ],
                ],
                'stock' => [
                    'url' => "{$this->yahooBase}/v8/finance/chart/^JKSE",
                    'params' => [
                        'interval' => '1d',
                        'range'    => '1d',
                    ],
                ],
                'gold' => [
                    'url' => "{$this->xausBase}/spot",
                    'params' => [
                        'unit' => 'oz',
                    ],
                ],
            ];

            try {
                $responses = Http::pool(function ($pool) use ($requests) {
                    $promises = [];

                    foreach ($requests as $key => $config) {
                        $promises[$key] = $pool->as($key)
                            ->timeout($this->timeout)
                            ->withHeaders([
                                'User-Agent' => $this->userAgent,
                                'Accept'     => 'application/json',
                            ])
                            ->get($config['url'], $config['params']);
                    }

                    return $promises;
                });

                $results = [
                    'bitcoin' => $this->parseBitcoinResponse($responses['bitcoin'] ?? null),
                    'stock'   => $this->parseStockResponse($responses['stock'] ?? null, '^JKSE'),
                    'gold'    => $this->parseGoldResponse($responses['gold'] ?? null),
                ];

                $duration = round((microtime(true) - $startTime) * 1000);
                Log::info("MarketDataService: All prices fetched in {$duration}ms (concurrent)");

                return $results;

            } catch (\Exception $e) {
                Log::error('MarketDataService::getAllCurrentPrices - ' . $e->getMessage());
                return $this->getAllCurrentPricesFallback();
            }
        });
    }

    /**
     * Ambil SEMUA harga historis secara PARALEL
     *
     * @param int $days Jumlah hari
     * @return array
     */
    public function getAllHistoricalPrices(int $days = 7): array
    {
        $cacheKey = "market.all.history.{$days}.v2";

        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($days) {
            $startTime = microtime(true);

            $range = $days <= 5 ? '5d' : ($days <= 30 ? '1mo' : '3mo');

            $requests = [
                'bitcoin' => [
                    'url' => "{$this->coingeckoBase}/coins/bitcoin/market_chart",
                    'params' => [
                        'vs_currency' => 'usd',
                        'days'        => $days,
                        'interval'    => 'daily',
                    ],
                ],
                'stock' => [
                    'url' => "{$this->yahooBase}/v8/finance/chart/^JKSE",
                    'params' => [
                        'interval' => '1d',
                        'range'    => $range,
                    ],
                ],
                'gold' => [
                    'url' => "{$this->xausBase}/history",
                    'params' => [],
                ],
            ];

            try {
                $responses = Http::pool(function ($pool) use ($requests) {
                    $promises = [];

                    foreach ($requests as $key => $config) {
                        $promises[$key] = $pool->as($key)
                            ->timeout($this->timeout)
                            ->withHeaders([
                                'User-Agent' => $this->userAgent,
                                'Accept'     => 'application/json',
                            ])
                            ->get($config['url'], $config['params']);
                    }

                    return $promises;
                });

                $results = [
                    'bitcoin' => $this->parseBitcoinHistory($responses['bitcoin'] ?? null, $days),
                    'stock'   => $this->parseStockHistory($responses['stock'] ?? null, $days, '^JKSE'),
                    'gold'    => $this->parseGoldHistory($responses['gold'] ?? null, $days),
                ];

                $duration = round((microtime(true) - $startTime) * 1000);
                Log::info("MarketDataService: All history fetched in {$duration}ms (concurrent)");

                return $results;

            } catch (\Exception $e) {
                Log::error('MarketDataService::getAllHistoricalPrices - ' . $e->getMessage());

                return [
                    'bitcoin' => $this->errorResponse('BTC', 'CoinGecko', 0, $e->getMessage()),
                    'stock'   => $this->errorResponse('^JKSE', 'Yahoo Finance', 0, $e->getMessage()),
                    'gold'    => $this->errorResponse('XAU', 'xaus.com', 0, $e->getMessage()),
                ];
            }
        });
    }

    // ============================================================
    // INDIVIDUAL METHODS (Backward Compatibility)
    // ============================================================

    public function getBitcoinCurrentPrice(): array
    {
        $all = $this->getAllCurrentPrices();
        return $all['bitcoin'] ?? $this->errorResponse('BTC', 'CoinGecko');
    }

    public function getBitcoinHistoricalPrices(int $days = 7): array
    {
        $all = $this->getAllHistoricalPrices($days);
        return $all['bitcoin'] ?? $this->errorResponse('BTC', 'CoinGecko');
    }

    public function getStockCurrentPrice(string $symbol = '^JKSE'): array
    {
        $all = $this->getAllCurrentPrices();
        return $all['stock'] ?? $this->errorResponse($symbol, 'Yahoo Finance');
    }

    public function getStockHistoricalPrices(string $symbol = '^JKSE', int $days = 7): array
    {
        $all = $this->getAllHistoricalPrices($days);
        return $all['stock'] ?? $this->errorResponse($symbol, 'Yahoo Finance');
    }

    public function getGoldCurrentPrice(): array
    {
        $all = $this->getAllCurrentPrices();
        return $all['gold'] ?? $this->errorResponse('XAU', 'xaus.com');
    }

    public function getGoldHistoricalPrices(int $days = 7): array
    {
        $all = $this->getAllHistoricalPrices($days);
        return $all['gold'] ?? $this->errorResponse('XAU', 'xaus.com');
    }

    // ============================================================
    // PARSER — Konversi response API jadi format seragam
    // ============================================================

    /**
     * Parse response Bitcoin current price
     *
     * @param Response|null $response
     * @return array
     */
    protected function parseBitcoinResponse(?Response $response): array
    {
        if (!$response || !$response->successful()) {
            return $this->errorResponse('BTC', 'CoinGecko', $response?->status() ?? 0);
        }

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

    /**
     * Parse response Stock/IHSG current price
     *
     * @param Response|null $response
     * @param string $symbol
     * @return array
     */
    protected function parseStockResponse(?Response $response, string $symbol): array
    {
        if (!$response || !$response->successful()) {
            return $this->errorResponse($symbol, 'Yahoo Finance', $response?->status() ?? 0);
        }

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

    /**
     * Parse response Gold current price
     *
     * @param Response|null $response
     * @return array
     */
    protected function parseGoldResponse(?Response $response): array
    {
        if (!$response || !$response->successful()) {
            return $this->errorResponse('XAU', 'xaus.com', $response?->status() ?? 0);
        }

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

    /**
     * Parse response Bitcoin historical prices
     *
     * @param Response|null $response
     * @param int $days
     * @return array
     */
    protected function parseBitcoinHistory(?Response $response, int $days): array
    {
        if (!$response || !$response->successful()) {
            return $this->errorResponse('BTC', 'CoinGecko', $response?->status() ?? 0);
        }

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

    /**
     * Parse response Stock historical prices
     *
     * @param Response|null $response
     * @param int $days
     * @param string $symbol
     * @return array
     */
    protected function parseStockHistory(?Response $response, int $days, string $symbol): array
    {
        if (!$response || !$response->successful()) {
            return $this->errorResponse($symbol, 'Yahoo Finance', $response?->status() ?? 0);
        }

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

    /**
     * Parse response Gold historical prices
     *
     * @param Response|null $response
     * @param int $days
     * @return array
     */
    protected function parseGoldHistory(?Response $response, int $days): array
    {
        if (!$response || !$response->successful()) {
            return $this->errorResponse('XAU', 'xaus.com', $response?->status() ?? 0);
        }

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

    // ============================================================
    // FALLBACK — Kalau pool gagal, request satu per satu
    // ============================================================

    protected function getAllCurrentPricesFallback(): array
    {
        return [
            'bitcoin' => $this->fetchBitcoinFallback(),
            'stock'   => $this->fetchStockFallback(),
            'gold'    => $this->fetchGoldFallback(),
        ];
    }

    protected function fetchBitcoinFallback(): array
    {
        try {
            $response = Http::timeout($this->timeout)
                ->get("{$this->coingeckoBase}/simple/price", [
                    'ids'                     => 'bitcoin',
                    'vs_currencies'           => 'usd',
                    'include_24hr_change'     => 'true',
                    'include_last_updated_at' => 'true',
                ]);

            return $this->parseBitcoinResponse($response);
        } catch (\Exception $e) {
            return $this->errorResponse('BTC', 'CoinGecko', 0, $e->getMessage());
        }
    }

    protected function fetchStockFallback(): array
    {
        try {
            $response = Http::timeout($this->timeout)
                ->withHeaders(['User-Agent' => $this->userAgent])
                ->get("{$this->yahooBase}/v8/finance/chart/^JKSE", [
                    'interval' => '1d',
                    'range'    => '1d',
                ]);

            return $this->parseStockResponse($response, '^JKSE');
        } catch (\Exception $e) {
            return $this->errorResponse('^JKSE', 'Yahoo Finance', 0, $e->getMessage());
        }
    }

    protected function fetchGoldFallback(): array
    {
        try {
            $response = Http::timeout($this->timeout)
                ->get("{$this->xausBase}/spot", ['unit' => 'oz']);

            return $this->parseGoldResponse($response);
        } catch (\Exception $e) {
            return $this->errorResponse('XAU', 'xaus.com', 0, $e->getMessage());
        }
    }

    // ============================================================
    // HELPER
    // ============================================================

    protected function errorResponse(string $asset, string $source, int $status = 0, string $message = ''): array
    {
        return [
            'success' => false,
            'asset'   => $asset,
            'source'  => $source,
            'error'   => $message ?: "Gagal mengambil data dari {$source} (HTTP {$status})",
        ];
    }

    public function clearCache(): void
    {
        Cache::forget('market.all.current.v2');
        foreach ([7, 14, 30, 90] as $d) {
            Cache::forget("market.all.history.{$d}.v2");
        }
    }
}