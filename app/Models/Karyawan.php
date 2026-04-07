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

    protected $fillable = [
        'id_karyawan',
        'nama',
        'jenis_kelamin',
        'tanggal_lahir',
    ];
}