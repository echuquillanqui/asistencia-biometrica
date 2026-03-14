<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = collect([
            'manage_users', 'manage_devices', 'manage_branches', 'manage_employees',
            'view_attendance', 'manage_attendance', 'view_reports',
        ])->mapWithKeys(fn ($name) => [$name => Permission::firstOrCreate(['name' => $name], ['description' => $name])]);

        $admin = Role::firstOrCreate(['name' => 'Administrador'], ['description' => 'Control total']);
        $supervisor = Role::firstOrCreate(['name' => 'Supervisor'], ['description' => 'Supervisión operativa']);
        $operator = Role::firstOrCreate(['name' => 'Operador'], ['description' => 'Operación diaria']);

        $admin->permissions()->sync($permissions->pluck('id'));
        $supervisor->permissions()->sync($permissions->only(['manage_employees','view_attendance','view_reports'])->pluck('id'));
        $operator->permissions()->sync($permissions->only(['view_attendance','manage_attendance'])->pluck('id'));
    }
}
