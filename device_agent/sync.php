<?php

$config = require __DIR__.'/config.php';

function fetchDeviceLogs(array $config): array
{
    $libraryPath = dirname(__DIR__).'/app/Libraries/zklibrary.php';

    if (!file_exists($libraryPath)) {
        file_put_contents(__DIR__.'/agent-error.log', date('c')." ERROR: ZKLibrary no encontrada en {$libraryPath}\n", FILE_APPEND);
        return [];
    }

    require_once $libraryPath;

    $zk = new ZKLibrary($config['device_ip'], (int) $config['device_port']);
    $connected = $zk->connect();

    if (!$connected) {
        file_put_contents(__DIR__.'/agent-error.log', date('c')." ERROR: No fue posible conectar al dispositivo {$config['device_ip']}:{$config['device_port']}\n", FILE_APPEND);
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

function sendToCentral(string $url, string $apiKey, array $logs): void
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
        file_put_contents(__DIR__.'/agent-error.log', date('c')." ERROR: $error\n", FILE_APPEND);
        return;
    }

    file_put_contents(__DIR__.'/agent-sync.log', date('c')." RESPONSE: $response\n", FILE_APPEND);
}

$logs = fetchDeviceLogs($config);
if (!empty($logs)) {
    sendToCentral($config['api_url'], $config['api_key'], $logs);
}
