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
        Schema::create('bahanBaku', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('kode_bahan')->unique(); // Kode unik bahan baku
            $table->string('nama_bahan'); // Nama bahan baku
            $table->string('kategori'); // Kategori (misal: tepung, cairan, dll)
            $table->string('satuan'); // Satuan (kg, liter, pcs, dll)
            $table->decimal('harga', 12, 2); // Harga per satuan
            $table->integer('stok'); // Jumlah stok tersedia
            $table->text('deskripsi')->nullable(); // Deskripsi bahan
            $table->string('gambar')->nullable(); // Foto bahan baku
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bahanBaku');
    }
};