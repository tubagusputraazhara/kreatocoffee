<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class pembelianBahanBaku extends Model
{
    use HasFactory;

    protected $table = 'pembelianBahanBaku'; // Nama tabel sudah benar[cite: 1]

    protected $guarded = [];

    // PERHATIKAN: Jika ID tabel ini adalah Primary Key Integer (Auto Increment), 
    // maka dua baris di bawah ini sebaiknya DIHAPUS. 
    // Gunakan ini HANYA jika ID utama tabel pembelian juga berupa string (seperti B001).
    // public $incrementing = false; 
    // protected $keyType = 'string';

    public static function getKodeFaktur()
    {
        // Query untuk mengambil no_faktur terakhir
        $sql = "SELECT IFNULL(MAX(no_faktur), 'PB-0000000') as no_faktur 
                FROM pembelianBahanBaku ";
        $kodefaktur = DB::select($sql);

        $kd = $kodefaktur[0]->no_faktur; // Mengambil hasil pertama langsung tanpa foreach

        // Mengambil 7 digit angka terakhir dari string PB-0000001
        $noawal = substr($kd, -7);
        $noakhir = (int)$noawal + 1; // Konversi ke integer lalu tambah 1
        $noakhir = 'PB-' . str_pad($noakhir, 7, "0", STR_PAD_LEFT); 
        
        return $noakhir;
    }

    // Relasi ke tabel bahanBaku (Master Data)
    public function bahanBaku()
    {
        // Karena bahanBaku menggunakan ID string (B001), 
        // kita harus pastikan foreign key-nya mengarah ke kolom 'id'[cite: 1, 3]
        return $this->belongsTo(bahanBaku::class, 'bahanBaku_id', 'id');
    }

    // Relasi ke Karyawan (Jika ada tabel Karyawan)
    public function Karyawan()
    {
        // PERBAIKAN: Jika ini relasi ke Karyawan, modelnya harus Karyawan::class, bukan bahanBaku::class
        // return $this->belongsTo(Karyawan::class, 'Karyawan_id'); 
    }
}