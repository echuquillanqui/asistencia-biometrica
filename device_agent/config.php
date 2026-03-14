<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Modo recomendado: múltiples dispositivos por sede
    |--------------------------------------------------------------------------
    | Registra cada huellero como un bloque dentro de `devices`.
    | Si solo tienes uno, deja un solo elemento en el arreglo.
    */
    'devices' => [
        [
            'name' => 'Sede-01-Recepcion',
            'device_ip' => '192.168.1.100',
            'device_port' => 4370,
            'device_password' => '0',
            // Con Radmin VPN puedes usar HTTP en red privada.
            // Si publicas por internet, usa HTTPS.
            'api_url' => 'http://10.99.0.10:8000/api/device',
            'api_key' => 'CHANGE_ME_SEDE_01',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Compatibilidad legado (un solo dispositivo)
    |--------------------------------------------------------------------------
    | Opcional: se mantiene para instalaciones antiguas.
    */
    'device_ip' => '192.168.1.100',
    'device_port' => 4370,
    'device_password' => '0',
    'api_url' => 'http://localhost:8000/api/device',
    'api_key' => 'CHANGE_ME',

    'sync_interval' => 60,
];
