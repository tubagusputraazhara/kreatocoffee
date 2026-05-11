<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu', function (Blueprint $table) {

            $table->string('id_menu')->primary();

            $table->string('nama_menu');

            $table->decimal('harga', 12, 2);

            $table->enum('kategori', [
                'Makanan',
                'Minuman'
            ]);

            $table->string('gambar')->nullable();

            $table->text('deskripsi')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu');
    }
};