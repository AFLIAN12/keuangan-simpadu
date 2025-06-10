<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('keu_keringanan', function (Blueprint $table) {
            $table->id('id_keringanan'); // INT AUTO_INCREMENT PRIMARY KEY
            $table->char('nim', 16); // NIM format tetap
            $table->char('id_thn_ak', 5);
            $table->string('jenis_keringanan', 50);
            $table->unsignedInteger('jumlah_potongan');
            $table->text('deskripsi_keringanan')->nullable();
            $table->enum('status_keringanan', ['Disetujui', 'Ditolak'])->default('Disetujui');
            $table->dateTime('tgl_konfirmasi')->nullable();
            $table->unsignedBigInteger('id_tagihan')->nullable(); // opsional relasi ke tagihan

            $table->dateTime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

            // Optional FK: jika kamu ingin enforce referensi tagihan
            $table->foreign('id_tagihan')->references('id_tagihan')->on('keu_tagihan')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keu_keringanan');
    }
};
