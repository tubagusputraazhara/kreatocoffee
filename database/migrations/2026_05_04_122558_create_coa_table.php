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
        Schema::create('coa', function (Blueprint $table) {
            $table->id('id_coa');                                                                   // PK Auto Increment
            $table->string('kode_akun')->unique();                                                  // Kode akun, contoh: 1-001
            $table->string('nama_akun');                                                            // Nama akun, contoh: Kas
            $table->enum('kategori_akun', ['Aset', 'Kewajiban', 'Modal', 'Pendapatan', 'Beban']); // Kategori akun
            $table->enum('jenis_akun', ['Debit', 'Kredit']);                                       // Jenis akun
            $table->decimal('saldo_normal', 15, 2)->default(0);                                    // Saldo awal akun
            $table->text('deskripsi')->nullable();                                                  // Keterangan akun (opsional)
            $table->date('tanggal_dibuat');                                                         // Tanggal akun didaftarkan
            $table->enum('status_akun', ['Aktif', 'Tidak Aktif'])->default('Aktif');              // Status akun
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coa');
    }
};