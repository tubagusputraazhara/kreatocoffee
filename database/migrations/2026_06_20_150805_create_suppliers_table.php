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
    Schema::create('suppliers', function (Blueprint $table) {

        $table->id(); // PK supplier

        $table->string('bahan_baku_id');

        $table->string('nama_supplier');

        $table->string('no_telephone',20);

        $table->integer('jumlah');

        $table->decimal('harga_satuan',15,2);

        $table->timestamps();

        $table->foreign('bahan_baku_id')
            ->references('id')
            ->on('bahanBaku')
            ->cascadeOnDelete();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
