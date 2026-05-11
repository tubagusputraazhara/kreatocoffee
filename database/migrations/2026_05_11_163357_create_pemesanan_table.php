<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pemesanan', function (Blueprint $table) {
            $table->id('id_pemesanan');
            $table->string('kode_pemesanan')->unique();  // contoh: ORD-20250504120000
            $table->string('nama_pemesan');              // dari session
            $table->string('no_meja');                  // cukup string, tidak FK
            $table->string('no_wa')->nullable();         // opsional
            $table->string('email')->nullable();         // opsional
            $table->string('sumber')->default('customer'); // 'customer' atau 'kasir'
            $table->decimal('total_harga', 12, 2)->default(0);
            $table->enum('status', ['pending', 'selesai', 'batal'])->default('pending');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pemesanan');
    }
};