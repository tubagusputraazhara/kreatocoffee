<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('biaya_operasionals', function (Blueprint $table) {
            $table->id('id_biaya');
            //data utama Transaksi
           $table->date('tgl_biaya');
            $table->string('nama_biaya'); // Contoh: Bayar Listrik, Gaji Karyawan
            $table->double('jumlah_biaya');
            $table->string('bukti_bayar')->nullable(); // Untuk upload struk
            $table->text('keterangan')->nullable();
            // Relasi Akuntansi (Wajib ada id_coa_debet dan id_coa_kredit)
            // Menentukan kategori beban (misal: Beban Gaji)

            // Relasi Akuntabilitas ke tabel karyawan milikmu
            // Menggunakan nullable() agar bisa dikosongkan jika biaya bersifat umum
            $table->foreignId('id_karyawan')->nullable()->constrained('karyawans');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */ 
    public function down(): void
    {
        Schema::dropIfExists('biaya_operasionals');
    }
};
