<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Device;
use App\Models\Employee;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BiometricDemoSeeder extends Seeder
{
    public function run(): void
    {
        $branch = Branch::firstOrCreate(['name' => 'Sede Central'], [
            'city' => 'Bogotá',
            'address' => 'Calle 100 #10-10',
            'timezone' => 'America/Bogota',
        ]);

        $device = Device::firstOrCreate(['serial_number' => 'ZK-DEMO-001'], [
            'branch_id' => $branch->id,
            'name' => 'Reloj Principal',
            'ip_address' => '192.168.1.100',
            'port' => 4370,
            'device_password' => '0',
            'api_key' => Str::uuid()->toString(),
            'status' => 'offline',
        ]);

        Employee::firstOrCreate(['employee_code' => 'EMP001'], [
            'first_name' => 'Ana',
            'last_name' => 'Pérez',
            'document_number' => '10000001',
            'branch_id' => $branch->id,
            'fingerprint_id' => '1',
            'status' => 'active',
        ]);
    }
}
