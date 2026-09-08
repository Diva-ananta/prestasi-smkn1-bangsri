<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriLomba extends Model
{

public function prestasis()
{
    return $this->hasMany(Prestasi::class);
}
}
