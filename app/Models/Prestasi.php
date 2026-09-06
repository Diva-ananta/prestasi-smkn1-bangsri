<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Prestasi extends Model
{
    protected $table = 'prestasi';

    protected $fillable = [
        'nama_lomba',
        'kategori',
        'tingkat',
        'penyelenggara',
        'tanggal_mulai',
        'tanggal_selesai',
        'lokasi',
        'bidang_lomba',
        'jenis_peserta',
        'nama_tim',
        'hasil',
        'kategori_juara',
        'sertifikat',
        'foto',
        'status',
        'keterangan',
    ];

    protected static function booted(): void
    {
        static::creating(function (Prestasi $prestasi) {
            $prestasi->public_token ??= (string) Str::uuid();
        });
    }

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    // Relasi ke DetailPrestasi
    public function detailPrestasi()
    {
        return $this->hasMany(DetailPrestasi::class);
    }

    // Relasi ke Siswa melalui DetailPrestasi
    public function siswa()
    {
        return $this->hasManyThrough(Siswa::class, DetailPrestasi::class, 'prestasi_id', 'id', 'id', 'siswa_id');
    }

    public function artikel()
    {
        return $this->hasMany(Artikel::class);
    }

    // Scope untuk prestasi yang dipublish
    public function scopePublish($query)
    {
        return $query->where('status', 'Publish');
    }

    // Accessor untuk thumbnail (ambil foto atau default)
    public function getThumbnailAttribute()
    {
        return $this->foto ? asset('storage/' . $this->foto) : asset('images/prestasi-default.jpg');
    }

    // Accessor untuk judul pendek
    public function getJudulPendekAttribute()
    {
        return $this->nama_lomba . ' - ' . $this->hasil;
    }
}