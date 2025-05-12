<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KeuPembayaran extends Model
{
    protected $table = 'keu_pembayaran';
    protected $primaryKey = 'id_pembayaran';

    protected $fillable = [
    'id_tagihan',
    'tgl_bayar',
    'jumlah_bayar',
    'metode',
    'status_verifikasi',
    'id_user'
];

}
