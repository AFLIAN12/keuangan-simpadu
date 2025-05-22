<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KeuTagihan extends Model
{
    protected $table = 'keu_tagihan';
    protected $primaryKey = 'id_tagihan';

    protected $fillable = [
    'nim',
    'nama_tagihan',
    'id_thn_ak',
    'nominal',
    'status_tagihan',
    'kategori_ukt',
    'tgl_terbit',
    'tgl_registrasi',
    'id_user'
];

}

