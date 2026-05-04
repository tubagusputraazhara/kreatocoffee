<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class Meja extends Model
{
    use HasFactory;

    protected $table = 'meja';

    protected $guarded = [];

    public static function getIdMeja()
    {
        $sql = "SELECT IFNULL(MAX(id_meja), 'MJ-000') as id_meja FROM meja";
        $result = DB::select($sql);

        foreach ($result as $row) {
            $last = $row->id_meja;
        }

        $number = substr($last, -3);
        $number = (int) $number + 1;

        return 'MJ-' . str_pad($number, 3, "0", STR_PAD_LEFT);
    }

    protected static function booted()
    {
        static::creating(function ($meja) {
            // Pakai logic id_meja yang sudah ada
            $meja->id_meja = self::getIdMeja();

            // URL mengarah ke 1 halaman order
            $url = url('/order?meja=' . $meja->id_meja);

            // Buat folder kalau belum ada
            if (!file_exists(storage_path('app/public/qrcodes'))) {
                mkdir(storage_path('app/public/qrcodes'), 0755, true);
            }

            // Generate QR pakai endroid/qr-code (support GD)
            $filename = 'qrcodes/meja-' . $meja->id_meja . '.png';

            $qrCode = new QrCode($url);
            $qrCode->setSize(400);
            $qrCode->setMargin(10);

            $writer = new PngWriter();
            $result = $writer->write($qrCode);

            $result->saveToFile(storage_path('app/public/' . $filename));

            $meja->qr_code_path = $filename;
        });
    }
}