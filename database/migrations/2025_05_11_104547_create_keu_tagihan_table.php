<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('keu_tagihan', function (Blueprint $table) {
            $table->id('id_tagihan');
            $table->char('nim', 16);
            $table->string('nama_tagihan', 100);
            $table->char('id_thn_ak', 5);
            $table->decimal('nominal', 12, 2);
            $table->tinyInteger('status_tagihan')->default(0); // 0 = Belum Lunas, 1 = Lunas
            $table->string('kategori_ukt', 100)->nullable();
            $table->date('tgl_terbit');
            $table->date('tgl_registrasi')->nullable();
            $table->unsignedBigInteger('id_user'); // Mengganti id_admin
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keu_tagihan');
    }
};
