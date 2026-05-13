<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model  // <-- Huruf kapital (konvensi Laravel)
{
    use HasFactory;

    protected $table = 'jabatan';       // ← Tambahkan ini!
    protected $primaryKey = 'id_jabatan';
    public $incrementing = false;
    protected $keyType = 'string';

    protected static function boot()
    {
        parent::boot();  // ← Hapus kurung kurawal dobel yang salah

        static::creating(function ($model) {
            $last = static::latest('id_jabatan')->first();
            $number = $last ? (int) substr($last->id_jabatan, 3) + 1 : 1;
            $model->id_jabatan = 'JBN' . str_pad($number, 2, '0', STR_PAD_LEFT);
        });
    }

    protected $fillable = [
        'id_jabatan',
        'jabatan',
        'gaji_pokok',
    ];
}