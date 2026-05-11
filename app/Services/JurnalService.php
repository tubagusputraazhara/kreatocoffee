<?php

namespace App\Services;

use App\Models\JurnalUmum;
use App\Models\DetailJurnalUmum;

class JurnalService
{
    /*
    |--------------------------------------------------------------------------
    | GENERATE NOMOR JURNAL
    |--------------------------------------------------------------------------
    */

    public static function generateNomorJurnal()
    {
        return 'JU-' . now()->format('YmdHis');
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE JURNAL UMUM
    |--------------------------------------------------------------------------
    */

    public static function createJurnal($tanggal, $keterangan, $details = [])
    {
        $jurnal = JurnalUmum::create([
            'nomor_jurnal'   => self::generateNomorJurnal(),
            'tanggal_jurnal' => $tanggal,
            'keterangan'     => $keterangan,
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

    /*
    |--------------------------------------------------------------------------
    | JURNAL PENJUALAN / PEMESANAN
    |--------------------------------------------------------------------------
    */

    public static function jurnalPenjualan($pemesanan)
    {
        self::createJurnal(

            now(),

            'Penjualan #' . $pemesanan->kode_pemesanan,

            [

                // DEBIT KAS
                [
                    'id_coa' => 1,
                    'debit'  => $pemesanan->total_harga,
                    'kredit' => 0,
                ],

                // KREDIT PENDAPATAN PENJUALAN
                [
                    'id_coa' => 13,
                    'debit'  => 0,
                    'kredit' => $pemesanan->total_harga,
                ],

            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | JURNAL PEMBELIAN BAHAN BAKU
    |--------------------------------------------------------------------------
    */

    public static function jurnalPembelian($pembelian)
    {
        self::createJurnal(

            now(),

            'Pembelian Bahan Baku #' . $pembelian->kode_pembelian,

            [

                // DEBIT PERSEDIAAN BAHAN BAKU
                [
                    'id_coa' => 11,
                    'debit'  => $pembelian->total_harga,
                    'kredit' => 0,
                ],

                // KREDIT KAS
                [
                    'id_coa' => 1,
                    'debit'  => 0,
                    'kredit' => $pembelian->total_harga,
                ],

            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | JURNAL PENGGAJIAN
    |--------------------------------------------------------------------------
    */

    public static function jurnalPenggajian($penggajian)
    {
        self::createJurnal(

            now(),

            'Penggajian #' . $penggajian->kode_penggajian,

            [

                // DEBIT BEBAN GAJI
                [
                    'id_coa' => 14,
                    'debit'  => $penggajian->total_gaji,
                    'kredit' => 0,
                ],

                // KREDIT KAS
                [
                    'id_coa' => 1,
                    'debit'  => 0,
                    'kredit' => $penggajian->total_gaji,
                ],

            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | JURNAL BIAYA OPERASIONAL
    |--------------------------------------------------------------------------
    */

    public static function jurnalOperasional($operasional)
    {
        self::createJurnal(

            now(),

            'Biaya Operasional #' . $operasional->kode_operasional,

            [

                // DEBIT BEBAN OPERASIONAL
                [
                    'id_coa' => 15,
                    'debit'  => $operasional->total_biaya,
                    'kredit' => 0,
                ],

                // KREDIT KAS
                [
                    'id_coa' => 1,
                    'debit'  => 0,
                    'kredit' => $operasional->total_biaya,
                ],

            ]
        );
    }
}