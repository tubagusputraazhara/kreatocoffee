ADD STATUS PEMESANAN

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pemesanan', function (Blueprint $table) {
            // Status pemesanan: tahap proses dapur/penyajian, terpisah dari kolom 'status'
            // (kolom 'status' tetap dipakai apa adanya untuk status pembayaran: pending/selesai/batal)
            $table->enum('status_pemesanan', ['diproses', 'diantarkan', 'selesai'])
                ->default('diproses')
                ->after('status');

            // Jenis pemesanan: dine_in (pilih meja) atau take_away (tanpa meja)
            $table->enum('jenis_pemesanan', ['dine_in', 'take_away'])
                ->default('dine_in')
                ->after('nama_pemesan');
        });

        // no_meja wajib diisi untuk dine_in, tapi kosong untuk take_away.
        // Pakai raw SQL agar tidak butuh package doctrine/dbal untuk MODIFY COLUMN.
        DB::statement('ALTER TABLE pemesanan MODIFY no_meja VARCHAR(255) NULL');
    }

    public function down(): void
    {
        Schema::table('pemesanan', function (Blueprint $table) {
            $table->dropColumn(['status_pemesanan', 'jenis_pemesanan']);
        });

        DB::statement("ALTER TABLE pemesanan MODIFY no_meja VARCHAR(255) NOT NULL DEFAULT ''");
    }
};
