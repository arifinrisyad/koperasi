<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('laporan_pembelians', function (Blueprint $table) {
            $table->string('bulan', 7)->change(); // Atur panjang maksimum untuk 'Y-m'
        });
    }

    public function down(): void
    {
        Schema::table('laporan_pembelians', function (Blueprint $table) {
            $table->string('bulan')->change(); // Kembalikan ke tipe sebelumnya
        });
    }
};
