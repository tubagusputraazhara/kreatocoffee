<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pemesanan', function (Blueprint $table) {
            $table->id();
            $table->string('id_pemesanan')->unique();

            // FOREIGN KEY
            $table->string('id_pelanggan'); // karena pelanggan.id = string
            $table->unsignedBigInteger('id_meja'); // karena meja.id = bigint

            $table->string('nama_pesanan');
            $table->string('nama_pelanggan');
            $table->decimal('harga_satuan', 12, 2);
            $table->integer('jumlah');
            $table->decimal('total_harga', 12, 2);

            $table->text('catatan')->nullable();
            $table->timestamps();

            // RELASI
            $table->foreign('id_pelanggan')
                  ->references('id')
                  ->on('pelanggan')
                  ->cascadeOnDelete();

            $table->foreign('id_meja')
                  ->references('id')
                  ->on('meja')
                  ->cascadeOnDelete();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('pemesanan');
    }
};
//s