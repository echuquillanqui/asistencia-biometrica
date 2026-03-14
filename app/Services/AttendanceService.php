<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\Device;
use App\Models\DeviceLog;
use App\Models\Employee;
use Carbon\Carbon;

class AttendanceService
{
    public function registerFromLog(Device $device, array $payload): ?Attendance
    {
        $fingerprintId = (string) ($payload['fingerprint_id'] ?? $payload['user_id'] ?? '');
        $checkTime = Carbon::parse($payload['check_time'] ?? $payload['log_time'] ?? now());

        $employee = Employee::where('fingerprint_id', $fingerprintId)->first();

        DeviceLog::create([
            'device_id' => $device->id,
            'user_id' => $fingerprintId,
            'log_time' => $checkTime,
            'status' => $employee ? 'matched' : 'unmatched',
            'raw_data' => $payload,
            'created_at' => now(),
        ]);

        if (!$employee) {
            return null;
        }

        $duplicate = Attendance::where('employee_id', $employee->id)
            ->where('device_id', $device->id)
            ->where('check_time', $checkTime)
            ->exists();

        if ($duplicate) {
            return null;
        }

        $lastAttendance = Attendance::where('employee_id', $employee->id)
            ->whereDate('check_time', $checkTime->toDateString())
            ->latest('check_time')
            ->first();

        $type = $lastAttendance?->type === 'checkin' ? 'checkout' : 'checkin';

        return Attendance::create([
            'employee_id' => $employee->id,
            'device_id' => $device->id,
            'branch_id' => $employee->branch_id,
            'check_time' => $checkTime,
            'type' => $type,
            'raw_log' => $payload,
        ]);
    }
}
