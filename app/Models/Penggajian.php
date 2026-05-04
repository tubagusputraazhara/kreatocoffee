<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penggajian extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_penggajian'; // sesuai kolom di migrasi
    public $incrementing = false;             // bukan auto increment
    protected $keyType = 'string';            // tipe string (GJI001)

    protected $fillable = [
        'id_penggajian',
        'id_karyawan',
        'bulan',
        'tahun',
        'gaji_pokok',
        'tunjangan',
        'potongan',
        'gaji_bersih',
        'status',
        'tanggal_bayar',
        'keterangan',
    ];

    /**
     * Relasi ke model Karyawan
     */
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'id_karyawan', 'id_karyawan');
    }

    /**
     * Hitung otomatis gaji_bersih sebelum disimpan
     */
    protected static function booted(): void
    {
        static::saving(function (Penggajian $penggajian) {
            $penggajian->gaji_bersih =
                ($penggajian->gaji_pokok + $penggajian->tunjangan) - $penggajian->potongan;
        });
    }

    /**
     * Nama bulan dalam Bahasa Indonesia
     */
    public static function namaBulan(): array
    {
        return [
            1  => 'Januari',
            2  => 'Februari',
            3  => 'Maret',
            4  => 'April',
            5  => 'Mei',
            6  => 'Juni',
            7  => 'Juli',
            8  => 'Agustus',
            9  => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];
    }
}