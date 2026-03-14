@extends('manuales.base')

@section('title', 'Manual del servidor principal')

@section('content')
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
        <div>
            <h1 class="mb-1">Manual del servidor principal</h1>
            <p class="muted mb-0">Guía paso a paso para instalar el sistema central, pensada para usuarios con poco conocimiento técnico.</p>
        </div>
        <span class="badge bg-primary">Nivel: Básico</span>
    </div>

    <div class="alert alert-info" role="alert">
        <strong>Objetivo:</strong> dejar el servidor listo para que las sedes remotas envíen asistencia por Internet de forma segura.
    </div>

    <h2>1) Antes de empezar (checklist simple)</h2>
    <ul>
        <li>Una computadora o VPS que estará encendida siempre (servidor principal).</li>
        <li>Sistema operativo con PHP 8.2+, Composer 2+, MySQL/MariaDB y Node.js 20+.</li>
        <li>Dominio o IP pública accesible desde las sedes (recomendado usar dominio + HTTPS).</li>
        <li>Acceso para abrir puertos/firewall y configurar SSL.</li>
    </ul>

    <h2>2) Instalación base del proyecto</h2>
    <ol>
        <li>Clonar el repositorio y entrar al proyecto.</li>
        <li>Instalar dependencias backend y frontend:<br><code>composer install</code><br><code>npm install</code></li>
        <li>Copiar entorno:<br><code>cp .env.example .env</code></li>
        <li>Configurar base de datos en <code>.env</code> (DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD).</li>
        <li>Inicializar aplicación:<br><code>php artisan key:generate</code><br><code>php artisan migrate --seed</code></li>
        <li>Compilar assets:<br><code>npm run build</code></li>
    </ol>

    <h2>3) Publicar el sistema en Internet</h2>
    <ol>
        <li>Apuntar dominio (o subdominio) al servidor principal.</li>
        <li>Configurar servidor web (Nginx/Apache) hacia la carpeta <code>public/</code>.</li>
        <li>Activar HTTPS (Let’s Encrypt recomendado).</li>
        <li>Probar en navegador que la URL principal abre correctamente.</li>
    </ol>

    <div class="alert alert-warning" role="alert">
        <strong>Importante:</strong> para sedes remotas, no uses <code>localhost</code> en el agente. Debes usar una URL pública como
        <code>https://tudominio.com/api/device</code>.
    </div>

    <h2>4) Endpoints de dispositivos (API)</h2>
    <p>Estos endpoints ya vienen incluidos en el sistema. Son usados por los huelleros/agentes:</p>
    <div class="card mb-3">
        <div class="card-body">
            <ul class="mb-0">
                <li><code>POST /api/device/heartbeat</code> → confirma conexión del dispositivo.</li>
                <li><code>POST /api/device/sync-logs</code> → envía lotes de marcaciones.</li>
                <li><code>POST /api/device/register-log</code> → envía una marcación individual.</li>
                <li><code>GET /api/device/employees</code> → descarga empleados activos de la sede.</li>
            </ul>
        </div>
    </div>
    <p>Todos requieren cabecera <code>X-DEVICE-API-KEY</code> con la clave del dispositivo.</p>

    <h2>5) Prueba rápida de conectividad (manual)</h2>
    <ol>
        <li>Crear/registrar un dispositivo en el panel y copiar su API key.</li>
        <li>Desde la sede o desde una terminal externa, ejecutar:</li>
    </ol>
    <pre class="bg-light p-3 rounded"><code>curl -X POST "https://TU_DOMINIO/api/device/heartbeat" \
  -H "X-DEVICE-API-KEY: TU_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"status":"online"}'</code></pre>
    <ol start="3">
        <li>Si responde <code>Heartbeat actualizado</code>, la conexión API está correcta.</li>
        <li>Revisar Dashboard &gt; Monitor de dispositivos para verificar estado y última sincronización.</li>
    </ol>

    <h2>6) Operación diaria recomendada</h2>
    <ul>
        <li>Revisar en dashboard los equipos <strong>offline</strong>.</li>
        <li>Validar que la <strong>última sincronización</strong> no tenga muchas horas de retraso.</li>
        <li>Monitorear logs y respaldar base de datos diariamente.</li>
    </ul>
@endsection
