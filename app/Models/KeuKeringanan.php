<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KeuKeringanan extends Model
{
    protected $table = 'keu_keringanan';
    protected $primaryKey = 'id_keringanan';

    protected $fillable = [
    'nim',
    'tahun_ajaran',
    'jenis_keringanan',
    'jumlah_potongan',
    'deskripsi_keringanan',
    'status_keringanan',
    'tgl_konfirmasi',
    'catatan_admin',
    'id_user',
    'id_tagihan',
];

}

