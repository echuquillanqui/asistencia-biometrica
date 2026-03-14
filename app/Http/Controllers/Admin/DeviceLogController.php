<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\DeviceLog;
use Illuminate\Http\Request;

class DeviceLogController extends Controller
{
    public function index() { $deviceLogs = DeviceLog::with('device')->orderByDesc('log_time')->paginate(20); return view('admin.device_logs.index', compact('deviceLogs')); }
    public function create() { $devices = Device::all(); return view('admin.device_logs.create', compact('devices')); }
    public function store(Request $request) { DeviceLog::create($request->validate(['device_id'=>'required|exists:devices,id','user_id'=>'required','log_time'=>'required|date','status'=>'required','raw_data'=>'nullable'])); return redirect()->route('device-logs.index')->with('success','Log creado'); }
    public function edit(DeviceLog $device_log) { $devices = Device::all(); $deviceLog = $device_log; return view('admin.device_logs.edit', compact('deviceLog','devices')); }
    public function update(Request $request, DeviceLog $device_log) { $device_log->update($request->validate(['device_id'=>'required|exists:devices,id','user_id'=>'required','log_time'=>'required|date','status'=>'required','raw_data'=>'nullable'])); return redirect()->route('device-logs.index')->with('success','Log actualizado'); }
    public function destroy(DeviceLog $device_log) { $device_log->delete(); return back()->with('success','Log eliminado'); }
}
