<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pelanggan extends Model
{
    use HasFactory;

    protected $table = 'pelanggan';

    // 1. Beritahu Laravel bahwa Primary Key (ID) adalah string, bukan angka
    protected $primaryKey = 'id';
    protected $keyType = 'string';

    // 2. Matikan fitur auto-increment bawaan karena kita buat manual
    public $incrementing = false;

    protected $guarded = [];

    // 3. Fungsi otomatis saat data baru dibuat
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Cari data terakhir untuk mengambil ID tertinggi
            $latest = self::orderBy('id', 'desc')->first();

            if (!$latest) {
                // Jika belum ada data sama sekali, mulai dari P001
                $model->id = 'P001';
            } else {
                // Mengambil angka dari ID terakhir (misal P001 diambil 001)
                $number = intval(substr($latest->id, 1));
                // Tambah 1 dan format kembali ke PXXX (P002, dst)
                $model->id = 'P' . str_pad($number + 1, 3, '0', STR_PAD_LEFT);
            }
        });
    }
}