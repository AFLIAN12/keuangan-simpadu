<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriUKT extends Model
{
    protected $table = 'tabel_kategori_ukt';
    protected $primaryKey = 'id_kategori_ukt';
    public $timestamps = true;

    protected $fillable = [
        'kategori',
        'nominal',
        'level',
    ];
}