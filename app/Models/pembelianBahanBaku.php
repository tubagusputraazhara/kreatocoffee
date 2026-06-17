<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class pembelianBahanBaku extends Model
{
    use HasFactory;

    protected $table = 'pembelian_bahan_bakus';
    protected $guarded = [];

    public static function getKodeFaktur()
    {
        $sql = "SELECT IFNULL(MAX(no_faktur), 'PB-0000000') as no_faktur 
                FROM pembelian_bahan_bakus";
        $kodefaktur = DB::select($sql);

        $kd      = $kodefaktur[0]->no_faktur;
        $noawal  = substr($kd, -7);
        $noakhir = (int) $noawal + 1;
        $noakhir = 'PB-' . str_pad($noakhir, 7, '0', STR_PAD_LEFT);

        return $noakhir;
    }

    public function bahanBaku()
    {
        return $this->belongsTo(bahanBaku::class, 'bahanBaku_id', 'id');
    }

    // ✅ Hanya tambah boot() ini, semua kode di atas tidak diubah
    protected static function boot()
    {
        parent::boot();

        // Saat pembelian DIBUAT → stok bertambah
        static::created(function ($pembelian) {
            bahanBaku::where('id', $pembelian->bahanBaku_id)
                ->increment('stok', $pembelian->jumlah);
        });

        // Saat pembelian DIEDIT → simpan data lama dulu
        static::updating(function ($pembelian) {
            $pembelian->jumlah_lama = $pembelian->getOriginal('jumlah');
            $pembelian->bahan_lama  = $pembelian->getOriginal('bahanBaku_id');
        });

        static::updated(function ($pembelian) {
            $jumlahLama = $pembelian->jumlah_lama ?? 0;
            $bahanLama  = $pembelian->bahan_lama;

            // Jika bahan baku diganti
            if ($bahanLama !== $pembelian->bahanBaku_id) {
                // Kembalikan stok bahan lama
                bahanBaku::where('id', $bahanLama)
                    ->decrement('stok', $jumlahLama);

                // Tambah stok bahan baru
                bahanBaku::where('id', $pembelian->bahanBaku_id)
                    ->increment('stok', $pembelian->jumlah);

            } else {
                // Bahan sama, hitung selisih
                $selisih = $pembelian->jumlah - $jumlahLama;

                if ($selisih > 0) {
                    bahanBaku::where('id', $pembelian->bahanBaku_id)
                        ->increment('stok', $selisih);
                } elseif ($selisih < 0) {
                    bahanBaku::where('id', $pembelian->bahanBaku_id)
                        ->decrement('stok', abs($selisih));
                }
            }
        });

        // Saat pembelian DIHAPUS → stok dikurangi kembali
        static::deleted(function ($pembelian) {
            bahanBaku::where('id', $pembelian->bahanBaku_id)
                ->decrement('stok', $pembelian->jumlah);
        });
    }
}