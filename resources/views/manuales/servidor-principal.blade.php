@extends('manuales.base')

@section('title', 'Manual del servidor principal')

@section('content')
    <h1>Manual del servidor principal</h1>
    <p class="muted">Guía base para instalar y operar el sistema central.</p>

    <h2>1) Requisitos</h2>
    <ul>
        <li>PHP 8.2+, Composer 2+, MySQL o MariaDB.</li>
        <li>Node.js 20+ para assets front-end.</li>
        <li>Acceso de red para que las sedes se conecten al servidor central.</li>
    </ul>

    <h2>2) Instalación</h2>
    <ol>
        <li>Clonar el repositorio y entrar al proyecto.</li>
        <li>Instalar dependencias: <code>composer install</code> y <code>npm install</code>.</li>
        <li>Configurar entorno: copiar <code>.env.example</code> a <code>.env</code>.</li>
        <li>Generar llave y migrar base de datos: <code>php artisan key:generate</code> y <code>php artisan migrate --seed</code>.</li>
        <li>Compilar assets: <code>npm run build</code>.</li>
    </ol>

    <h2>3) Puesta en marcha</h2>
    <ul>
        <li>Levantar aplicación con <code>php artisan serve --host=0.0.0.0 --port=8000</code>.</li>
        <li>Configurar HTTPS y dominio en producción.</li>
        <li>Crear usuarios administrativos y asignar permisos por rol.</li>
    </ul>
@endsection
