<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pemesanan', function (Blueprint $table) {
            $table->string('order_id')->nullable()->after('catatan');
            $table->string('snap_token')->nullable()->after('order_id');
            $table->string('payment_type')->nullable()->after('snap_token');
            $table->string('transaction_status')->nullable()->after('payment_type');
        });
    }

    public function down(): void
    {
        Schema::table('pemesanan', function (Blueprint $table) {
            $table->dropColumn(['order_id', 'snap_token', 'payment_type', 'transaction_status']);
        });
    }
};