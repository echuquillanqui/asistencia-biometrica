<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Branch;
use App\Models\Device;
use App\Models\Employee;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Attendance::with(['employee','device','branch'])->latest('check_time');
        if ($request->filled('branch_id')) $query->where('branch_id', $request->branch_id);
        if ($request->filled('type')) $query->where('type', $request->type);
        $attendances = $query->paginate(15);
        $branches = Branch::all();
        return view('admin.attendances.index', compact('attendances','branches'));
    }
    public function create(){ return view('admin.attendances.create',['employees'=>Employee::all(),'devices'=>Device::all(),'branches'=>Branch::all()]); }
    public function store(Request $r){ Attendance::create($r->validate(['employee_id'=>'required|exists:employees,id','device_id'=>'required|exists:devices,id','branch_id'=>'required|exists:branches,id','check_time'=>'required|date','type'=>'required|in:checkin,checkout','raw_log'=>'nullable'])); return redirect()->route('attendances.index')->with('success','Asistencia creada'); }
    public function edit(Attendance $attendance){ return view('admin.attendances.edit',['attendance'=>$attendance,'employees'=>Employee::all(),'devices'=>Device::all(),'branches'=>Branch::all()]); }
    public function update(Request $r, Attendance $attendance){ $attendance->update($r->validate(['employee_id'=>'required|exists:employees,id','device_id'=>'required|exists:devices,id','branch_id'=>'required|exists:branches,id','check_time'=>'required|date','type'=>'required|in:checkin,checkout','raw_log'=>'nullable'])); return redirect()->route('attendances.index')->with('success','Asistencia actualizada'); }
    public function destroy(Attendance $attendance){ $attendance->delete(); return back()->with('success','Asistencia eliminada'); }
}
