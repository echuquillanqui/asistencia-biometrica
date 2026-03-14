<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Services\AttendanceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    public function __construct(private readonly AttendanceService $attendanceService)
    {
    }

    public function syncLogs(Request $request): JsonResponse
    {
        $validated = $request->validate(['logs' => ['required', 'array', 'min:1']]);
        $device = $request->attributes->get('device');
        $registered = 0;

        foreach ($validated['logs'] as $log) {
            $attendance = $this->attendanceService->registerFromLog($device, $log);
            if ($attendance) {
                $registered++;
            }
        }

        $device->update(['last_sync' => now(), 'status' => 'online']);

        return response()->json(['message' => 'Sincronización procesada', 'registered' => $registered]);
    }

    public function registerLog(Request $request): JsonResponse
    {
        $device = $request->attributes->get('device');
        $attendance = $this->attendanceService->registerFromLog($device, $request->all());
        $device->update(['last_sync' => now(), 'status' => 'online']);

        return response()->json([
            'message' => $attendance ? 'Asistencia registrada' : 'Log almacenado (sin asistencia nueva)',
            'attendance_id' => $attendance?->id,
        ]);
    }

    public function employees(Request $request): JsonResponse
    {
        $device = $request->attributes->get('device');

        $employees = Employee::where('branch_id', $device->branch_id)
            ->where('status', 'active')
            ->get(['id', 'employee_code', 'first_name', 'last_name', 'fingerprint_id']);

        return response()->json($employees);
    }

    public function heartbeat(Request $request): JsonResponse
    {
        $device = $request->attributes->get('device');
        $device->update([
            'status' => $request->input('status', 'online'),
            'last_sync' => now(),
        ]);

        return response()->json(['message' => 'Heartbeat actualizado']);
    }
}
