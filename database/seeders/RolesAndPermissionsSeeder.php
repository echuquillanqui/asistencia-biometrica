<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin General',
                'email' => 'admin@asistencia.test',
                'role' => User::ROLE_ADMIN,
                'permissions' => ['*'],
            ],
            [
                'name' => 'Supervisor Planta',
                'email' => 'supervisor@asistencia.test',
                'role' => User::ROLE_SUPERVISOR,
                'permissions' => [
                    'usuarios.ver',
                    'reportes.ver',
                    'asistencia.ver',
                    'asistencia.aprobar',
                ],
            ],
            [
                'name' => 'Operador Reloj',
                'email' => 'operador@asistencia.test',
                'role' => User::ROLE_OPERADOR,
                'permissions' => [
                    'asistencia.marcar',
                    'asistencia.corregir',
                ],
            ],
            [
                'name' => 'Empleado Demo',
                'email' => 'empleado@asistencia.test',
                'role' => User::ROLE_EMPLEADO,
                'permissions' => [
                    'asistencia.marcar',
                    'asistencia.ver-propia',
                ],
            ],
        ];

        foreach ($users as $data) {
            User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                    'role' => $data['role'],
                    'permissions' => $data['permissions'],
                ],
            );
        }
    }
}
