<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('meja', function (Blueprint $table) {
            $table->dropColumn([
                'foto_meja',
                'status',
                'harga_minimum',
                'tanggal_perawatan',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('meja', function (Blueprint $table) {
            $table->string('foto_meja')->nullable();
            $table->enum('status', ['tersedia', 'ditempati', 'reservasi', 'maintenance'])->default('tersedia');
            $table->decimal('harga_minimum', 10, 2)->default(0);
            $table->date('tanggal_perawatan')->nullable();
        });
    }
};