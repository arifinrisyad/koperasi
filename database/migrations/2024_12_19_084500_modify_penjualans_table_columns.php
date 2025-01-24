<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('penjualans', function (Blueprint $table) {
            $table->decimal('total_harga', 15, 2)->change();
            $table->decimal('keuntungan', 15, 2)->change();
        });
    }

    public function down()
    {
        Schema::table('penjualans', function (Blueprint $table) {
            $table->decimal('total_harga', 10, 2)->change();
            $table->decimal('keuntungan', 15, 2)->change();
        });
    }
};
