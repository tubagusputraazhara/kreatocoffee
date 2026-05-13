<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class jabatan extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_jabatan'; // sesuai kolom di migrasi
    public $incrementing = false;          // jika bukan auto increment
    protected $keyType = 'string';         // jika tipenya string (KYN001)

    protected static function boot()
{
{
    parent::boot();

    static::creating(function ($model) {
        $last = static::latest('id_jabatan')->first();
        $number = $last ? (int) substr($last->id_jabatan, 3) + 1 : 1;
        $model->id_jabatan = 'JBN' . str_pad($number, 2, '0', STR_PAD_LEFT);
    });
}
}
    protected $fillable = [
        'id_jabatan',
        'jabatan',
        'gaji_pokok',
    ];
}

