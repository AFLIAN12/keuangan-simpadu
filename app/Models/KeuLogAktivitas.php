<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KeuLogAktivitas extends Model
{
    protected $table = 'keu_log_aktivitas';
protected $primaryKey = 'id_log';
public $timestamps = false;

protected $fillable = [
    'id_user',
    'aktivitas',
    'entitas',
    'entitas_id',
    'tgl_log',
];

}

