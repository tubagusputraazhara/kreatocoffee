<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BiayaOperasional extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'biaya_operasionals';

    // Primary key custom sesuai keinginanmu
    protected $primaryKey = 'id_biaya';

    // Kolom yang boleh diisi (mass assignment)
    protected $fillable = [
        'tgl_biaya',
        'nama_biaya',
        'jumlah_biaya',
        'bukti_bayar',
        'keterangan',
        'id_karyawan',
    ];

    /**
     * Relasi ke Tabel Karyawan
     * Mencatat siapa yang bertanggung jawab atas pengeluaran ini.
     */
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'id_karyawan');
    }
}