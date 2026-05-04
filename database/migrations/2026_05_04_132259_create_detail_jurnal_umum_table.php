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
        Schema::create('detail_jurnal_umum', function (Blueprint $table) {
            $table->id('id_detail_jurnal');                                         // PK Auto Increment

            $table->unsignedBigInteger('id_jurnal');                                // FK dari jurnal_umum
            $table->unsignedBigInteger('id_coa');                                   // FK dari COA

            $table->decimal('debit', 15, 2)->default(0);                            // Nominal debit
            $table->decimal('kredit', 15, 2)->default(0);                           // Nominal kredit

            $table->timestamps();

            // Relasi ke tabel jurnal_umum
            $table->foreign('id_jurnal')
                ->references('id_jurnal')
                ->on('jurnal_umum')
                ->onDelete('cascade');

            // Relasi ke tabel coa
            $table->foreign('id_coa')
                ->references('id_coa')
                ->on('coa')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_jurnal_umum');
    }
};