# Agente local ZKTeco

Ejecuta cada minuto por cron:

```bash
* * * * * /usr/bin/php /ruta/proyecto/device_agent/sync.php >/dev/null 2>&1
```

Este agente descarga logs del biométrico (placeholder en `fetchDeviceLogs`) y sincroniza contra la API central usando `X-DEVICE-API-KEY`.
