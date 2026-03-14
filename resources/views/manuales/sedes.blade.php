@extends('manuales.base')

@section('title', 'Manual de sedes')

@section('content')
    <h1>Manual de servidor por sedes</h1>
    <p class="muted">Guía de configuración para cada punto remoto conectado al servidor principal.</p>

    <h2>1) Objetivo</h2>
    <p>Cada sede debe registrar marcaciones biométricas y sincronizarlas con el servidor principal de forma segura y periódica.</p>

    <h2>2) Preparación</h2>
    <ul>
        <li>Registrar la sede en el módulo de administración.</li>
        <li>Asociar dispositivos biométricos y validar conectividad LAN.</li>
        <li>Configurar credenciales y endpoint del servidor principal.</li>
    </ul>

    <h2>3) Flujo recomendado</h2>
    <ol>
        <li>Capturar asistencia local en el dispositivo de sede.</li>
        <li>Enviar lotes de eventos al servidor principal (cada 1-5 minutos).</li>
        <li>Verificar recepción en el módulo de reportes y reconciliar pendientes.</li>
    </ol>

    <h2>4) Buenas prácticas</h2>
    <ul>
        <li>Sincronizar hora del dispositivo con NTP.</li>
        <li>Registrar logs de envío para soporte.</li>
        <li>Definir reintentos automáticos en caso de desconexión.</li>
    </ul>
@endsection
