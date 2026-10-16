<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PortfolioHolding extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_user',
        'asset_symbol',
        'asset_type',
        'quantity',
        'buy_price',
    ];

    protected $casts = [
        'quantity' => 'decimal:8',
        'buy_price' => 'decimal:8',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
