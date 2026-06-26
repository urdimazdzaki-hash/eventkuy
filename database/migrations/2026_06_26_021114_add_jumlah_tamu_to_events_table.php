<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->unsignedInteger('jumlah_tamu')->nullable()->after('tipe_lokasi');
            $table->unsignedBigInteger('harga_per_orang')->nullable()->after('jumlah_tamu');
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['jumlah_tamu', 'harga_per_orang']);
        });
    }
};