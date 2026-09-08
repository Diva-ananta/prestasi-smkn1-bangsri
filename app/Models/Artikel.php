<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Artikel extends Model
{
    protected $table = 'artikel';

    protected $fillable = [
        'judul',
        'slug',
        'isi',
        'gambar',
        'penulis',
        'tanggal_publikasi',
        'status',
        'prestasi_id',
        'video_url',
    ];

    protected $casts = [
        'tanggal_publikasi' => 'date',
    ];

    // Auto-generate slug dari judul jika kosong
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($artikel) {
            if (empty($artikel->slug)) {
                $artikel->slug = Str::slug($artikel->judul);
            }
            // Pastikan slug unik
            $count = 1;
            while (static::where('slug', $artikel->slug)->exists()) {
                $artikel->slug = Str::slug($artikel->judul) . '-' . $count++;
            }
        });
    }

    // Route binding pakai slug
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function prestasi()
    {
        return $this->belongsTo(Prestasi::class);
    }

    // Scope untuk artikel publish
    public function scopePublish($query)
    {
        return $query->where('status', 'Publish')
            ->where('tanggal_publikasi', '<=', now());
    }
}