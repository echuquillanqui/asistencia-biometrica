@extends('manuales.base')

@section('title', 'Manual de sedes')

@section('content')
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
        <div>
            <h1 class="mb-1">Manual de sede remota</h1>
            <p class="muted mb-0">Pasos simples para conectar huelleros de cada sede al servidor principal.</p>
        </div>
        <span class="badge bg-success">Nivel: Básico</span>
    </div>

    <div class="alert alert-info" role="alert">
        <strong>Meta:</strong> que el dispositivo registre asistencia local y la envíe automáticamente al servidor principal.
    </div>

    <h2>1) Datos que debes tener a la mano</h2>
    <ul>
        <li>IP local del huellero (ejemplo: <code>192.168.1.100</code>) y puerto (normalmente <code>4370</code>).</li>
        <li>URL pública del servidor principal (ejemplo: <code>https://tudominio.com/api/device</code>).</li>
        <li>API key del dispositivo (copiada desde el módulo de Dispositivos).</li>
        <li>Conexión a Internet estable en la sede.</li>
    </ul>

    <h2>2) Configurar el agente de sede</h2>
    <ol>
        <li>Ir al archivo <code>device_agent/config.php</code>.</li>
        <li>Completar los campos:
            <ul>
                <li><code>device_ip</code> y <code>device_port</code> del huellero.</li>
                <li><code>api_url</code> apuntando al servidor principal (nunca localhost en sede).</li>
                <li><code>api_key</code> del dispositivo registrado.</li>
            </ul>
        </li>
        <li>Guardar cambios.</li>
    </ol>

    <h2>3) Activar sincronización automática</h2>
    <ol>
        <li>Configurar cron para ejecutar cada minuto:</li>
    </ol>
    <pre class="bg-light p-3 rounded"><code>* * * * * /usr/bin/php /ruta/proyecto/device_agent/sync.php >/dev/null 2>&1</code></pre>
    <ol start="2">
        <li>Esto enviará marcaciones y actualizará empleados activos periódicamente.</li>
    </ol>

    <h2>4) ¿Cómo validar que sí conectó?</h2>
    <ol>
        <li>Ejecutar una prueba de heartbeat desde la sede:</li>
    </ol>
    <pre class="bg-light p-3 rounded"><code>curl -X POST "https://TU_DOMINIO/api/device/heartbeat" \
  -H "X-DEVICE-API-KEY: TU_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"status":"online"}'</code></pre>
    <ol start="2">
        <li>Entrar al dashboard del servidor principal y revisar el Monitor de dispositivos:</li>
    </ol>
    <ul>
        <li><strong>Estado:</strong> debe pasar a <code>online</code>.</li>
        <li><strong>Últ. sync:</strong> debe mostrar fecha/hora reciente.</li>
    </ul>

    <div class="alert alert-warning" role="alert">
        Si no conecta: revisar firewall, DNS, SSL, hora del sistema, y que la API key pertenezca al dispositivo correcto.
    </div>

    <h2>5) Buenas prácticas</h2>
    <ul>
        <li>Sincronizar hora del dispositivo y servidor por NTP.</li>
        <li>Guardar logs locales del agente para soporte técnico.</li>
        <li>Usar reintentos automáticos y monitorear equipos sin sincronización.</li>
    </ul>
@endsection
