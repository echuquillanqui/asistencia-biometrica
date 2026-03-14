<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') · {{ config('app.name', 'Asistencia Biométrica') }}</title>
    <style>
        body{margin:0;font-family:Figtree,sans-serif;background:#f8fafc;color:#0f172a}
        .wrap{max-width:900px;margin:0 auto;padding:30px 18px 50px}
        .card{background:#fff;border:1px solid #e2e8f0;border-radius:14px;padding:24px;box-shadow:0 10px 30px rgba(15,23,42,.05)}
        h1{margin:0 0 12px}
        h2{margin:22px 0 10px;font-size:1.1rem}
        p,li{line-height:1.6}
        .muted{color:#475569}
        .nav{margin-bottom:14px;display:flex;gap:8px;flex-wrap:wrap}
        .nav a{display:inline-block;padding:8px 12px;border:1px solid #cbd5e1;border-radius:10px;text-decoration:none;color:#0f172a;font-weight:600}
        code{background:#f1f5f9;padding:2px 6px;border-radius:6px}
    </style>
</head>
<body>
<div class="wrap">
    <div class="nav">
        <a href="{{ url('/') }}">Inicio</a>
        <a href="{{ route('manual.main-server') }}">Manual servidor principal</a>
        <a href="{{ route('manual.branches') }}">Manual sedes</a>
    </div>
    <article class="card">
        @yield('content')
    </article>
</div>
</body>
</html>
