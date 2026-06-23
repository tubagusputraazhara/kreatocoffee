<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketTrend extends Model
{
    use HasFactory;

    protected $table = 'market_trends';

    protected $fillable = [
        'nama_tren',
        'analisis_ai',
        'referensi_visual',
        'menu_populer',
        'kategori_terlaris',
    ];

    protected $casts = [
        'referensi_visual' => 'array',
    ];
}
