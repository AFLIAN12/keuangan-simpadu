<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KeuKeringanan extends Model
{
    protected $table = 'keu_keringanan';
    protected $primaryKey = 'id_keringanan';

    protected $fillable = [
    'nim',
    'id_thn_ak',
    'jenis_keringanan',
    'jumlah_potongan',
    'deskripsi_keringanan',
    'status_keringanan',
    'tgl_konfirmasi',
    'id_tagihan',
];

}

