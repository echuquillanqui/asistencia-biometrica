<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index(){ $roles = Role::with('permissions')->paginate(10); return view('admin.roles.index', compact('roles')); }
    public function create(){ $permissions = Permission::all(); return view('admin.roles.create', compact('permissions')); }
    public function store(Request $request){ $data = $request->validate(['name'=>'required|unique:roles,name','description'=>'nullable','permissions'=>'array']); $role = Role::create($data); $role->permissions()->sync($request->permissions ?? []); return redirect()->route('roles.index')->with('success','Rol creado'); }
    public function edit(Role $role){ $permissions = Permission::all(); return view('admin.roles.edit', compact('role','permissions')); }
    public function update(Request $request, Role $role){ $data = $request->validate(['name'=>'required|unique:roles,name,'.$role->id,'description'=>'nullable','permissions'=>'array']); $role->update($data); $role->permissions()->sync($request->permissions ?? []); return redirect()->route('roles.index')->with('success','Rol actualizado'); }
    public function destroy(Role $role){ $role->delete(); return back()->with('success','Rol eliminado'); }
}
