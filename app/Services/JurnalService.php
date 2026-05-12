<?php

namespace App\Services;

use App\Models\JurnalUmum;
use App\Models\DetailJurnalUmum;
use App\Models\Coa;

class JurnalService
{
    public static function generateNomorJurnal()
    {
        return 'JU-' . now()->format('YmdHis');
    }

    private static function coa($kode_akun)
    {
        $coa = Coa::where('kode_akun', $kode_akun)->first();

        if (!$coa) {
            throw new \Exception("COA dengan kode '{$kode_akun}' tidak ditemukan. Pastikan seeder COA sudah dijalankan.");
        }

        return $coa->id_coa;
    }

    public static function createJurnal($tanggal, $keterangan, $details = [], $ref = null)
    {
        $jurnal = JurnalUmum::create([
            'nomor_jurnal'   => self::generateNomorJurnal(),
            'tanggal_jurnal' => $tanggal,
            'keterangan'     => $keterangan,
            'ref'            => $ref,
        ]);

        foreach ($details as $detail) {
            DetailJurnalUmum::create([
                'id_jurnal' => $jurnal->id_jurnal,
                'id_coa'    => $detail['id_coa'],
                'debit'     => $detail['debit'],
                'kredit'    => $detail['kredit'],
            ]);
        }

        return $jurnal;
    }

    public static function jurnalPenjualan($pemesanan)
    {
        self::createJurnal(
            now(),
            'Penjualan #' . $pemesanan->kode_pemesanan,
            [
                ['id_coa' => self::coa('1-001'), 'debit' => $pemesanan->total_harga, 'kredit' => 0],
                ['id_coa' => self::coa('4-001'), 'debit' => 0, 'kredit' => $pemesanan->total_harga],
            ],
            $pemesanan->kode_pemesanan
        );
    }

    public static function jurnalPembelian($pembelian)
    {
        self::createJurnal(
            now(),
            'Pembelian Bahan Baku #' . $pembelian->no_faktur, // ← fix
            [
                ['id_coa' => self::coa('1-004'), 'debit' => $pembelian->total_harga, 'kredit' => 0],
                ['id_coa' => self::coa('1-001'), 'debit' => 0, 'kredit' => $pembelian->total_harga],
            ],
            $pembelian->no_faktur // ← fix
        );
    }

    public static function jurnalPenggajian($penggajian)
    {
        self::createJurnal(
            now(),
            'Penggajian ' . $penggajian->id_penggajian, // ← fix
            [
                ['id_coa' => self::coa('5-001'), 'debit' => $penggajian->gaji_bersih, 'kredit' => 0], // ← fix
                ['id_coa' => self::coa('1-001'), 'debit' => 0, 'kredit' => $penggajian->gaji_bersih], // ← fix
            ],
            $penggajian->id_penggajian // ← fix
        );
    }

    public static function jurnalOperasional($operasional)
    {
        self::createJurnal(
            now(),
            'Biaya Operasional - ' . $operasional->nama_biaya, // ← fix
            [
                ['id_coa' => self::coa('5-002'), 'debit' => $operasional->jumlah_biaya, 'kredit' => 0], // ← fix
                ['id_coa' => self::coa('1-001'), 'debit' => 0, 'kredit' => $operasional->jumlah_biaya], // ← fix
            ],
            $operasional->nama_biaya // ← fix
        );
    }
}