<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('keu_log_aktivitas', function (Blueprint $table) {
    $table->id('id_log');
    $table->unsignedBigInteger('id_pegawai');
    $table->string('aktivitas', 100);
    $table->string('entitas', 50);
    $table->unsignedBigInteger('entitas_id');
    $table->dateTime('tgl_log')->default(DB::raw('CURRENT_TIMESTAMP'));
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keu_log_aktivitas');
    }
};
