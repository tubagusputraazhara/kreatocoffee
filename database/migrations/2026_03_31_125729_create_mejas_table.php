<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meja', function (Blueprint $table) {
            $table->id();
            $table->string('nama_meja')->unique();
            $table->string('id_meja')->unique();
            $table->string('qr_code_path')->nullable();
            $table->string('foto_meja')->nullable();
            $table->enum('status', ['tersedia', 'ditempati', 'reservasi', 'maintenance'])->default('tersedia');
            $table->integer('kapasitas')->default(4);
            $table->enum('lokasi', ['indoor', 'outdoor', 'vip', 'family'])->default('indoor');
            $table->text('deskripsi')->nullable();
            $table->decimal('harga_minimum', 10, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->date('tanggal_perawatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meja');
    }
};