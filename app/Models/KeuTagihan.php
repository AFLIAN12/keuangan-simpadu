<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KeuTagihan extends Model
{
    protected $table = 'keu_tagihan';
    protected $primaryKey = 'id_tagihan';
    public $timestamps = true;

    protected $fillable = [
        'nim',
        'nama_tagihan',
        'id_thn_ak',
        'id_kategori_ukt',
        'status_tagihan',
        'tgl_terbit',
    ];

    // Relasi ke kategori UKT
    public function kategoriUkt()
    {
        return $this->belongsTo(KategoriUKT::class, 'id_kategori_ukt', 'id_kategori_ukt');
    }
    
}