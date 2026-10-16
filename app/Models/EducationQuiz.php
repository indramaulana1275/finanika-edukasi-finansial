<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EducationQuiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'module_id',
        'question',
        'option_a',
        'option_b',
        'option_c',
        'option_d',
        'correct_answer',
        'explanation',
        'order_index',
    ];

    protected $casts = [
        'correct_answer' => 'string',
    ];

    public function module(): BelongsTo
    {
        return $this->belongsTo(EducationModule::class, 'module_id');
    }
}
