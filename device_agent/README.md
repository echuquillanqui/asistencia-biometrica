# Agente local ZKTeco

Ejecuta cada minuto por cron:

```bash
* * * * * /usr/bin/php /ruta/proyecto/device_agent/sync.php >/dev/null 2>&1
```

En Windows (Task Scheduler), usa una tarea programada cada 1 minuto con una acción de este tipo:

```bat
"C:\xampp\php\php.exe" "C:\asistencia\device_agent\sync.php"
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

#### Alternativa en Windows (sin cron)

1. Abre **Programador de tareas** (`taskschd.msc`).
2. Crea una tarea: **Asistencia Biometrica Sync**.
3. En **Desencadenadores**: iniciar *diariamente* y en "Configuración avanzada" repetir tarea **cada 1 minuto** durante **indefinidamente**.
4. En **Acciones**:
   - Programa o script: `C:\xampp\php\php.exe`
   - Agregar argumentos: `"C:\asistencia\device_agent\sync.php"`
   - Iniciar en (opcional): `C:\asistencia\device_agent`
5. En **Condiciones**: desactiva "Iniciar la tarea solo si el equipo está conectado a corriente" si la PC es mini-PC/UPS.
6. En **Configuración**: habilita "Ejecutar la tarea lo antes posible después de un inicio programado omitido".

Para una creación rápida por consola (como Administrador):

```bat
schtasks /Create /SC MINUTE /MO 1 /TN "AsistenciaBiometricaSync" /TR "\"C:\xampp\php\php.exe\" \"C:\asistencia\device_agent\sync.php\"" /F
```

### 5) Verificación rápida

- Ejecuta manualmente una vez:

```bash
php /opt/asistencia/device_agent/sync.php
```

- Si ves errores de conexión, revisa IP/puertos/API key y firewall.

//CONSIDERAR ESTO

Summary

Te dejé documentado exactamente qué archivos configurar en la PC de sede y cómo ejecutar la sincronización automática en Windows/XAMPP (Task Scheduler), además del modo Linux con cron. Esto quedó en device_agent/README.md con pasos y comando schtasks listos para usar.

También actualicé el manual interno de sedes para que en la app quede claro el flujo de Linux vs Windows (programa C:\xampp\php\php.exe, argumentos del sync.php, frecuencia cada 1 minuto).

Para la configuración en sede, el archivo clave sigue siendo device_agent/config.php: ahí registras cada huellero en devices[] con su device_ip, device_port, api_url (tu IP VPN + /api/device) y api_key única por equipo.

Cómo hacerlo en la PC de sede (resumen práctico)

Copia carpeta device_agent en una ruta fija (ej. C:\asistencia\device_agent).

Edita device_agent/config.php y completa cada dispositivo en devices[] (uno por huellero).

En api_url usa tu servidor por VPN, por ejemplo:
http://26.109.74.146:8080/api/device (no localhost en la sede).

Programa ejecución cada minuto:

Linux: cron * * * * * /usr/bin/php /ruta/.../sync.php

Windows/XAMPP: Task Scheduler con C:\xampp\php\php.exe y argumento "C:\asistencia\device_agent\sync.php"

Prueba manual una vez con PHP CLI para validar conexión/API key.
