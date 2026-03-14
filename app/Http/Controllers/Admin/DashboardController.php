<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Device;
use App\Models\Employee;

class DashboardController extends Controller
{
    public function index()
    {
        $metrics = [
            'employees' => Employee::count(),
            'devices' => Device::count(),
            'today_attendance' => Attendance::whereDate('check_time', today())->count(),
            'offline_devices' => Device::where('status', 'offline')->count(),
            'last_sync' => Device::max('last_sync'),
            'devices_status' => Device::with('branch')->latest('last_sync')->get(),
        ];

        return view('admin.dashboard', compact('metrics'));
    }
}
