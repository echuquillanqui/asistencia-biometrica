<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index(){ $permissions = Permission::paginate(10); return view('admin.permissions.index', compact('permissions')); }
    public function create(){ return view('admin.permissions.create'); }
    public function store(Request $request){ Permission::create($request->validate(['name'=>'required|unique:permissions,name','description'=>'nullable'])); return redirect()->route('permissions.index')->with('success','Permiso creado'); }
    public function edit(Permission $permission){ return view('admin.permissions.edit', compact('permission')); }
    public function update(Request $request, Permission $permission){ $permission->update($request->validate(['name'=>'required|unique:permissions,name,'.$permission->id,'description'=>'nullable'])); return redirect()->route('permissions.index')->with('success','Permiso actualizado'); }
    public function destroy(Permission $permission){ $permission->delete(); return back()->with('success','Permiso eliminado'); }
}
