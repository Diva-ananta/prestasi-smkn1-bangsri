<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Siswa extends Model
{
    protected $table = 'siswa';

    protected $fillable = [
        'nis',
        'nisn',
        'nama',
        'foto',
        'jenis_kelamin',
        'kelas',
        'jurusan',
        'angkatan',
        'status',
        'tahun_lulus',
        'is_published',
    ];

    protected $casts = [
        'angkatan' => 'integer',
        'is_published' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (Siswa $siswa) {
            $siswa->public_token ??= (string) Str::uuid();
        });
    }

   public function scopeAktif($query)
{
    return $query->where('status', '!=', 'Alumni');
}

    // Accessor untuk status (Aktif/Alumni)
    public function getStatusAttribute($value)
    {
        if (!empty($value)) {
            return $value;
        }

        $tahunAngkatan = $this->angkatan;
        $tahunSekarang = (int) date('Y');
        $tahunLulus = $tahunAngkatan ? ($tahunAngkatan + 3) : $tahunSekarang;

        if ($tahunSekarang >= $tahunLulus) {
            return 'Alumni';
        }
        return 'Aktif';
    }

     // Method untuk cek apakah siswa aktif
    public function isAktif()
    {
        return $this->status === 'Aktif';
    }

    // Method untuk cek apakah alumni
    public function isAlumni()
    {
        return $this->status === 'Alumni';
    }

    public function detailPrestasi()
    {
        return $this->hasMany(DetailPrestasi::class);
    }


}