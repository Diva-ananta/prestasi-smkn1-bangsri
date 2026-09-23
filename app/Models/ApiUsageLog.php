<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiUsageLog extends Model
{
    protected $fillable = [
        'api_client_id',
        'method',
        'endpoint',
        'status_code',
        'ip_address',
        'user_agent',
        'response_time_ms',
    ];

    protected $casts = [
        'status_code' => 'integer',
        'response_time_ms' => 'integer',
    ];

    public function apiClient()
    {
        return $this->belongsTo(ApiClient::class);
    }
}