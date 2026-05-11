<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemesanan extends Model
{
    use HasFactory;

    protected $table = 'pemesanan';
    protected $primaryKey = 'id_pemesanan';
    protected $guarded = [];

    public static function generateKode()
    {
        return 'ORD-' . now()->format('YmdHis');
    }

    public function details()
    {
        return $this->hasMany(DetailPemesanan::class, 'id_pemesanan');
    }
}