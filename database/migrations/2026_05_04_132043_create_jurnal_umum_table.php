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
        Schema::create('jurnal_umum', function (Blueprint $table) {
            $table->id('id_jurnal');                                  // PK Auto Increment
            $table->string('nomor_jurnal')->unique();                // Nomor jurnal, contoh: JU-001
            $table->date('tanggal_jurnal');                          // Tanggal transaksi jurnal
            $table->text('keterangan')->nullable();                  // Keterangan jurnal (opsional)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jurnal_umum');
    }
};