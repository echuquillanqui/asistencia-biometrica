<?php

$config = require __DIR__.'/config.php';

function resolveDevices(array $config): array
{
    if (isset($config['devices']) && is_array($config['devices']) && !empty($config['devices'])) {
        return $config['devices'];
    }

    return [[
        'name' => 'legacy-device',
        'device_ip' => $config['device_ip'] ?? null,
        'device_port' => $config['device_port'] ?? 4370,
        'device_password' => $config['device_password'] ?? '0',
        'api_url' => $config['api_url'] ?? null,
        'api_key' => $config['api_key'] ?? null,
    ]];
}

function isDeviceConfigValid(array $device): bool
{
    return !empty($device['device_ip'])
        && !empty($device['device_port'])
        && !empty($device['api_url'])
        && !empty($device['api_key']);
}

function connectToDevice(array $device): ?ZKLibrary
{
    $libraryPath = dirname(__DIR__).'/app/Libraries/zklibrary.php';

    if (!file_exists($libraryPath)) {
        file_put_contents(__DIR__.'/agent-error.log', date('c')." ERROR: ZKLibrary no encontrada en {$libraryPath}\n", FILE_APPEND);
        return null;
    }

    require_once $libraryPath;

    $zk = new ZKLibrary($device['device_ip'], (int) $device['device_port']);
    $connected = $zk->connect();

    if (!$connected) {
        file_put_contents(__DIR__.'/agent-error.log', date('c')." ERROR [{$device['name']}]: No fue posible conectar al dispositivo {$device['device_ip']}:{$device['device_port']}\n", FILE_APPEND);
        return null;
    }

    return $zk;
}

function fetchDeviceLogs(array $device): array
{
    $zk = connectToDevice($device);
    if (!$zk) {
        return [];
    }

    $logs = [];

    try {
        $zk->disableDevice();
        $attendanceLogs = $zk->getAttendance();

        if (!is_array($attendanceLogs)) {
            return [];
        }

        foreach ($attendanceLogs as $entry) {
            if (!is_array($entry) || count($entry) < 4) {
                continue;
            }

            [$uid, $userId, $state, $timestamp] = $entry;

            $logs[] = [
                'uid' => (string) $uid,
                'user_id' => (string) $userId,
                'fingerprint_id' => (string) $userId,
                'check_time' => date('Y-m-d H:i:s', (int) $timestamp),
                'status' => (string) $state,
                'source' => 'zklibrary',
            ];
        }
    } finally {
        $zk->enableDevice();
        $zk->disconnect();
    }

    return $logs;
}

function fetchEmployeesFromCentral(string $url, string $apiKey, string $deviceName): array
{
    $ch = curl_init($url.'/employees');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'Accept: application/json',
            'X-DEVICE-API-KEY: '.$apiKey,
        ],
        CURLOPT_TIMEOUT => 15,
    ]);

    $response = curl_exec($ch);
    $error = curl_error($ch);
    $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($error) {
        file_put_contents(__DIR__.'/agent-error.log', date('c')." ERROR empleados [{$deviceName}]: {$error}\n", FILE_APPEND);
        return [];
    }

    if ($httpCode < 200 || $httpCode >= 300 || !$response) {
        file_put_contents(__DIR__.'/agent-error.log', date('c')." ERROR empleados HTTP {$httpCode} [{$deviceName}]: {$response}\n", FILE_APPEND);
        return [];
    }

    $employees = json_decode($response, true);
    if (!is_array($employees)) {
        file_put_contents(__DIR__.'/agent-error.log', date('c')." ERROR empleados [{$deviceName}]: respuesta inválida\n", FILE_APPEND);
        return [];
    }

    return $employees;
}

function syncEmployeesToDevice(array $device): void
{
    $employees = fetchEmployeesFromCentral($device['api_url'], $device['api_key'], $device['name']);
    if (empty($employees)) {
        return;
    }

    $zk = connectToDevice($device);
    if (!$zk) {
        return;
    }

    $synced = 0;
    $skipped = 0;

    try {
        $zk->disableDevice();

        foreach ($employees as $employee) {
            $fingerprintId = (string) ($employee['fingerprint_id'] ?? '');
            $fallbackUid = (string) ($employee['id'] ?? '');
            $uidSource = ctype_digit($fingerprintId) ? $fingerprintId : $fallbackUid;

            if (!ctype_digit($uidSource)) {
                $skipped++;
                file_put_contents(__DIR__.'/agent-error.log', date('c')." WARN [{$device['name']}] empleado omitido sin UID numérico (fingerprint_id={$fingerprintId}, id={$fallbackUid})\n", FILE_APPEND);
                continue;
            }

            $uid = (int) $uidSource;
            $userId = $fingerprintId !== '' ? $fingerprintId : (string) $employee['employee_code'];
            $name = trim((string) ($employee['first_name'] ?? '').' '.(string) ($employee['last_name'] ?? ''));
            $name = $name !== '' ? $name : ((string) ($employee['employee_code'] ?? 'Empleado'));

            $created = $zk->setUser($uid, $userId, $name, '', 0);
            if ($created) {
                $synced++;
            } else {
                $skipped++;
                file_put_contents(__DIR__.'/agent-error.log', date('c')." WARN [{$device['name']}] no se pudo sincronizar empleado uid={$uid}, user_id={$userId}\n", FILE_APPEND);
            }
        }
    } finally {
        $zk->enableDevice();
        $zk->disconnect();
    }

    file_put_contents(__DIR__.'/agent-sync.log', date('c')." EMPLEADOS [{$device['name']}] synced={$synced} skipped={$skipped}\n", FILE_APPEND);
}

function sendToCentral(string $url, string $apiKey, array $logs, string $deviceName): void
{
    $payload = json_encode(['logs' => $logs]);

    $ch = curl_init($url.'/sync-logs');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'X-DEVICE-API-KEY: '.$apiKey,
        ],
        CURLOPT_POSTFIELDS => $payload,
        CURLOPT_TIMEOUT => 15,
    ]);

    $response = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);

    if ($error) {
        file_put_contents(__DIR__.'/agent-error.log', date('c')." ERROR [{$deviceName}]: {$error}\n", FILE_APPEND);
        return;
    }

    file_put_contents(__DIR__.'/agent-sync.log', date('c')." RESPONSE [{$deviceName}]: {$response}\n", FILE_APPEND);
}

$devices = resolveDevices($config);

foreach ($devices as $index => $device) {
    $device = array_merge([
        'name' => 'device-'.($index + 1),
        'device_password' => '0',
    ], $device);

    if (!isDeviceConfigValid($device)) {
        file_put_contents(__DIR__.'/agent-error.log', date('c')." ERROR [{$device['name']}]: configuración incompleta (device_ip, device_port, api_url, api_key)\n", FILE_APPEND);
        continue;
    }

    $logs = fetchDeviceLogs($device);
    if (!empty($logs)) {
        sendToCentral($device['api_url'], $device['api_key'], $logs, $device['name']);
    }

    syncEmployeesToDevice($device);
}
