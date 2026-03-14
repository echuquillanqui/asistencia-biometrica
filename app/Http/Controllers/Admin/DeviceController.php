<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DeviceController extends Controller
{
    public function index() { $devices = Device::with('branch')->latest()->paginate(10); return view('admin.devices.index', compact('devices')); }
    public function create() { $branches = Branch::all(); return view('admin.devices.create', compact('branches')); }
    public function store(Request $request) {
        $data = $request->validate([
            'branch_id'=>'required|exists:branches,id','name'=>'required','serial_number'=>'required|unique:devices,serial_number',
            'ip_address'=>'required|ip','port'=>'required|integer','device_password'=>'nullable','status'=>'required'
        ]);
        $data['api_key'] = Str::uuid()->toString();
        Device::create($data);
        return redirect()->route('devices.index')->with('success','Dispositivo creado');
    }
    public function edit(Device $device) { $branches = Branch::all(); return view('admin.devices.edit', compact('device','branches')); }
    public function update(Request $request, Device $device) {
        $device->update($request->validate([
            'branch_id'=>'required|exists:branches,id','name'=>'required','serial_number'=>'required|unique:devices,serial_number,'.$device->id,
            'ip_address'=>'required|ip','port'=>'required|integer','device_password'=>'nullable','status'=>'required'
        ]));
        return redirect()->route('devices.index')->with('success','Dispositivo actualizado');
    }
    public function destroy(Device $device) { $device->delete(); return back()->with('success','Dispositivo eliminado'); }
}
