<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiskProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'nim',
        'tipe_risiko',
        'level',
    ];
}