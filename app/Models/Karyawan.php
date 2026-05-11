<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_karyawan'; // sesuai kolom di migrasi
    public $incrementing = false;          // jika bukan auto increment
    protected $keyType = 'string';         // jika tipenya string (KYN001)

    protected static function boot()
{
    parent::boot();

    static::creating(function ($model) {
        $last = static::latest('id_karyawan')->first();
        $number = $last ? (int) substr($last->id_karyawan, 3) + 1 : 1;
        $model->id_karyawan = 'KYN' . str_pad($number, 2, '0', STR_PAD_LEFT);
    });
}
    protected $fillable = [
        'id_karyawan',
        'nama',
        'jenis_kelamin',
        'tanggal_lahir',
        'gaji',
    ];
}