<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('keu_keringanan', function (Blueprint $table) {
            $table->integer('id_keringanan', true); // INT AUTO_INCREMENT PRIMARY KEY
            $table->char('nim', 16); // nim VARCHAR(20)
            $table->string('tahun_ajaran', 10); // tahun_ajaran VARCHAR(10)
            $table->string('jenis_keringanan', 50); // jenis_keringanan VARCHAR(50)
            $table->decimal('jumlah_potongan', 12, 2); // DECIMAL(12,2)
            $table->text('deskripsi_keringanan')->nullable();
            $table->enum('status_keringanan', ['Disetujui', 'Ditolak'])->default('Disetujui');
            $table->dateTime('tgl_konfirmasi')->nullable();
            $table->text('catatan_admin')->nullable();
            $table->integer('id_user'); // INT, foreign key ke users.id_user (non-enforced)
            $table->dateTime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keu_keringanan');
    }
};
