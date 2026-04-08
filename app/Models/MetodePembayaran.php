<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetodePembayaran extends Model
{
    use HasFactory;

    protected $table = 'metode_pembayaran'; // biar gak jadi metode_pembayarans

    protected $guarded = [];

    // Relasi ke pembayaran (1 metode bisa banyak pembayaran)
    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class, 'metode_pembayaran_id');
    }
    public function setKodeMetodeAttribute($value)
    {
        if (str_starts_with($value, 'MP-')) {
            $this->attributes['kode_metode'] = strtoupper($value);
        } else {
            $this->attributes['kode_metode'] = 'MP-' . strtoupper($value);
        }
    }
}