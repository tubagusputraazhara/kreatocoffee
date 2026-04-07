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
    Schema::create('pelanggan', function (Blueprint $table) {
   $table->string('id')->primary(); // Id Pelanggan
    $table->string('nama_pelanggan'); 
    $table->string('no_hp'); 
    $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelanggan');
    }
};