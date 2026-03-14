# Agente local ZKTeco

Ejecuta cada minuto por cron:

```bash
* * * * * /usr/bin/php /ruta/proyecto/device_agent/sync.php >/dev/null 2>&1
```

El agente realiza dos tareas:

1. Descarga logs del biométrico y los sincroniza contra la API central usando `X-DEVICE-API-KEY`.
2. Consulta `GET /api/device/employees` y sincroniza los empleados activos al huellero con `setUser`.

> Importante: esta sincronización crea/actualiza datos de usuario (UID, código/ID y nombre),
> pero **no** transfiere plantillas biométricas de huella. La enrolación de huella se hace directamente en el dispositivo.

## Configuración para varias sedes/varios huelleros

Ya no estás limitado a 1 huellero por archivo. En `device_agent/config.php` puedes registrar varios en el arreglo `devices`:

```php
return [
    'devices' => [
        [
            'name' => 'Sede-01-Recepcion',
            'device_ip' => '192.168.1.100',
            'device_port' => 4370,
            'device_password' => '0',
            'api_url' => 'http://10.99.0.10:8000/api/device',
            'api_key' => 'API_KEY_DEL_DISPOSITIVO_1',
        ],
        [
            'name' => 'Sede-02-Almacen',
            'device_ip' => '192.168.18.100',
            'device_port' => 4370,
            'device_password' => '0',
            'api_url' => 'http://10.99.0.10:8000/api/device',
            'api_key' => 'API_KEY_DEL_DISPOSITIVO_2',
        ],
    ],
    'sync_interval' => 60,
];
```

### ¿Tengo que registrar 1 a 1 los huelleros?

Sí. Debes registrar cada huellero (cada uno con su API key) porque la API autentica por dispositivo.
Eso permite saber qué sede/equipo envió cada marcación y evitar mezcla de credenciales.

### ¿HTTP o HTTPS con Radmin VPN?

- Si todo el tráfico viaja dentro de tu red privada por Radmin VPN, puedes usar `http://` hacia la IP VPN privada.
- Si el tráfico sale a internet pública o atraviesa redes no confiables, usa `https://`.

Recomendación: en producción, migra a HTTPS cuando sea posible.

## Instalación en la PC puente (resumen práctico)

No necesitas clonar TODO el proyecto Laravel en la PC puente.
Para el agente local solo necesitas PHP y la carpeta `device_agent`.

### 1) ¿Qué instalar en la PC puente?

- PHP CLI (recomendado 8.x).
- Extensiones comunes de PHP (`curl`, `json`, `mbstring`) si tu instalación no las trae por defecto.
- Conectividad de red hacia:
  - el huellero (puerto 4370 normalmente),
  - la API central (`api_url` que pongas en config).

### 2) ¿Qué archivos copiar?

Copia la carpeta `device_agent` a una ruta fija, por ejemplo:

```bash
/opt/asistencia/device_agent
```

Dentro de esa carpeta deben existir al menos:

- `config.php`
- `sync.php`

### 3) ¿Dónde registrar los huelleros?

En `device_agent/config.php`, dentro de `devices[]`.
Sí, es 1 bloque por huellero y cada uno con su `api_key` propia.

### 4) ¿Cómo se ejecuta la sincronización?

Con cron, apuntando a la ruta real donde dejaste `sync.php`:

```bash
* * * * * /usr/bin/php /opt/asistencia/device_agent/sync.php >/dev/null 2>&1
```

Eso ejecuta la sincronización cada minuto.

### 5) Verificación rápida

- Ejecuta manualmente una vez:

```bash
php /opt/asistencia/device_agent/sync.php
```

- Si ves errores de conexión, revisa IP/puertos/API key y firewall.
