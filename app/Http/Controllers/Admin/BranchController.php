<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index() { $branches = Branch::latest()->paginate(10); return view('admin.branches.index', compact('branches')); }
    public function create() { return view('admin.branches.create'); }
    public function store(Request $request) {
        Branch::create($request->validate(['name'=>'required','city'=>'required','address'=>'required','timezone'=>'required']));
        return redirect()->route('branches.index')->with('success','Sucursal creada');
    }
    public function edit(Branch $branch) { return view('admin.branches.edit', compact('branch')); }
    public function update(Request $request, Branch $branch) {
        $branch->update($request->validate(['name'=>'required','city'=>'required','address'=>'required','timezone'=>'required']));
        return redirect()->route('branches.index')->with('success','Sucursal actualizada');
    }
    public function destroy(Branch $branch) { $branch->delete(); return back()->with('success','Sucursal eliminada'); }
}
