<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('keu_pembayaran', function (Blueprint $table) {
            $table->id('id_pembayaran');
            $table->unsignedBigInteger('id_tagihan');
            $table->dateTime('tgl_bayar');
            $table->decimal('jumlah_bayar', 12, 2);
            $table->string('metode', 50);
            $table->enum('status_verifikasi', ['Terverifikasi', 'Gagal'])->default('Terverifikasi');
            $table->unsignedBigInteger('id_user'); // Pengganti id_admin
            $table->timestamps();

            // Foreign key ke tagihan (internal sistem keuangan)
            $table->foreign('id_tagihan')->references('id_tagihan')->on('keu_tagihan')->onDelete('cascade');

            // Tidak perlu foreign key untuk id_user (karena antar service)
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keu_pembayaran');
    }
};
