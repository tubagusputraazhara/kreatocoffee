<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemesanan extends Model
{
    use HasFactory;

    protected $table = 'pemesanan'; // Nama tabel eksplisit
    protected $primaryKey = 'id_pemesanan'; // Primary key menggunakan id_pemesanan
    public $incrementing = false; // Karena isinya P-0001 (bukan angka 1,2,3)
    protected $keyType = 'string'; // Isinya berupa huruf/string

    protected $guarded = []; // Mengizinkan semua data masuk
}