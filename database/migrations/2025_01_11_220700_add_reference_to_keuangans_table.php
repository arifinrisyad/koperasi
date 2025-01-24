<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('keuangans', function (Blueprint $table) {
            $table->unsignedBigInteger('referensi_id')->nullable();
            $table->string('referensi_type')->nullable();
            $table->index(['referensi_id', 'referensi_type']);
        });
    }

    public function down()
    {
        Schema::table('keuangans', function (Blueprint $table) {
            $table->dropIndex(['referensi_id', 'referensi_type']);
            $table->dropColumn(['referensi_id', 'referensi_type']);
        });
    }
};
