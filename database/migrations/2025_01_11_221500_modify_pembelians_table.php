<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pembelians', function (Blueprint $table) {
            // Hapus kolom total_harga
            $table->dropColumn('total_harga');
            
            // Tambah kolom baru
            $table->decimal('harga_satuan', 15, 2)->after('jumlah');
            $table->decimal('total', 15, 2)->after('harga_satuan');
        });
    }

    public function down()
    {
        Schema::table('pembelians', function (Blueprint $table) {
            // Kembalikan ke struktur awal
            $table->decimal('total_harga', 15, 2);
            $table->dropColumn(['harga_satuan', 'total']);
        });
    }
};
