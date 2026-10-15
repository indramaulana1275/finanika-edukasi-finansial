<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NlgService
{
    /**
     * Google Gemini API Key
     */
    protected string $apiKey;

    /**
     * Base URL Gemini API (gemini-1.5-flash)
     */
    protected string $baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent';

    /**
     * Timeout request (detik)
     */
    protected int $timeout = 60;

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key') ?? env('GEMINI_API_KEY');
    }

    /**
     * ============================================================
     * Generate narasi edukasi finansial berdasarkan profil risiko
     * dan data pasar terkini.
     *
     * @param string $profilRisiko  'Konservatif' | 'Moderat' | 'Agresif'
     * @param array  $marketData    ['bitcoin' => [...], 'stock' => [...], 'gold' => [...]]
     * @return string               Narasi edukasi (2 paragraf)
     * ============================================================
     */
    public function generateNarasiEdukasi(string $profilRisiko, array $marketData): string
    {
        // ---------------------------------------------------------
        // 1. Ekstrak data penting dari $marketData
        // ---------------------------------------------------------
        $btcPrice  = $marketData['bitcoin']['price_usd'] ?? null;
        $btcChange = $marketData['bitcoin']['change_24h'] ?? 0;

        $ihsgPrice  = $marketData['stock']['price'] ?? null;
        $ihsgChange = $marketData['stock']['change_percent'] ?? 0;

        $goldPrice  = $marketData['gold']['price_usd'] ?? null;

        // Format angka agar mudah dibaca
        $btcPriceFormatted  = $btcPrice  ? '$ ' . number_format($btcPrice, 2, '.', ',') : 'tidak tersedia';
        $ihsgPriceFormatted = $ihsgPrice ? 'Rp ' . number_format($ihsgPrice, 0, ',', '.') : 'tidak tersedia';
        $goldPriceFormatted = $goldPrice ? '$ ' . number_format($goldPrice, 2, '.', ',') . ' / troy ounce' : 'tidak tersedia';

        // ---------------------------------------------------------
        // 2. Susun System Prompt (gaya Gen Z, ahli, ringkas)
        // ---------------------------------------------------------
        $systemInstruction = <<<PROMPT
Kamu adalah "FinGo", penasihat keuangan digital yang ahli dan berpengalaman,
tapi gaya bahasamu santai, modern, dan asyik — cocok untuk Gen Z (mahasiswa).
Gunakan bahasa Indonesia gaul yang sopan (boleh pakai "kamu", "nih", "sih", "gitu", "banget"),
hindari bahasa formal kaku seperti "Anda" atau "demikian".

Tugasmu: berikan narasi edukasi finansial MAKSIMAL 2 PARAGRAF PENDEK (total 80-120 kata).

Paragraf 1: Analisis singkat kondisi pasar dari 3 aset (Bitcoin, IHSG, Emas)
           berdasarkan data harga yang diberikan. Sebutkan arah pergerakan
           (naik/turun) secara natural, jangan sekadar membaca angka.

Paragraf 2: Berikan rekomendasi strategi DCA (Dollar Cost Averaging) yang
           DISESUAIKAN dengan profil risiko user. Jelaskan kenapa strategi
           itu cocok, dan sebutkan porsi alokasi ideal secara umum
           (contoh: 50% ke aset stabil, 30% ke saham, 20% ke kripto).

Aturan penting:
- JANGAN gunakan bullet point atau numbering. Tulis dalam paragraf mengalir.
- JANGAN gunakan emoji berlebihan (maksimal 1-2 emoji saja).
- JANGAN mengulang angka mentah; interpretasikan.
- Akhiri dengan satu kalimat penyemangat singkat.
PROMPT;

        // ---------------------------------------------------------
        // 3. Susun User Prompt (data yang diberikan ke Gemini)
        // ---------------------------------------------------------
        $userPrompt = <<<PROMPT
Profil risiko saya: {$profilRisiko}

Data pasar saat ini:
- Bitcoin (BTC): {$btcPriceFormatted} (perubahan 24 jam: {$btcChange}%)
- IHSG (^JKSE): {$ihsgPriceFormatted} (perubahan: {$ihsgChange}%)
- Emas (XAU/USD): {$goldPriceFormatted}

Tolong berikan analisis dan rekomendasi strategi DCA yang cocok untuk saya.
PROMPT;

        // ---------------------------------------------------------
        // 4. Kirim Request ke Gemini API
        // ---------------------------------------------------------
        try {
            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                ])
                ->post("{$this->baseUrl}?key={$this->apiKey}", [
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
                        'temperature'     => 0.8,
                        'maxOutputTokens' => 300,
                        'topP'            => 0.95,
                    ],
                ]);

            // ---------------------------------------------------------
            // 5. Handle Response
            // ---------------------------------------------------------
            if ($response->successful()) {
                $data = $response->json();

                $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;

                if ($text) {
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

    /**
     * ============================================================
     * Fallback narasi jika Gemini API gagal
     * ============================================================
     */
    protected function fallbackNarasi(string $profilRisiko): string
    {
        return "Pasar lagi dinamis nih! Harga Bitcoin, IHSG, dan Emas bergerak variatif. "
             . "Untuk kamu yang profilnya {$profilRisiko}, strategi DCA tetap paling aman — "
             . "cukup sisihkan dana rutin tiap bulan, jangan panic selling, dan fokus jangka panjang. "
             . "Ingat, konsistensi itu kunci! 💪";
    }
}