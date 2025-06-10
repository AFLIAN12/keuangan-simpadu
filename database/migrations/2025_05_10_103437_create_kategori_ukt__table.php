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
       Schema::create('tabel_kategori_ukt', function (Blueprint $table) {
        $table->id('id_kategori_ukt');
        $table->unsignedTinyInteger('id_prodi');
        $table->string('kategori_ukt', 100);
        $table->unsignedInteger('nominal');
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kategori_ukt_');
    }
};
