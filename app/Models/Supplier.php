<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'bahan_baku_id',
        'nama_supplier',
        'no_telephone',
        'jumlah',
        'satuan',
        'harga_satuan',
        'total',
    ];

    protected $casts = [
        'jumlah' => 'integer',
        'harga_satuan' => 'decimal:2',
    ];

    /**
     * Relasi: Supplier ini menyuplai 1 bahan baku tertentu (per baris).
     */
    public function bahanBaku()
    {
        return $this->belongsTo(BahanBaku::class, 'bahan_baku_id');
    }
}