<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPrestasi extends Model
{
    protected $table = 'detail_prestasi';

    protected $fillable = [
        'prestasi_id',
        'siswa_id',
        'peran'
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function prestasi()
    {
        return $this->belongsTo(Prestasi::class, 'prestasi_id');
    }
}