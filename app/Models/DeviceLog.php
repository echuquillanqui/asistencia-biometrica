<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceLog extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['device_id', 'user_id', 'log_time', 'status', 'raw_data', 'created_at'];

    protected $casts = [
        'log_time' => 'datetime',
        'raw_data' => 'array',
        'created_at' => 'datetime',
    ];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}
