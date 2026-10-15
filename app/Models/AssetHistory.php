<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_user',
        'nama_aset',
        'harga_saat_ini',
        'simulasi_dca',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}