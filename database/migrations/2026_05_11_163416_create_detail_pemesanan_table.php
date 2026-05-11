<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_pemesanan', function (Blueprint $table) {
            $table->id('id_detail');

            $table->unsignedBigInteger('id_pemesanan');
            $table->string('id_menu');

            $table->string('nama_menu');
            $table->decimal('harga_satuan', 12, 2);
            $table->integer('qty');
            $table->decimal('subtotal', 12, 2);

            $table->timestamps();

            $table->foreign('id_pemesanan')
                ->references('id_pemesanan')
                ->on('pemesanan')
                ->cascadeOnDelete();

            $table->foreign('id_menu')
                ->references('id_menu')
                ->on('menu')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_pemesanan');
    }
};