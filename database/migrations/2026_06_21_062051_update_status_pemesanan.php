<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ✅ Ubah enum dulu agar bisa menerima nilai baru
        DB::statement("ALTER TABLE pemesanan MODIFY COLUMN status ENUM('belum_lunas', 'lunas', 'batal', 'pending', 'selesai') DEFAULT 'belum_lunas'");

        // ✅ Baru update data lama
        DB::table('pemesanan')->where('status', 'pending')->update(['status' => 'belum_lunas']);
        DB::table('pemesanan')->where('status', 'selesai')->update(['status' => 'lunas']);

        // ✅ Hapus nilai lama dari enum
        DB::statement("ALTER TABLE pemesanan MODIFY COLUMN status ENUM('belum_lunas', 'lunas', 'batal') DEFAULT 'belum_lunas'");

        // ✅ Tambah kolom status_pesanan
        Schema::table('pemesanan', function (Blueprint $table) {
            $table->enum('status_pesanan', ['diproses', 'diantarkan', 'selesai'])
                  ->default('diproses')
                  ->after('status');
        });
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE pemesanan MODIFY COLUMN status ENUM('belum_lunas', 'lunas', 'batal', 'pending', 'selesai') DEFAULT 'belum_lunas'");

        DB::table('pemesanan')->where('status', 'belum_lunas')->update(['status' => 'pending']);
        DB::table('pemesanan')->where('status', 'lunas')->update(['status' => 'selesai']);

        DB::statement("ALTER TABLE pemesanan MODIFY COLUMN status ENUM('pending', 'selesai', 'batal') DEFAULT 'pending'");

        Schema::table('pemesanan', function (Blueprint $table) {
            $table->dropColumn('status_pesanan');
        });
    }
};