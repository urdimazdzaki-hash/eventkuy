<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->string('nama_paket')->nullable()->after('harga_per_orang');
            $table->text('fasilitas_paket')->nullable()->after('nama_paket');
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['nama_paket', 'fasilitas_paket']);
        });
    }
};