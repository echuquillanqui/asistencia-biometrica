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
