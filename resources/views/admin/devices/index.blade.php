@extends('layouts.app')

@section('content')
<div class="container py-2">
    @include('admin.partials.nav')

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <div>
            <h4 class="mb-1">Dispositivos</h4>
            <p class="text-muted mb-0">Registra tus equipos biométricos y valida su conexión con el servidor principal.</p>
        </div>
        <a href="{{ route('devices.create') }}" class="btn btn-primary">+ Nuevo dispositivo</a>
    </div>

    <div class="alert alert-info" role="alert">
        <strong>Prueba de conexión recomendada:</strong> usa el endpoint
        <code>POST /api/device/heartbeat</code> con cabecera <code>X-DEVICE-API-KEY</code>.
        Si responde correctamente, verás el estado en el dashboard.
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nombre</th>
                        <th>Serial</th>
                        <th>Estado</th>
                        <th>API Key</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($devices as $row)
                        <tr>
                            <td class="fw-semibold">{{ $row->name }}</td>
                            <td>{{ $row->serial_number }}</td>
                            <td>
                                <span class="badge {{ $row->status === 'online' ? 'text-bg-success' : 'text-bg-secondary' }}">
                                    {{ strtoupper($row->status) }}
                                </span>
                            </td>
                            <td><code>{{ $row->api_key }}</code></td>
                            <td class="text-end">
                                <a href="{{ route('devices.edit',$row) }}" class="btn btn-sm btn-outline-warning">Editar</a>
                                <form method="POST" action="{{ route('devices.destroy',$row) }}" class="d-inline" onsubmit="return confirm('¿Eliminar?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">No hay dispositivos registrados todavía.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $devices->links() }}
    </div>
</div>
@endsection
