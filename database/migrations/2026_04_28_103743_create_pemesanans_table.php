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
        Schema::create('pemesanan', function (Blueprint $table) {
            $table->id();
            $table->string('id_pemesanan')->primary();
            $table->string('id_pelanggan')->primary();
            $table->string('no_meja')->primary();
            $table->string('nama_pesanan');
            $table->string('nama_pelanggan');
            $table->decimal('subtotal', 12, 2);
            $table->decimal('total_harga', 12, 2);
           // $table->enum('kategori', ['Makanan', 'Minuman']);
           // $table->string('gambar'); 
            $table->text('catatan'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemesanan');
    }
};
