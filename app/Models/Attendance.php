<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = ['employee_id', 'device_id', 'branch_id', 'check_time', 'type', 'raw_log'];

    protected $casts = [
        'check_time' => 'datetime',
        'raw_log' => 'array',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
