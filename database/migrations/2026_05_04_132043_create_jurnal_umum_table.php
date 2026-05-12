<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jurnal_umum', function (Blueprint $table) {
            $table->id('id_jurnal');
            $table->string('nomor_jurnal')->unique();        // contoh: JU-20260511171442
            $table->date('tanggal_jurnal');                  // Tanggal transaksi
            $table->text('keterangan')->nullable();          // Keterangan jurnal
            $table->string('ref')->nullable();               // Referensi transaksi, contoh: ORD-xxx
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jurnal_umum');
    }
};