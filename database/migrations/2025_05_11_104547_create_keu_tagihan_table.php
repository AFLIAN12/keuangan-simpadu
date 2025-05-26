<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
{
    Schema::create('keu_tagihan', function (Blueprint $table) {
        $table->id('id_tagihan');
        $table->string('nim', 16);
        $table->string('nama_tagihan', 100);
        $table->char('id_thn_ak', 5);
        $table->unsignedBigInteger('id_kategori_ukt');
        $table->tinyInteger('status_tagihan')->default(0);
        $table->date('tgl_terbit');
        $table->timestamps();

        $table->foreign('id_kategori_ukt')->references('id_kategori_ukt')->on('tabel_kategori_ukt');
    });
}

    public function down(): void
    {
        Schema::dropIfExists('keu_tagihan');
    }
};
