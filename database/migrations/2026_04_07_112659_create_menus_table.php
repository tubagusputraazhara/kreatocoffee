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
        Schema::create('menu', function (Blueprint $table) {
            $table->string('id_menu')->primary();
            $table->string('nama_menu');
            $table->decimal('harga', 12, 2);
            $table->enum('kategori', ['Makanan', 'Minuman']);
            $table->string('gambar'); 
            $table->text('deskripsi'); 
            $table->boolean('is_admin')->default(true); // Status Menu (Tersedia = 1, Habis = 0)
            $table->timestamps();
});
       
    }

    /**
     * Reverse the migrations.
     */ //aahahahahah
    public function down(): void
    {
        Schema::dropIfExists('menu');
    }
};