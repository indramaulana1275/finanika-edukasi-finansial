<?php

namespace App\Services;

use App\Models\ChatMessage;
use App\Models\EducationModule;
use App\Models\RiskProfile;
use App\Models\UserModuleProgress;
use Illuminate\Support\Collection;

class ChatService
{
    public function __construct(
        protected NlgService $nlg,
        protected MarketDataService $market
    ) {}

    /**
     * Proses chat user dengan FinGo AI.
     *
     * @return array ['success' => bool, 'reply' => string, 'error' => string|null]
     */
    public function chat(int $userId, string $userMessage): array
    {
        // Ambil profil risiko user
        $riskProfile = RiskProfile::where('nim', auth()->user()->nim ?? null)->first();
        $profilRisiko = $riskProfile->tipe_risiko ?? 'Moderat';

        // Ambil harga saat ini lengkap (cache 5 menit)
        $marketPrices = $this->market->getAllCurrentPrices();
        $forexPrices = $this->market->getAllCurrentForex();
        $sahamUSPrices = $this->market->getAllCurrentSahamUS();
        $commodityPrices = $this->market->getAllCurrentCommodities();

        // Ambil 10 pesan terakhir untuk konteks (terbaru dulu)
        $recentMessages = ChatMessage::where('id_user', $userId)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get()
            ->map(fn ($m) => [
                'role' => $m->role,
                'content' => $m->content,
            ])
            ->toArray();

        // Balikkan agar urutan dari terlama ke terbaru untuk prompt
        $chatHistory = array_reverse($recentMessages);

        // Progress modul edukasi
        $moduleProgressRecords = UserModuleProgress::where('id_user', $userId)
            ->with('module')
            ->get();
        $completedCount = $moduleProgressRecords->where('completed', true)->count();
        $totalModules = EducationModule::count();
        $nextModule = EducationModule::whereNotIn(
            'id',
            $moduleProgressRecords->where('completed', true)->pluck('module_id')
        )->orderBy('order_index')->first()?->title;

        // Kirim ke Gemini
        $reply = $this->nlg->chatWithFinGo($userMessage, [
            'profil_risiko' => $profilRisiko,
            'market_prices' => $marketPrices,
            'forex_prices' => $forexPrices,
            'saham_us' => $sahamUSPrices,
            'commodities' => $commodityPrices,
            'chat_history' => $chatHistory,
            'user_name' => auth()->user()->name ?? 'Teman',
            'module_progress' => [
                'completed' => $completedCount,
                'total' => $totalModules,
                'next_module' => $nextModule,
            ],
        ]);

        // Simpan pesan user
        ChatMessage::create([
            'id_user' => $userId,
            'role' => 'user',
            'content' => $userMessage,
            'metadata' => null,
        ]);

        // Simpan pesan AI
        ChatMessage::create([
            'id_user' => $userId,
            'role' => 'assistant',
            'content' => $reply,
            'metadata' => null,
        ]);

        return [
            'success' => true,
            'reply' => $reply,
            'error' => null,
        ];
    }

    /**
     * Ambil history chat user (terlama ke terbaru).
     */
    public function getChatHistory(int $userId, int $limit = 50): Collection
    {
        return ChatMessage::where('id_user', $userId)
            ->orderBy('created_at', 'asc')
            ->take($limit)
            ->get();
    }

    /**
     * Hapus semua chat history user.
     */
    public function clearChat(int $userId): bool
    {
        return (bool) ChatMessage::where('id_user', $userId)->delete();
    }
}
