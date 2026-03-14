<?php

$config = require __DIR__.'/config.php';

function fetchDeviceLogs(array $config): array
{
    // Stub: integrar librería ZKTeco real (UDP/TCP SDK) en producción.
    return [[
        'user_id' => '1',
        'fingerprint_id' => '1',
        'check_time' => date('Y-m-d H:i:s'),
        'status' => 'ok',
    ]];
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
