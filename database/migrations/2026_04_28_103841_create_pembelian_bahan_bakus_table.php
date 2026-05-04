<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pembelian_bahan_bakus', function (Blueprint $table) {
            $table->id();
            $table->string('no_faktur')->unique();
            $table->dateTime('tgl');
            // Harus string untuk menampung format B001
            $table->string('bahanBaku_id'); 
            $table->integer('jumlah');
            $table->decimal('harga_satuan', 12, 2);
            $table->decimal('total_harga', 12, 2);
            $table->timestamps();

            // Relasi manual ke tabel bahanBaku
            $table->foreign('bahanBaku_id')->references('id')->on('bahanBaku')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembelian_bahan_bakus');
    }
};
//