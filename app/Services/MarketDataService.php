<?php

namespace App\Services;

use Closure;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MarketDataService
{
    /**
     * Base URL untuk masing-masing API
     */
    protected string $coingeckoBase = 'https://api.coingecko.com/api/v3';

    protected string $yahooBase = 'https://query1.finance.yahoo.com';

    protected string $xausBase = 'https://xaus.com/api/v1';

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
     */
    public function getAllCurrentPrices(): array
    {
        $cacheKey = 'market.all.current.v3';

        return $this->rememberSuccessful($cacheKey, $this->cacheTtl, function () {
            $startTime = microtime(true);

            $requests = [
                'coingecko' => [
                    'url' => "{$this->coingeckoBase}/simple/price",
                    'params' => [
                        'ids' => 'bitcoin,ethereum,solana,binancecoin,cardano,bitcoin-cash,litecoin,dogecoin,polkadot,chainlink',
                        'vs_currencies' => 'usd',
                        'include_24hr_change' => 'true',
                        'include_last_updated_at' => 'true',
                    ],
                ],
                'stock' => [
                    'url' => "{$this->yahooBase}/v8/finance/chart/^JKSE",
                    'params' => [
                        'interval' => '1d',
                        'range' => '1d',
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
                                'Accept' => 'application/json',
                            ])
                            ->get($config['url'], $config['params']);
                    }

                    return $promises;
                });

                $cryptoPrices = $this->parseCoinGeckoPrices($responses['coingecko'] ?? null);

                $results = [
                    'bitcoin' => $cryptoPrices['bitcoin'] ?? $this->errorResponse('BTC', 'CoinGecko'),
                    'ethereum' => $cryptoPrices['ethereum'] ?? $this->errorResponse('ETH', 'CoinGecko'),
                    'solana' => $cryptoPrices['solana'] ?? $this->errorResponse('SOL', 'CoinGecko'),
                    'bnb' => $cryptoPrices['bnb'] ?? $this->errorResponse('BNB', 'CoinGecko'),
                    'cardano' => $cryptoPrices['cardano'] ?? $this->errorResponse('ADA', 'CoinGecko'),
                    'bitcoin-cash' => $cryptoPrices['bitcoin-cash'] ?? $this->errorResponse('BCH', 'CoinGecko'),
                    'litecoin' => $cryptoPrices['litecoin'] ?? $this->errorResponse('LTC', 'CoinGecko'),
                    'dogecoin' => $cryptoPrices['dogecoin'] ?? $this->errorResponse('DOGE', 'CoinGecko'),
                    'polkadot' => $cryptoPrices['polkadot'] ?? $this->errorResponse('DOT', 'CoinGecko'),
                    'chainlink' => $cryptoPrices['chainlink'] ?? $this->errorResponse('LINK', 'CoinGecko'),
                    'stock' => $this->parseStockResponse($responses['stock'] ?? null, '^JKSE'),
                    'gold' => $this->parseGoldResponse($responses['gold'] ?? null),
                ];

                $duration = round((microtime(true) - $startTime) * 1000);
                Log::info("MarketDataService: All prices fetched in {$duration}ms (concurrent)");

                return $results;

            } catch (\Exception $e) {
                Log::error('MarketDataService::getAllCurrentPrices - '.$e->getMessage());

                return $this->getAllCurrentPricesFallback();
            }
        });
    }

    /**
     * Ambil SEMUA harga historis secara PARALEL
     *
     * @param  int  $days  Jumlah hari
     */
    public function getAllHistoricalPrices(int $days = 7): array
    {
        $cacheKey = "market.all.history.{$days}.v3";

        // Cache 30 menit untuk menghindari rate limit CoinGecko (10 crypto sekaligus)
        return $this->rememberSuccessful($cacheKey, 1800, function () use ($days) {
            $startTime = microtime(true);

            $range = $days <= 5 ? '5d' : ($days <= 30 ? '1mo' : '3mo');

            $requests = [
                'bitcoin' => [
                    'url' => "{$this->coingeckoBase}/coins/bitcoin/market_chart",
                    'params' => [
                        'vs_currency' => 'usd',
                        'days' => $days,
                        'interval' => 'daily',
                    ],
                ],
                'ethereum' => [
                    'url' => "{$this->coingeckoBase}/coins/ethereum/market_chart",
                    'params' => [
                        'vs_currency' => 'usd',
                        'days' => $days,
                        'interval' => 'daily',
                    ],
                ],
                'solana' => [
                    'url' => "{$this->coingeckoBase}/coins/solana/market_chart",
                    'params' => [
                        'vs_currency' => 'usd',
                        'days' => $days,
                        'interval' => 'daily',
                    ],
                ],
                'bnb' => [
                    'url' => "{$this->coingeckoBase}/coins/binancecoin/market_chart",
                    'params' => [
                        'vs_currency' => 'usd',
                        'days' => $days,
                        'interval' => 'daily',
                    ],
                ],
                'cardano' => [
                    'url' => "{$this->coingeckoBase}/coins/cardano/market_chart",
                    'params' => [
                        'vs_currency' => 'usd',
                        'days' => $days,
                        'interval' => 'daily',
                    ],
                ],
                'bitcoin-cash' => [
                    'url' => "{$this->coingeckoBase}/coins/bitcoin-cash/market_chart",
                    'params' => [
                        'vs_currency' => 'usd',
                        'days' => $days,
                        'interval' => 'daily',
                    ],
                ],
                'litecoin' => [
                    'url' => "{$this->coingeckoBase}/coins/litecoin/market_chart",
                    'params' => [
                        'vs_currency' => 'usd',
                        'days' => $days,
                        'interval' => 'daily',
                    ],
                ],
                'dogecoin' => [
                    'url' => "{$this->coingeckoBase}/coins/dogecoin/market_chart",
                    'params' => [
                        'vs_currency' => 'usd',
                        'days' => $days,
                        'interval' => 'daily',
                    ],
                ],
                'polkadot' => [
                    'url' => "{$this->coingeckoBase}/coins/polkadot/market_chart",
                    'params' => [
                        'vs_currency' => 'usd',
                        'days' => $days,
                        'interval' => 'daily',
                    ],
                ],
                'chainlink' => [
                    'url' => "{$this->coingeckoBase}/coins/chainlink/market_chart",
                    'params' => [
                        'vs_currency' => 'usd',
                        'days' => $days,
                        'interval' => 'daily',
                    ],
                ],
                'stock' => [
                    'url' => "{$this->yahooBase}/v8/finance/chart/^JKSE",
                    'params' => [
                        'interval' => '1d',
                        'range' => $range,
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
                                'Accept' => 'application/json',
                            ])
                            ->get($config['url'], $config['params']);
                    }

                    return $promises;
                });

                $results = [
                    'bitcoin' => $this->parseBitcoinHistory($responses['bitcoin'] ?? null, $days),
                    'ethereum' => $this->parseCryptoHistory($responses['ethereum'] ?? null, $days, 'ethereum', 'ETH', 'Ethereum'),
                    'solana' => $this->parseCryptoHistory($responses['solana'] ?? null, $days, 'solana', 'SOL', 'Solana'),
                    'bnb' => $this->parseCryptoHistory($responses['bnb'] ?? null, $days, 'binancecoin', 'BNB', 'BNB'),
                    'cardano' => $this->parseCryptoHistory($responses['cardano'] ?? null, $days, 'cardano', 'ADA', 'Cardano'),
                    'bitcoin-cash' => $this->parseCryptoHistory($responses['bitcoin-cash'] ?? null, $days, 'bitcoin-cash', 'BCH', 'Bitcoin Cash'),
                    'litecoin' => $this->parseCryptoHistory($responses['litecoin'] ?? null, $days, 'litecoin', 'LTC', 'Litecoin'),
                    'dogecoin' => $this->parseCryptoHistory($responses['dogecoin'] ?? null, $days, 'dogecoin', 'DOGE', 'Dogecoin'),
                    'polkadot' => $this->parseCryptoHistory($responses['polkadot'] ?? null, $days, 'polkadot', 'DOT', 'Polkadot'),
                    'chainlink' => $this->parseCryptoHistory($responses['chainlink'] ?? null, $days, 'chainlink', 'LINK', 'Chainlink'),
                    'stock' => $this->parseStockHistory($responses['stock'] ?? null, $days, '^JKSE'),
                    'gold' => $this->parseGoldHistory($responses['gold'] ?? null, $days),
                ];

                $duration = round((microtime(true) - $startTime) * 1000);
                Log::info("MarketDataService: All history fetched in {$duration}ms (concurrent)");

                return $results;

            } catch (\Exception $e) {
                Log::error('MarketDataService::getAllHistoricalPrices - '.$e->getMessage());

                return [
                    'bitcoin' => $this->errorResponse('BTC', 'CoinGecko', 0, $e->getMessage()),
                    'ethereum' => $this->errorResponse('ETH', 'CoinGecko', 0, $e->getMessage()),
                    'solana' => $this->errorResponse('SOL', 'CoinGecko', 0, $e->getMessage()),
                    'bnb' => $this->errorResponse('BNB', 'CoinGecko', 0, $e->getMessage()),
                    'cardano' => $this->errorResponse('ADA', 'CoinGecko', 0, $e->getMessage()),
                    'bitcoin-cash' => $this->errorResponse('BCH', 'CoinGecko', 0, $e->getMessage()),
                    'litecoin' => $this->errorResponse('LTC', 'CoinGecko', 0, $e->getMessage()),
                    'dogecoin' => $this->errorResponse('DOGE', 'CoinGecko', 0, $e->getMessage()),
                    'polkadot' => $this->errorResponse('DOT', 'CoinGecko', 0, $e->getMessage()),
                    'chainlink' => $this->errorResponse('LINK', 'CoinGecko', 0, $e->getMessage()),
                    'stock' => $this->errorResponse('^JKSE', 'Yahoo Finance', 0, $e->getMessage()),
                    'gold' => $this->errorResponse('XAU', 'xaus.com', 0, $e->getMessage()),
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
     */
    protected function parseBitcoinResponse(?Response $response): array
    {
        if (! $response || ! $response->successful()) {
            return $this->errorResponse('BTC', 'CoinGecko', $response?->status() ?? 0);
        }

        $data = $response->json();

        return [
            'success' => true,
            'asset' => 'BTC',
            'name' => 'Bitcoin',
            'price_usd' => $data['bitcoin']['usd'] ?? null,
            'change_24h' => $data['bitcoin']['usd_24h_change'] ?? null,
            'last_updated' => $data['bitcoin']['last_updated_at'] ?? null,
        ];
    }

    /**
     * Parse response Stock/IHSG current price
     */
    protected function parseStockResponse(?Response $response, string $symbol): array
    {
        if (! $response || ! $response->successful()) {
            return $this->errorResponse($symbol, 'Yahoo Finance', $response?->status() ?? 0);
        }

        $data = $response->json();
        $result = $data['chart']['result'][0] ?? null;

        if (! $result) {
            return $this->errorResponse($symbol, 'Yahoo Finance', 0, 'Data chart kosong');
        }

        $meta = $result['meta'] ?? [];
        $quote = $result['indicators']['quote'][0] ?? [];

        $currentPrice = $meta['regularMarketPrice'] ?? ($quote['close'][0] ?? null);
        $previousClose = $meta['chartPreviousClose'] ?? ($quote['close'][1] ?? null);

        $change = null;
        $changePercent = null;

        if ($currentPrice && $previousClose) {
            $change = round($currentPrice - $previousClose, 2);
            $changePercent = round(($change / $previousClose) * 100, 2);
        }

        return [
            'success' => true,
            'asset' => $symbol,
            'name' => $meta['longName'] ?? $symbol,
            'currency' => $meta['currency'] ?? 'IDR',
            'price' => $currentPrice,
            'previous_close' => $previousClose,
            'change' => $change,
            'change_percent' => $changePercent,
            'exchange' => $meta['exchangeName'] ?? null,
        ];
    }

    /**
     * Parse response Gold current price
     */
    protected function parseGoldResponse(?Response $response): array
    {
        if (! $response || ! $response->successful()) {
            return $this->errorResponse('XAU', 'xaus.com', $response?->status() ?? 0);
        }

        $data = $response->json();

        return [
            'success' => true,
            'asset' => 'XAU',
            'name' => 'Gold (XAU/USD)',
            'price_usd' => $data['spot_usd_oz'] ?? ($data['xau']['price'] ?? null),
            'unit' => 'oz',
            'data_state' => $data['data_state']['status'] ?? null,
            'as_of' => $data['data_state']['as_of'] ?? null,
            'updated_at' => $data['updated_at'] ?? null,
        ];
    }

    /**
     * Parse response Bitcoin historical prices
     */
    protected function parseBitcoinHistory(?Response $response, int $days): array
    {
        if (! $response || ! $response->successful()) {
            return $this->errorResponse('BTC', 'CoinGecko', $response?->status() ?? 0);
        }

        $data = $response->json();
        $prices = $data['prices'] ?? [];

        return [
            'success' => true,
            'asset' => 'BTC',
            'name' => 'Bitcoin',
            'days' => $days,
            'prices' => array_map(function ($point) {
                return [
                    'timestamp' => $point[0],
                    'date' => date('Y-m-d', (int) ($point[0] / 1000)),
                    'price_usd' => round($point[1], 2),
                ];
            }, $prices),
        ];
    }

    /**
     * Parse response Stock historical prices
     */
    protected function parseStockHistory(?Response $response, int $days, string $symbol): array
    {
        if (! $response || ! $response->successful()) {
            return $this->errorResponse($symbol, 'Yahoo Finance', $response?->status() ?? 0);
        }

        $data = $response->json();
        $result = $data['chart']['result'][0] ?? null;

        if (! $result) {
            return $this->errorResponse($symbol, 'Yahoo Finance', 0, 'Data chart kosong');
        }

        $timestamps = $result['timestamp'] ?? [];
        $quote = $result['indicators']['quote'][0] ?? [];
        $closes = $quote['close'] ?? [];

        $prices = [];
        foreach ($timestamps as $i => $ts) {
            if (isset($closes[$i]) && $closes[$i] !== null) {
                $prices[] = [
                    'timestamp' => $ts * 1000,
                    'date' => date('Y-m-d', $ts),
                    'price' => round($closes[$i], 2),
                ];
            }
        }

        $prices = array_slice($prices, -$days);

        return [
            'success' => true,
            'asset' => $symbol,
            'days' => $days,
            'prices' => array_values($prices),
        ];
    }

    /**
     * Parse response Gold historical prices
     */
    protected function parseGoldHistory(?Response $response, int $days): array
    {
        if (! $response || ! $response->successful()) {
            return $this->errorResponse('XAU', 'xaus.com', $response?->status() ?? 0);
        }

        $data = $response->json();

        $history = $data['points']
            ?? $data['history']
            ?? $data['daily_closes']
            ?? $data['data']
            ?? [];

        $prices = [];
        foreach ($history as $entry) {
            $date = $entry['d'] ?? ($entry['date'] ?? ($entry['timestamp'] ?? null));
            $price = $entry['c'] ?? ($entry['close'] ?? ($entry['price'] ?? 0));

            $prices[] = [
                'timestamp' => strtotime($date) * 1000,
                'date' => $date,
                'price_usd' => round((float) $price, 2),
            ];
        }

        $prices = array_slice($prices, -$days);

        return [
            'success' => true,
            'asset' => 'XAU',
            'days' => $days,
            'prices' => array_values($prices),
        ];
    }

    // ============================================================
    // FALLBACK — Kalau pool gagal, request satu per satu
    // ============================================================

    protected function getAllCurrentPricesFallback(): array
    {
        return [
            'bitcoin' => $this->fetchBitcoinFallback(),
            'ethereum' => $this->fetchCryptoFallback('ethereum', 'ETH', 'Ethereum'),
            'solana' => $this->fetchCryptoFallback('solana', 'SOL', 'Solana'),
            'bnb' => $this->fetchCryptoFallback('binancecoin', 'BNB', 'BNB'),
            'cardano' => $this->fetchCryptoFallback('cardano', 'ADA', 'Cardano'),
            'bitcoin-cash' => $this->fetchCryptoFallback('bitcoin-cash', 'BCH', 'Bitcoin Cash'),
            'litecoin' => $this->fetchCryptoFallback('litecoin', 'LTC', 'Litecoin'),
            'dogecoin' => $this->fetchCryptoFallback('dogecoin', 'DOGE', 'Dogecoin'),
            'polkadot' => $this->fetchCryptoFallback('polkadot', 'DOT', 'Polkadot'),
            'chainlink' => $this->fetchCryptoFallback('chainlink', 'LINK', 'Chainlink'),
            'stock' => $this->fetchStockFallback(),
            'gold' => $this->fetchGoldFallback(),
        ];
    }

    protected function fetchBitcoinFallback(): array
    {
        try {
            $response = Http::timeout($this->timeout)
                ->get("{$this->coingeckoBase}/simple/price", [
                    'ids' => 'bitcoin',
                    'vs_currencies' => 'usd',
                    'include_24hr_change' => 'true',
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
                    'range' => '1d',
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
            'asset' => $asset,
            'source' => $source,
            'error' => $message ?: "Gagal mengambil data dari {$source} (HTTP {$status})",
        ];
    }

    protected function parseCryptoResponse(?Response $response, string $coinId, string $symbol, string $name): array
    {
        if (! $response || ! $response->successful()) {
            return $this->errorResponse($symbol, 'CoinGecko', $response?->status() ?? 0);
        }

        $data = $response->json();
        $coin = $data[$coinId] ?? null;

        if (! $coin) {
            return $this->errorResponse($symbol, 'CoinGecko', 0, "Data {$coinId} tidak ditemukan");
        }

        return [
            'success' => true,
            'asset' => $symbol,
            'name' => $name,
            'price_usd' => $coin['usd'] ?? null,
            'change_24h' => $coin['usd_24h_change'] ?? 0,
            'last_updated' => $coin['last_updated_at'] ?? null,
        ];
    }

    protected function parseCryptoHistory(?Response $response, int $days, string $coinId, string $symbol, string $name): array
    {
        if (! $response || ! $response->successful()) {
            return $this->errorResponse($symbol, 'CoinGecko', $response?->status() ?? 0);
        }

        $data = $response->json();
        $prices = $data['prices'] ?? [];

        return [
            'success' => true,
            'asset' => $symbol,
            'name' => $name,
            'days' => $days,
            'prices' => array_map(function ($point) {
                return [
                    'timestamp' => $point[0],
                    'date' => date('Y-m-d', (int) ($point[0] / 1000)),
                    'price_usd' => round($point[1], 2),
                ];
            }, $prices),
        ];
    }

    protected function fetchCryptoFallback(string $coinId, string $symbol, string $name): array
    {
        try {
            $response = Http::timeout($this->timeout)
                ->get("{$this->coingeckoBase}/simple/price", [
                    'ids' => $coinId,
                    'vs_currencies' => 'usd',
                    'include_24hr_change' => 'true',
                    'include_last_updated_at' => 'true',
                ]);

            return $this->parseCryptoResponse($response, $coinId, $symbol, $name);
        } catch (\Exception $e) {
            return $this->errorResponse($symbol, 'CoinGecko', 0, $e->getMessage());
        }
    }

    public function getAllCurrentForex(): array
    {
        $cacheKey = 'market.all.forex.current.v1';

        return $this->rememberSuccessful($cacheKey, $this->cacheTtl, function () {
            $requests = [
                'usd' => ['url' => "{$this->yahooBase}/v8/finance/chart/USDIDR=X", 'params' => ['interval' => '1d', 'range' => '1d']],
                'eur' => ['url' => "{$this->yahooBase}/v8/finance/chart/EURIDR=X", 'params' => ['interval' => '1d', 'range' => '1d']],
                'jpy' => ['url' => "{$this->yahooBase}/v8/finance/chart/JPYIDR=X", 'params' => ['interval' => '1d', 'range' => '1d']],
                'cny' => ['url' => "{$this->yahooBase}/v8/finance/chart/CNYIDR=X", 'params' => ['interval' => '1d', 'range' => '1d']],
                'gbp' => ['url' => "{$this->yahooBase}/v8/finance/chart/GBPIDR=X", 'params' => ['interval' => '1d', 'range' => '1d']],
                'sgd' => ['url' => "{$this->yahooBase}/v8/finance/chart/SGDIDR=X", 'params' => ['interval' => '1d', 'range' => '1d']],
            ];

            try {
                $responses = Http::pool(function ($pool) use ($requests) {
                    $promises = [];
                    foreach ($requests as $key => $config) {
                        $promises[$key] = $pool->as($key)
                            ->timeout($this->timeout)
                            ->withHeaders(['User-Agent' => $this->userAgent, 'Accept' => 'application/json'])
                            ->get($config['url'], $config['params']);
                    }

                    return $promises;
                });

                return [
                    'usd' => $this->parseForexResponse($responses['usd'] ?? null, 'USD', 'USD/IDR'),
                    'eur' => $this->parseForexResponse($responses['eur'] ?? null, 'EUR', 'EUR/IDR'),
                    'jpy' => $this->parseForexResponse($responses['jpy'] ?? null, 'JPY', 'JPY/IDR'),
                    'cny' => $this->parseForexResponse($responses['cny'] ?? null, 'CNY', 'CNY/IDR'),
                    'gbp' => $this->parseForexResponse($responses['gbp'] ?? null, 'GBP', 'GBP/IDR'),
                    'sgd' => $this->parseForexResponse($responses['sgd'] ?? null, 'SGD', 'SGD/IDR'),
                ];
            } catch (\Exception $e) {
                Log::error('MarketDataService::getAllCurrentForex - '.$e->getMessage());

                return [
                    'usd' => $this->errorResponse('USD/IDR', 'Yahoo Finance', 0, $e->getMessage()),
                    'eur' => $this->errorResponse('EUR/IDR', 'Yahoo Finance', 0, $e->getMessage()),
                    'jpy' => $this->errorResponse('JPY/IDR', 'Yahoo Finance', 0, $e->getMessage()),
                    'cny' => $this->errorResponse('CNY/IDR', 'Yahoo Finance', 0, $e->getMessage()),
                    'gbp' => $this->errorResponse('GBP/IDR', 'Yahoo Finance', 0, $e->getMessage()),
                    'sgd' => $this->errorResponse('SGD/IDR', 'Yahoo Finance', 0, $e->getMessage()),
                ];
            }
        });
    }

    public function getAllHistoricalForex(int $days = 7): array
    {
        $cacheKey = "market.all.forex.history.{$days}.v1";

        return $this->rememberSuccessful($cacheKey, $this->cacheTtl, function () use ($days) {
            $range = $days <= 5 ? '5d' : ($days <= 30 ? '1mo' : '3mo');

            $symbols = [
                'usd' => 'USDIDR=X',
                'eur' => 'EURIDR=X',
                'jpy' => 'JPYIDR=X',
                'cny' => 'CNYIDR=X',
                'gbp' => 'GBPIDR=X',
                'sgd' => 'SGDIDR=X',
            ];

            $requests = [];
            foreach ($symbols as $key => $symbol) {
                $requests[$key] = [
                    'url' => "{$this->yahooBase}/v8/finance/chart/{$symbol}",
                    'params' => ['interval' => '1d', 'range' => $range],
                ];
            }

            try {
                $responses = Http::pool(function ($pool) use ($requests) {
                    $promises = [];
                    foreach ($requests as $key => $config) {
                        $promises[$key] = $pool->as($key)
                            ->timeout($this->timeout)
                            ->withHeaders(['User-Agent' => $this->userAgent, 'Accept' => 'application/json'])
                            ->get($config['url'], $config['params']);
                    }

                    return $promises;
                });

                $results = [];
                foreach ($symbols as $key => $symbol) {
                    $results[$key] = $this->parseStockHistory($responses[$key] ?? null, $days, $symbol);
                }

                return $results;
            } catch (\Exception $e) {
                Log::error('MarketDataService::getAllHistoricalForex - '.$e->getMessage());
                $results = [];
                foreach ($symbols as $key => $symbol) {
                    $results[$key] = $this->errorResponse($symbol, 'Yahoo Finance', 0, $e->getMessage());
                }

                return $results;
            }
        });
    }

    protected function parseForexResponse(?Response $response, string $currencyCode, string $pairName): array
    {
        $parsed = $this->parseStockResponse($response, $pairName);
        if (! ($parsed['success'] ?? false)) {
            return $parsed;
        }

        return [
            'success' => true,
            'asset' => $pairName,
            'name' => $currencyCode,
            'pair' => $pairName,
            'rate' => $parsed['price'] ?? null,
            'previous_close' => $parsed['previous_close'] ?? null,
            'change' => $parsed['change'] ?? null,
            'change_percent' => $parsed['change_percent'] ?? null,
        ];
    }

    protected function parseCoinGeckoPrices(?Response $response): array
    {
        if (! $response || ! $response->successful()) {
            return [
                'bitcoin' => $this->errorResponse('BTC', 'CoinGecko', $response?->status() ?? 0),
                'ethereum' => $this->errorResponse('ETH', 'CoinGecko', $response?->status() ?? 0),
                'solana' => $this->errorResponse('SOL', 'CoinGecko', $response?->status() ?? 0),
                'bnb' => $this->errorResponse('BNB', 'CoinGecko', $response?->status() ?? 0),
                'cardano' => $this->errorResponse('ADA', 'CoinGecko', $response?->status() ?? 0),
                'bitcoin-cash' => $this->errorResponse('BCH', 'CoinGecko', $response?->status() ?? 0),
                'litecoin' => $this->errorResponse('LTC', 'CoinGecko', $response?->status() ?? 0),
                'dogecoin' => $this->errorResponse('DOGE', 'CoinGecko', $response?->status() ?? 0),
                'polkadot' => $this->errorResponse('DOT', 'CoinGecko', $response?->status() ?? 0),
                'chainlink' => $this->errorResponse('LINK', 'CoinGecko', $response?->status() ?? 0),
            ];
        }

        $data = $response->json();
        $coins = [
            'bitcoin' => ['id' => 'bitcoin',  'symbol' => 'BTC', 'name' => 'Bitcoin'],
            'ethereum' => ['id' => 'ethereum', 'symbol' => 'ETH', 'name' => 'Ethereum'],
            'solana' => ['id' => 'solana',   'symbol' => 'SOL', 'name' => 'Solana'],
            'bnb' => ['id' => 'binancecoin', 'symbol' => 'BNB', 'name' => 'BNB'],
            'cardano' => ['id' => 'cardano', 'symbol' => 'ADA', 'name' => 'Cardano'],
            'bitcoin-cash' => ['id' => 'bitcoin-cash', 'symbol' => 'BCH', 'name' => 'Bitcoin Cash'],
            'litecoin' => ['id' => 'litecoin', 'symbol' => 'LTC', 'name' => 'Litecoin'],
            'dogecoin' => ['id' => 'dogecoin', 'symbol' => 'DOGE', 'name' => 'Dogecoin'],
            'polkadot' => ['id' => 'polkadot', 'symbol' => 'DOT', 'name' => 'Polkadot'],
            'chainlink' => ['id' => 'chainlink', 'symbol' => 'LINK', 'name' => 'Chainlink'],
        ];

        $result = [];
        foreach ($coins as $key => $meta) {
            $coin = $data[$meta['id']] ?? null;
            if (! $coin) {
                $result[$key] = $this->errorResponse($meta['symbol'], 'CoinGecko', 0, "Data {$meta['id']} tidak ditemukan");

                continue;
            }

            $result[$key] = [
                'success' => true,
                'asset' => $meta['symbol'],
                'name' => $meta['name'],
                'price_usd' => $coin['usd'] ?? null,
                'change_24h' => $coin['usd_24h_change'] ?? 0,
                'last_updated' => $coin['last_updated_at'] ?? null,
            ];
        }

        return $result;
    }

    public function getAllCurrentSahamUS(): array
    {
        $cacheKey = 'market.sahamus.current.v1';

        return $this->rememberSuccessful($cacheKey, $this->cacheTtl, function () {
            $startTime = microtime(true);

            $requests = [
                'nasdaq' => [
                    'url' => "{$this->yahooBase}/v8/finance/chart/^IXIC",
                    'params' => ['interval' => '1d', 'range' => '1d'],
                ],
                'sp500' => [
                    'url' => "{$this->yahooBase}/v8/finance/chart/^GSPC",
                    'params' => ['interval' => '1d', 'range' => '1d'],
                ],
                'dowjones' => [
                    'url' => "{$this->yahooBase}/v8/finance/chart/^DJI",
                    'params' => ['interval' => '1d', 'range' => '1d'],
                ],
                'russell2000' => [
                    'url' => "{$this->yahooBase}/v8/finance/chart/^RUT",
                    'params' => ['interval' => '1d', 'range' => '1d'],
                ],
                'vix' => [
                    'url' => "{$this->yahooBase}/v8/finance/chart/^VIX",
                    'params' => ['interval' => '1d', 'range' => '1d'],
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
                                'Accept' => 'application/json',
                            ])
                            ->get($config['url'], $config['params']);
                    }

                    return $promises;
                });

                $results = [
                    'nasdaq' => $this->parseStockResponse($responses['nasdaq'] ?? null, '^IXIC'),
                    'sp500' => $this->parseStockResponse($responses['sp500'] ?? null, '^GSPC'),
                    'dowjones' => $this->parseStockResponse($responses['dowjones'] ?? null, '^DJI'),
                    'russell2000' => $this->parseStockResponse($responses['russell2000'] ?? null, '^RUT'),
                    'vix' => $this->parseStockResponse($responses['vix'] ?? null, '^VIX'),
                ];

                $duration = round((microtime(true) - $startTime) * 1000);
                Log::info("MarketDataService: Saham US fetched in {$duration}ms (concurrent)");

                return $results;
            } catch (\Exception $e) {
                Log::error('MarketDataService::getAllCurrentSahamUS - '.$e->getMessage());

                return [
                    'nasdaq' => $this->errorResponse('^IXIC', 'Yahoo Finance', 0, $e->getMessage()),
                    'sp500' => $this->errorResponse('^GSPC', 'Yahoo Finance', 0, $e->getMessage()),
                    'dowjones' => $this->errorResponse('^DJI', 'Yahoo Finance', 0, $e->getMessage()),
                    'russell2000' => $this->errorResponse('^RUT', 'Yahoo Finance', 0, $e->getMessage()),
                    'vix' => $this->errorResponse('^VIX', 'Yahoo Finance', 0, $e->getMessage()),
                ];
            }
        });
    }

    public function getAllHistoricalSahamUS(int $days = 7): array
    {
        $cacheKey = "market.sahamus.history.{$days}.v1";
        $range = $days <= 5 ? '5d' : ($days <= 30 ? '1mo' : '3mo');

        return $this->rememberSuccessful($cacheKey, $this->cacheTtl, function () use ($days, $range) {
            $requests = [
                'nasdaq' => ['url' => "{$this->yahooBase}/v8/finance/chart/^IXIC", 'params' => ['interval' => '1d', 'range' => $range]],
                'sp500' => ['url' => "{$this->yahooBase}/v8/finance/chart/^GSPC", 'params' => ['interval' => '1d', 'range' => $range]],
                'dowjones' => ['url' => "{$this->yahooBase}/v8/finance/chart/^DJI", 'params' => ['interval' => '1d', 'range' => $range]],
                'russell2000' => ['url' => "{$this->yahooBase}/v8/finance/chart/^RUT", 'params' => ['interval' => '1d', 'range' => $range]],
                'vix' => ['url' => "{$this->yahooBase}/v8/finance/chart/^VIX", 'params' => ['interval' => '1d', 'range' => $range]],
            ];

            try {
                $responses = Http::pool(function ($pool) use ($requests) {
                    $promises = [];
                    foreach ($requests as $key => $config) {
                        $promises[$key] = $pool->as($key)
                            ->timeout($this->timeout)
                            ->withHeaders([
                                'User-Agent' => $this->userAgent,
                                'Accept' => 'application/json',
                            ])
                            ->get($config['url'], $config['params']);
                    }

                    return $promises;
                });

                return [
                    'nasdaq' => $this->parseStockHistory($responses['nasdaq'] ?? null, $days, '^IXIC'),
                    'sp500' => $this->parseStockHistory($responses['sp500'] ?? null, $days, '^GSPC'),
                    'dowjones' => $this->parseStockHistory($responses['dowjones'] ?? null, $days, '^DJI'),
                    'russell2000' => $this->parseStockHistory($responses['russell2000'] ?? null, $days, '^RUT'),
                    'vix' => $this->parseStockHistory($responses['vix'] ?? null, $days, '^VIX'),
                ];
            } catch (\Exception $e) {
                return [
                    'nasdaq' => $this->errorResponse('^IXIC', 'Yahoo Finance', 0, $e->getMessage()),
                    'sp500' => $this->errorResponse('^GSPC', 'Yahoo Finance', 0, $e->getMessage()),
                    'dowjones' => $this->errorResponse('^DJI', 'Yahoo Finance', 0, $e->getMessage()),
                    'russell2000' => $this->errorResponse('^RUT', 'Yahoo Finance', 0, $e->getMessage()),
                    'vix' => $this->errorResponse('^VIX', 'Yahoo Finance', 0, $e->getMessage()),
                ];
            }
        });
    }

    public function clearCache(): void
    {
        Cache::forget('market.all.current.v3');
        Cache::forget('market.all.commodity.current.v1');
        foreach ([7, 14, 30, 90] as $d) {
            Cache::forget("market.all.history.{$d}.v3");
            Cache::forget("market.all.commodity.history.{$d}.v1");
        }
    }

    /**
     * Ambil semua harga komoditas saat ini (Yahoo Finance).
     */
    public function getAllCurrentCommodities(): array
    {
        $cacheKey = 'market.commodities.current.v2';

        return $this->rememberSuccessful($cacheKey, $this->cacheTtl, function () {
            $requests = [
                'silver' => [
                    'url' => "{$this->yahooBase}/v8/finance/chart/SI=F",
                    'params' => ['interval' => '1d', 'range' => '1d'],
                ],
                'crude-oil' => [
                    'url' => "{$this->yahooBase}/v8/finance/chart/CL=F",
                    'params' => ['interval' => '1d', 'range' => '1d'],
                ],
                'natural-gas' => [
                    'url' => "{$this->yahooBase}/v8/finance/chart/NG=F",
                    'params' => ['interval' => '1d', 'range' => '1d'],
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
                                'Accept' => 'application/json',
                            ])
                            ->get($config['url'], $config['params']);
                    }

                    return $promises;
                });

                // Ambil gold dari getAllCurrentPrices (sudah ada di xaus.com)
                $allPrices = $this->getAllCurrentPrices();
                $goldData = $allPrices['gold'] ?? ['success' => false];

                // Override symbol & name untuk gold
                if ($goldData['success'] ?? false) {
                    $goldData['asset'] = 'XAU';
                    $goldData['name'] = 'Emas (XAU/USD)';
                    $goldData['is_commodity'] = true;
                }

                return [
                    'gold' => $goldData,
                    'silver' => $this->parseStockResponse($responses['silver'] ?? null, 'SI=F'),
                    'crude-oil' => $this->parseStockResponse($responses['crude-oil'] ?? null, 'CL=F'),
                    'natural-gas' => $this->parseStockResponse($responses['natural-gas'] ?? null, 'NG=F'),
                ];
            } catch (\Exception $e) {
                Log::error('MarketDataService::getAllCurrentCommodities - '.$e->getMessage());

                return [
                    'gold' => $this->errorResponse('XAU', 'xaus.com', 0, $e->getMessage()),
                    'silver' => $this->errorResponse('SI=F', 'Yahoo Finance', 0, $e->getMessage()),
                    'crude-oil' => $this->errorResponse('CL=F', 'Yahoo Finance', 0, $e->getMessage()),
                    'natural-gas' => $this->errorResponse('NG=F', 'Yahoo Finance', 0, $e->getMessage()),
                ];
            }
        });
    }

    /**
     * Ambil semua histori komoditas (Yahoo Finance).
     */
    public function getAllHistoricalCommodities(int $days = 7): array
    {
        $cacheKey = "market.all.commodity.history.{$days}.v1";
        $range = $days <= 5 ? '5d' : ($days <= 30 ? '1mo' : '3mo');

        return $this->rememberSuccessful($cacheKey, $this->cacheTtl, function () use ($days, $range) {
            $requests = [
                'silver' => ['url' => "{$this->yahooBase}/v8/finance/chart/SI=F", 'params' => ['interval' => '1d', 'range' => $range]],
                'crude-oil' => ['url' => "{$this->yahooBase}/v8/finance/chart/CL=F", 'params' => ['interval' => '1d', 'range' => $range]],
                'natural-gas' => ['url' => "{$this->yahooBase}/v8/finance/chart/NG=F", 'params' => ['interval' => '1d', 'range' => $range]],
            ];

            try {
                $responses = Http::pool(function ($pool) use ($requests) {
                    $promises = [];
                    foreach ($requests as $key => $config) {
                        $promises[$key] = $pool->as($key)
                            ->timeout($this->timeout)
                            ->withHeaders([
                                'User-Agent' => $this->userAgent,
                                'Accept' => 'application/json',
                            ])
                            ->get($config['url'], $config['params']);
                    }

                    return $promises;
                });

                // Ambil histori gold dari getAllHistoricalPrices (dari xaus.com)
                $allHistory = $this->getAllHistoricalPrices($days);
                $goldHistory = $allHistory['gold'] ?? ['success' => false, 'prices' => []];

                return [
                    'gold' => $goldHistory,
                    'silver' => $this->parseStockHistory($responses['silver'] ?? null, $days, 'SI=F'),
                    'crude-oil' => $this->parseStockHistory($responses['crude-oil'] ?? null, $days, 'CL=F'),
                    'natural-gas' => $this->parseStockHistory($responses['natural-gas'] ?? null, $days, 'NG=F'),
                ];
            } catch (\Exception $e) {
                Log::error('MarketDataService::getAllHistoricalCommodities - '.$e->getMessage());

                return [
                    'gold' => $this->errorResponse('XAU', 'xaus.com', 0, $e->getMessage()),
                    'silver' => $this->errorResponse('SI=F', 'Yahoo Finance', 0, $e->getMessage()),
                    'crude-oil' => $this->errorResponse('CL=F', 'Yahoo Finance', 0, $e->getMessage()),
                    'natural-gas' => $this->errorResponse('NG=F', 'Yahoo Finance', 0, $e->getMessage()),
                ];
            }
        });
    }

    /**
     * Cache data pasar hanya ketika semua sumber berhasil.
     * Mencegah respons error 429 dari CoinGecko/Yahoo ikut tersimpan.
     */
    protected function rememberSuccessful(string $cacheKey, int $ttl, Closure $callback): mixed
    {
        $cached = Cache::get($cacheKey);

        if (is_array($cached) && $this->hasSuccessfulCached($cached)) {
            return $cached;
        }

        $result = $callback();

        if (is_array($result) && $this->hasSuccessfulCached($result)) {
            Cache::put($cacheKey, $result, $ttl);
        }

        return $result;
    }

    /**
     * Ambil harga saat ini minimal untuk dashboard (hanya 3 aset: BTC, IHSG, Gold).
     * Tidak kena rate limit CoinGecko.
     */
    public function getAllCurrentPricesMinimal(): array
    {
        $cacheKey = 'market.all.current.minimal.v1';

        return $this->rememberSuccessful($cacheKey, $this->cacheTtl, function () {
            $startTime = microtime(true);

            $requests = [
                'coingecko' => [
                    'url' => "{$this->coingeckoBase}/simple/price",
                    'params' => [
                        'ids' => 'bitcoin',
                        'vs_currencies' => 'usd',
                        'include_24hr_change' => 'true',
                        'include_last_updated_at' => 'true',
                    ],
                ],
                'stock' => [
                    'url' => "{$this->yahooBase}/v8/finance/chart/^JKSE",
                    'params' => [
                        'interval' => '1d',
                        'range' => '1d',
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
                                'Accept' => 'application/json',
                            ])
                            ->get($config['url'], $config['params']);
                    }

                    return $promises;
                });

                $cryptoPrices = $this->parseCoinGeckoPrices($responses['coingecko'] ?? null);

                $results = [
                    'bitcoin' => $cryptoPrices['bitcoin'] ?? $this->errorResponse('BTC', 'CoinGecko'),
                    'stock' => $this->parseStockResponse($responses['stock'] ?? null, '^JKSE'),
                    'gold' => $this->parseGoldResponse($responses['gold'] ?? null),
                ];

                $duration = round((microtime(true) - $startTime) * 1000);
                Log::info("MarketDataService: Minimal prices fetched in {$duration}ms (3 assets)");

                return $results;

            } catch (\Exception $e) {
                Log::error('MarketDataService::getAllCurrentPricesMinimal - '.$e->getMessage());

                return [
                    'bitcoin' => $this->errorResponse('BTC', 'CoinGecko', 0, $e->getMessage()),
                    'stock' => $this->errorResponse('^JKSE', 'Yahoo Finance', 0, $e->getMessage()),
                    'gold' => $this->errorResponse('XAU', 'xaus.com', 0, $e->getMessage()),
                ];
            }
        });
    }

    /**
     * Ambil histori minimal untuk dashboard (hanya 3 aset: BTC, IHSG, Gold).
     * Cache TTL 30 menit untuk menghindari rate limit CoinGecko.
     */
    public function getAllHistoricalPricesMinimal(int $days = 7): array
    {
        $cacheKey = "market.all.history.minimal.{$days}.v1";

        return $this->rememberSuccessful($cacheKey, 1800, function () use ($days) {
            $startTime = microtime(true);

            $range = $days <= 5 ? '5d' : ($days <= 30 ? '1mo' : '3mo');

            $requests = [
                'bitcoin' => [
                    'url' => "{$this->coingeckoBase}/coins/bitcoin/market_chart",
                    'params' => [
                        'vs_currency' => 'usd',
                        'days' => $days,
                        'interval' => 'daily',
                    ],
                ],
                'stock' => [
                    'url' => "{$this->yahooBase}/v8/finance/chart/^JKSE",
                    'params' => [
                        'interval' => '1d',
                        'range' => $range,
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
                                'Accept' => 'application/json',
                            ])
                            ->get($config['url'], $config['params']);
                    }

                    return $promises;
                });

                $results = [
                    'bitcoin' => $this->parseBitcoinHistory($responses['bitcoin'] ?? null, $days),
                    'stock' => $this->parseStockHistory($responses['stock'] ?? null, $days, '^JKSE'),
                    'gold' => $this->parseGoldHistory($responses['gold'] ?? null, $days),
                ];

                $duration = round((microtime(true) - $startTime) * 1000);
                Log::info("MarketDataService: Minimal history fetched in {$duration}ms (3 assets)");

                return $results;

            } catch (\Exception $e) {
                Log::error('MarketDataService::getAllHistoricalPricesMinimal - '.$e->getMessage());

                return [
                    'bitcoin' => $this->errorResponse('BTC', 'CoinGecko', 0, $e->getMessage()),
                    'stock' => $this->errorResponse('^JKSE', 'Yahoo Finance', 0, $e->getMessage()),
                    'gold' => $this->errorResponse('XAU', 'xaus.com', 0, $e->getMessage()),
                ];
            }
        });
    }

    /**
     * Fallback CoinCap saat CoinGecko rate limit (429).
     * CoinCap IDs: bitcoin, ethereum, solana, binance-coin, cardano, xrp, avalanche, polkadot, chainlink, polygon
     */
    protected function fetchCryptoFromCoinCap(string $coinId): array
    {
        $coinCapIds = [
            'bitcoin' => 'bitcoin',
            'ethereum' => 'ethereum',
            'solana' => 'solana',
            'binancecoin' => 'binance-coin',
            'cardano' => 'cardano',
            'bitcoin-cash' => 'bitcoin-cash',
            'litecoin' => 'litecoin',
            'dogecoin' => 'dogecoin',
            'polkadot' => 'polkadot',
            'chainlink' => 'chainlink',
        ];

        $capId = $coinCapIds[$coinId] ?? $coinId;

        try {
            $response = Http::timeout($this->timeout)
                ->get("https://api.coincap.io/v2/assets/{$capId}");

            if (! $response->successful()) {
                return $this->errorResponse(strtoupper($coinId), 'CoinCap', $response->status() ?? 0);
            }

            $data = $response->json();
            $asset = $data['data'] ?? null;

            if (! $asset) {
                return $this->errorResponse(strtoupper($coinId), 'CoinCap', 0, 'Data tidak ditemukan');
            }

            return [
                'success' => true,
                'asset' => strtoupper($coinId),
                'name' => ucfirst($coinId),
                'price_usd' => (float) ($asset['priceUsd'] ?? 0),
                'change_24h' => (float) ($asset['changePercent24Hr'] ?? 0),
                'last_updated' => $asset['timestamp'] ?? null,
            ];

        } catch (\Exception $e) {
            return $this->errorResponse(strtoupper($coinId), 'CoinCap', 0, $e->getMessage());
        }
    }

    /**
     * Nilai boleh dicache bila semua sub-data berhasil dan memiliki data.
     */
    protected function hasSuccessfulCached(array $data): bool
    {
        if (empty($data)) {
            return false;
        }

        foreach ($data as $item) {
            if (! is_array($item) || ! (bool) ($item['success'] ?? false)) {
                return false;
            }

            if (array_key_exists('prices', $item) && empty($item['prices'])) {
                return false;
            }
        }

        return true;
    }
}
