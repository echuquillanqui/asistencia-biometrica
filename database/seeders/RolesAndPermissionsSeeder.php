<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissionsByRole = [
            User::ROLE_ADMIN => [
                'manage_users',
                'manage_devices',
                'manage_branches',
                'manage_employees',
                'view_attendance',
                'manage_attendance',
                'view_reports',
            ],
            User::ROLE_SUPERVISOR => [
                'manage_employees',
                'view_attendance',
                'view_reports',
            ],
            User::ROLE_OPERADOR => [
                'view_attendance',
                'manage_attendance',
            ],
            User::ROLE_EMPLEADO => [
                'view_attendance',
            ],
        ];

        $allPermissions = collect($permissionsByRole)->flatten()->unique()->values();
        foreach ($allPermissions as $permissionName) {
            Permission::findOrCreate($permissionName, 'web');
        }

        foreach ($permissionsByRole as $roleName => $permissions) {
            $role = Role::findOrCreate($roleName, 'web');
            $role->syncPermissions($permissions);
        }

        $users = [
            ['name' => 'Admin General', 'email' => 'admin@asistencia.test', 'role' => User::ROLE_ADMIN],
            ['name' => 'Supervisor Planta', 'email' => 'supervisor@asistencia.test', 'role' => User::ROLE_SUPERVISOR],
            ['name' => 'Operador Reloj', 'email' => 'operador@asistencia.test', 'role' => User::ROLE_OPERADOR],
            ['name' => 'Empleado Demo', 'email' => 'empleado@asistencia.test', 'role' => User::ROLE_EMPLEADO],
        ];

        foreach ($users as $data) {
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ],
            );

            $user->syncRoles([$data['role']]);
        }
    }
}
