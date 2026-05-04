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
        Schema::create('penggajians', function (Blueprint $table) {
            $table->string('id_penggajian')->primary();         // ID Penggajian
            $table->string('id_karyawan');                      // Foreign key ke karyawans
            $table->integer('bulan');                           // Bulan (1-12)
            $table->integer('tahun');                           // Tahun (misal 2024)
            $table->decimal('gaji_pokok', 15, 2)->default(0);   // Gaji pokok (dari karyawan)
            $table->decimal('tunjangan', 15, 2)->default(0);    // Tunjangan tambahan
            $table->decimal('potongan', 15, 2)->default(0);     // Potongan (absensi, dll)
            $table->decimal('gaji_bersih', 15, 2)->default(0);  // Gaji bersih = pokok + tunjangan - potongan
            $table->enum('status', ['Pending', 'Dibayar', 'Ditolak'])->default('Pending'); // Status pembayaran
            $table->date('tanggal_bayar')->nullable();          // Tanggal pembayaran
            $table->text('keterangan')->nullable();             // Keterangan tambahan
            $table->timestamps();

            $table->foreign('id_karyawan')
                  ->references('id_karyawan')
                  ->on('karyawans')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penggajians');
    }
};