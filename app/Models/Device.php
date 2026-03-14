<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id', 'name', 'serial_number', 'ip_address', 'port',
        'device_password', 'api_key', 'last_sync', 'status',
    ];

    protected $casts = ['last_sync' => 'datetime'];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function logs()
    {
        return $this->hasMany(DeviceLog::class);
    }
}
