<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->enum('status_pembayaran', ['pending', 'paid'])->default('pending')->after('catatan');
            $table->unsignedBigInteger('jumlah_dp')->nullable()->after('status_pembayaran');
            $table->timestamp('paid_at')->nullable()->after('jumlah_dp');
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['status_pembayaran', 'jumlah_dp', 'paid_at']);
        });
    }
};