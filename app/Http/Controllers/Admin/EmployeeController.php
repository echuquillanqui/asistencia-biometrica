<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index() { $employees = Employee::with('branch')->latest()->paginate(10); return view('admin.employees.index', compact('employees')); }
    public function create() { $branches = Branch::all(); return view('admin.employees.create', compact('branches')); }
    public function store(Request $request) {
        Employee::create($request->validate([
            'employee_code'=>'required|unique:employees,employee_code','first_name'=>'required','last_name'=>'required',
            'document_number'=>'required|unique:employees,document_number','branch_id'=>'required|exists:branches,id',
            'fingerprint_id'=>'required|unique:employees,fingerprint_id','status'=>'required'
        ]));
        return redirect()->route('employees.index')->with('success','Empleado creado');
    }
    public function edit(Employee $employee) { $branches = Branch::all(); return view('admin.employees.edit', compact('employee','branches')); }
    public function update(Request $request, Employee $employee) {
        $employee->update($request->validate([
            'employee_code'=>'required|unique:employees,employee_code,'.$employee->id,'first_name'=>'required','last_name'=>'required',
            'document_number'=>'required|unique:employees,document_number,'.$employee->id,'branch_id'=>'required|exists:branches,id',
            'fingerprint_id'=>'required|unique:employees,fingerprint_id,'.$employee->id,'status'=>'required'
        ]));
        return redirect()->route('employees.index')->with('success','Empleado actualizado');
    }
    public function destroy(Employee $employee) { $employee->delete(); return back()->with('success','Empleado eliminado'); }
}
