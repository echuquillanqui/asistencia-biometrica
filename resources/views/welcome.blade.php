<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Asistencia Biométrica') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    <style>
        :root {
            --primary: #4f46e5;
            --secondary: #06b6d4;
            --text: #e2e8f0;
            --muted: #94a3b8;
            --surface: rgba(15, 23, 42, 0.65);
            --border: rgba(148, 163, 184, 0.22);
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: Figtree, sans-serif;
            color: var(--text);
            min-height: 100vh;
            background:
                radial-gradient(circle at 15% 20%, rgba(79, 70, 229, 0.35), transparent 30%),
                radial-gradient(circle at 85% 5%, rgba(6, 182, 212, 0.3), transparent 35%),
                linear-gradient(145deg, #020617, #0f172a 40%, #111827 100%);
            padding: 32px;
        }

        .container {
            max-width: 1100px;
            margin: 0 auto;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 14px 18px;
            border: 1px solid var(--border);
            border-radius: 16px;
            backdrop-filter: blur(12px);
            background: var(--surface);
        }

        .brand { font-weight: 800; letter-spacing: .5px; }

        .nav-links {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn {
            border: 1px solid transparent;
            color: var(--text);
            text-decoration: none;
            border-radius: 10px;
            font-weight: 600;
            padding: 10px 14px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: .2s ease;
        }

        .btn:hover { transform: translateY(-1px); }

        .btn-outline {
            border-color: var(--border);
            background: rgba(15, 23, 42, 0.5);
        }

        .btn-main {
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            color: #fff;
            box-shadow: 0 8px 20px rgba(79, 70, 229, .35);
        }

        .hero {
            margin-top: 36px;
            display: grid;
            grid-template-columns: 1.25fr .85fr;
            gap: 20px;
        }

        .panel {
            border: 1px solid var(--border);
            border-radius: 20px;
            background: var(--surface);
            padding: 28px;
            backdrop-filter: blur(10px);
        }

        h1 {
            font-size: clamp(2rem, 4vw, 3.2rem);
            line-height: 1.1;
            margin: 0 0 12px;
        }

        .subtitle {
            color: var(--muted);
            line-height: 1.6;
            margin-bottom: 22px;
            max-width: 64ch;
        }

        .actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .features {
            margin-top: 24px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
            gap: 12px;
        }

        .feature {
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 14px;
            background: rgba(15, 23, 42, .55);
        }

        .feature h3 { margin: 0 0 8px; font-size: 1rem; }
        .feature p { margin: 0; color: var(--muted); font-size: .92rem; line-height: 1.45; }

        .manual-list {
            display: grid;
            gap: 14px;
        }

        .manual-item {
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 14px;
            background: rgba(15, 23, 42, .55);
        }

        .manual-item strong { display: block; margin-bottom: 8px; }

        .manual-item a {
            color: #67e8f9;
            text-decoration: none;
            font-weight: 600;
        }

        .manual-item a:hover { text-decoration: underline; }

        @media (max-width: 900px) {
            body { padding: 20px; }
            .hero { grid-template-columns: 1fr; }
            .navbar { gap: 12px; align-items: flex-start; flex-direction: column; }
        }
    </style>
</head>
<body>
<div class="container">
    <nav class="navbar">
        <div class="brand">🛰️ {{ config('app.name', 'Asistencia Biométrica') }}</div>
        <div class="nav-links">
            <a class="btn btn-outline" href="{{ route('manual.main-server') }}">Manual servidor principal</a>
            <a class="btn btn-outline" href="{{ route('manual.branches') }}">Manual servidor de sedes</a>
            @if (Route::has('login'))
                @auth
                    <a class="btn btn-main" href="{{ url('/home') }}">Ir al panel</a>
                @else
                    <a class="btn btn-main" href="{{ route('login') }}">Iniciar sesión</a>
                @endauth
            @endif
        </div>
    </nav>

    <section class="hero">
        <article class="panel">
            <h1>Control de asistencia biométrica moderno y confiable</h1>
            <p class="subtitle">
                Administra marcaciones, personal, dispositivos y sedes desde una sola plataforma.
                Ahora con una interfaz renovada, más clara y preparada para operación diaria.
            </p>
            <div class="actions">
                <a class="btn btn-main" href="{{ route('manual.main-server') }}">Ver instalación del servidor principal</a>
                <a class="btn btn-outline" href="{{ route('manual.branches') }}">Ver guía para sedes</a>
            </div>

            <div class="features">
                <div class="feature">
                    <h3>📍 Gestión por sedes</h3>
                    <p>Organiza empleados y equipos de captura por ubicación con trazabilidad completa.</p>
                </div>
                <div class="feature">
                    <h3>🧾 Reportes centralizados</h3>
                    <p>Consolida registros biométricos para auditoría y control operativo diario.</p>
                </div>
                <div class="feature">
                    <h3>🔐 Seguridad y permisos</h3>
                    <p>Define accesos por rol para proteger información crítica en cada módulo.</p>
                </div>
            </div>
        </article>

        <aside class="panel">
            <h2 style="margin-top:0; margin-bottom:12px;">Manuales disponibles</h2>
            <p class="subtitle" style="margin-bottom:14px;">Acceso rápido a documentación para despliegue y operación.</p>
            <div class="manual-list">
                <div class="manual-item">
                    <strong>Servidor principal</strong>
                    <a href="{{ route('manual.main-server') }}">Abrir manual de uso e instalación</a>
                </div>
                <div class="manual-item">
                    <strong>Servidores de sedes</strong>
                    <a href="{{ route('manual.branches') }}">Abrir manual de configuración por sede</a>
                </div>
            </div>
        </aside>
    </section>
</div>
</body>
</html>
