<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeletedRecord extends Model
{
    protected $fillable = ['model_type', 'model_id', 'snapshot', 'deleted_by', 'deleted_at'];

    protected function casts(): array
    {
        return ['snapshot' => 'array', 'deleted_at' => 'datetime'];
    }

    public function deleter()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}
