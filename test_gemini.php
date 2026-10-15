<?php

$root = __DIR__;
$env = file_get_contents($root . '/.env');
preg_match('/^GEMINI_API_KEY=(.+)$/m', $env, $m);
$apiKey = trim($m[1]);

// Model yang terverifikasi berjalan dengan API key ini.
$model = 'gemini-3.5-flash-lite';
$url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent";

$data = [
    'system_instruction' => [
        'parts' => [
            ['text' => 'Kamu adalah asisten keuangan digital untuk mahasiswa. Jawab singkat dan ramah.'],
        ],
    ],
    'contents' => [
        [
            'role' => 'user',
            'parts' => [
                ['text' => 'Profil saya Moderat. Harga Bitcoin saat ini $100, IHSG 7000 poin. Berikan 2 kalimat rekomendasi DCA.'],
            ],
        ],
    ],
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'x-goog-api-key: ' . $apiKey,
]);
curl_setopt($ch, CURLOPT_TIMEOUT, 60);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Model : {$model}\n";
echo "HTTP  : {$httpCode}\n\n";

$decoded = json_decode($response, true);
$text = $decoded['candidates'][0]['content']['parts'][0]['text'] ?? null;

if ($text) {
    echo "Jawaban Gemini:\n{$text}\n";
} else {
    echo "Response:\n{$response}\n";
}
