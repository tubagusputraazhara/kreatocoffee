<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('market_trends', function (Blueprint $table) {
            $table->id();
            $table->string('nama_tren');              // Contoh: "Tren Menu Kopi Susu 2026"
            $table->text('analisis_ai');               // Teks penjelasan dari Gemini
            $table->json('referensi_visual')->nullable(); // Daftar URL gambar referensi (opsional)
            $table->string('menu_populer')->nullable();   // Contoh: "Kopi Susu Gula Aren, Matcha Latte"
            $table->string('kategori_terlaris')->nullable(); // Contoh: "Non Coffee, Signature"
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('market_trends');
    }
};
