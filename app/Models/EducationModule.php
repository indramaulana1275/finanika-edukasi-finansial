<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EducationModule extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'title',
        'description',
        'content',
        'order_index',
        'icon',
        'category',
    ];

    public function quizzes(): HasMany
    {
        return $this->hasMany(EducationQuiz::class, 'module_id')->orderBy('order_index');
    }

    public function userProgress(): HasMany
    {
        return $this->hasMany(UserModuleProgress::class, 'module_id');
    }
}
