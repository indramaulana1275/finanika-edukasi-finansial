<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NlgService
{
    protected string $apiKey;
    protected string $baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-3.5-flash-lite:generateContent';
    protected int $timeout = 60;

    public function __construct()
    {
        $this->apiKey = env('GEMINI_API_KEY');
    }

    public function generateNarasiEdukasi(string $profilRisiko, array $marketData): string
    {
        $btcPrice  = $marketData['bitcoin']['price_usd'] ?? null;
        $btcChange = $marketData['bitcoin']['change_24h'] ?? 0;
        $ihsgPrice  = $marketData['stock']['price'] ?? null;
        $ihsgChange = $marketData['stock']['change_percent'] ?? 0;
        $goldPrice  = $marketData['gold']['price_usd'] ?? null;

        $btcPriceFormatted  = $btcPrice  ? '$ ' . number_format($btcPrice, 2, '.', ',') : 'tidak tersedia';
        $ihsgPriceFormatted = $ihsgPrice ? 'Rp ' . number_format($ihsgPrice, 0, ',', '.') : 'tidak tersedia';
        $goldPriceFormatted = $goldPrice ? '$ ' . number_format($goldPrice, 2, '.', ',') . ' / troy ounce' : 'tidak tersedia';

        $systemInstruction = <<<PROMPT
Kamu adalah "FinGo", penasihat keuangan digital untuk mahasiswa Indonesia. Gaya bahasamu santai, akrab, dan mengajar — seperti kakak tingkat yang paham finansial.

TUGASMU: Berikan narasi edukasi finansial MAKSIMAL 2 PARAGRAF dengan total 150-200 kata tentang kondisi pasar hari ini dan strategi DCA (Dollar Cost Averaging, yaitu strategi beli aset secara rutin di harga tetap) yang cocok untuk profil risiko mahasiswa.

ATURAN KETAT:
1. WAJIB 100% bahasa Indonesia. JANGAN pakai istilah Inggris seperti 'bleeding', 'bullish', 'bearish', 'FOMO', 'moon', 'dump', 'pump', 'hodl', 'guys'. Kalau perlu istilah teknis seperti DCA, langsung jelaskan artinya dalam bahasa Indonesia.
2. Paragraf 1: Jelaskan kondisi pasar 3 aset (Bitcoin, IHSG, Emas) hari ini secara mengalir. Sebut arah pergerakan (naik/turun) secara natural, dan jelaskan implikasinya untuk investor.
3. Paragraf 2: Berikan saran strategi DCA konkret yang disesuaikan dengan profil risiko user. Kalau pasar turun, edukasi bahwa ini kesempatan akumulasi (beli murah). Kalau pasar naik, ingatkan jangan terbawa emosi dan tetap disiplin jadwal DCA. Sebutkan alokasi ideal secara umum: Konservatif lebih banyak ke aset stabil (emas, deposito), sedikit ke kripto dan saham. Moderat seimbang antara aset stabil dan aset berisiko. Agresif lebih besar ke kripto dan saham, tetapi tetap ada porsi aset stabil.
4. Bahasa santai, pakai 'kamu', 'nih', 'sih', 'gitu', 'banget'. Hindari bahasa formal seperti 'Anda' atau 'demikian'.
5. Tulis dalam paragraf mengalir. JANGAN pakai bullet, numbering, atau tanda bintang.
6. Akhiri dengan satu kalimat penyemangat singkat.
7. JANGAN pakai emoji.
PROMPT;

        $userPrompt = <<<PROMPT
Profil risiko saya: {$profilRisiko}

Data pasar saat ini:
Bitcoin: {$btcPriceFormatted} (perubahan 24 jam: {$btcChange}%)
IHSG: {$ihsgPriceFormatted} (perubahan: {$ihsgChange}%)
Emas: {$goldPriceFormatted}

Tolong berikan analisis dan saran strategi DCA yang cocok untuk profil saya. Ingat: 100% bahasa Indonesia, maksimal 2 paragraf, fokus edukasi tentang cara menyikapi pasar turun versus naik.
PROMPT;

        try {
            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'x-goog-api-key' => $this->apiKey,
                    'Content-Type'   => 'application/json',
                ])
                ->post($this->baseUrl, [
                    'system_instruction' => [
                        'parts' => [
                            ['text' => $systemInstruction],
                        ],
                    ],
                    'contents' => [
                        [
                            'role'  => 'user',
                            'parts' => [
                                ['text' => $userPrompt],
                            ],
                        ],
                    ],
                    'generationConfig' => [
                        'temperature'     => 0.75,
                        'topP'            => 0.95,
                        'maxOutputTokens' => 500,
                    ],
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;

                if ($text) {
                    Log::info('NlgService: Narasi Gemini BERHASIL di-generate');
                    return trim($text);
                }

                Log::warning('NlgService: Response sukses tapi text kosong', $data);
                return $this->fallbackNarasi($profilRisiko);
            }

            Log::error('NlgService: Gemini API gagal', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);
            return $this->fallbackNarasi($profilRisiko);

        } catch (\Exception $e) {
            Log::error('NlgService: Exception - ' . $e->getMessage());
            return $this->fallbackNarasi($profilRisiko);
        }
    }

    protected function fallbackNarasi(string $profilRisiko): string
    {
        return "Pasar lagi dinamis nih! Harga Bitcoin, IHSG, dan Emas bergerak variatif. "
             . "Untuk kamu yang profilnya {$profilRisiko}, strategi DCA tetap paling aman "
             . "— cukup sisihkan dana rutin tiap bulan, jangan panik, dan fokus jangka panjang. "
             . "Ingat, konsistensi itu kunci!";
    }
}
