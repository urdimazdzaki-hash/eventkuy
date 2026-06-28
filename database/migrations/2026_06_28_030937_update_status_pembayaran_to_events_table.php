<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE events MODIFY status_pembayaran ENUM('pending', 'paid', 'belum_bayar', 'dp', 'lunas') DEFAULT 'pending'");

        DB::table('events')->where('status_pembayaran', 'pending')->update(['status_pembayaran' => 'belum_bayar']);
        DB::table('events')->where('status_pembayaran', 'paid')->update(['status_pembayaran' => 'lunas']);

        DB::statement("ALTER TABLE events MODIFY status_pembayaran ENUM('belum_bayar', 'dp', 'lunas') DEFAULT 'belum_bayar'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE events MODIFY status_pembayaran ENUM('pending', 'paid') DEFAULT 'pending'");
    }
};