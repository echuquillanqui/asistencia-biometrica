@extends('layouts.app')

@section('content')
<div class="container py-2" x-data="{showMonitor:true}">
    @include('admin.partials.nav')

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <div>
            <h3 class="mb-1">Dashboard Biométrico</h3>
            <p class="text-muted mb-0">Vista general de personal, dispositivos y estado de sincronización.</p>
        </div>
        <button class="btn btn-sm btn-outline-secondary" @click="showMonitor=!showMonitor">
            <span x-text="showMonitor ? 'Ocultar monitor' : 'Mostrar monitor'"></span>
        </button>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Empleados activos</div>
                    <div class="display-6 fw-bold">{{ $metrics['employees'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Dispositivos registrados</div>
                    <div class="display-6 fw-bold">{{ $metrics['devices'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Asistencias de hoy</div>
                    <div class="display-6 fw-bold">{{ $metrics['today_attendance'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100 {{ $metrics['offline_devices'] > 0 ? 'bg-warning-subtle' : 'bg-success-subtle' }}">
                <div class="card-body">
                    <div class="text-muted small">Dispositivos offline</div>
                    <div class="display-6 fw-bold">{{ $metrics['offline_devices'] }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="alert {{ $metrics['offline_devices'] > 0 ? 'alert-warning' : 'alert-success' }} d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <strong>Última sincronización global:</strong> {{ $metrics['last_sync'] ?? 'N/A' }}
        </div>
        @if($metrics['offline_devices'] > 0)
            <span class="badge text-bg-warning">Atención: hay equipos pendientes por revisar</span>
        @else
            <span class="badge text-bg-success">Todos los equipos reportando correctamente</span>
        @endif
    </div>

    <div class="card border-0 shadow-sm" x-show="showMonitor">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <strong>Monitor de dispositivos</strong>
            <small class="text-muted">Estado en tiempo real por última actividad</small>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Dispositivo</th>
                        <th>Sucursal</th>
                        <th>Estado</th>
                        <th>Última sincronización</th>
                        <th>Último log</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($metrics['devices_status'] as $device)
                        @php
                            $isOnline = $device->status === 'online';
                        @endphp
                        <tr>
                            <td class="fw-semibold">{{ $device->name }}</td>
                            <td>{{ $device->branch->name ?? 'N/A' }}</td>
                            <td>
                                <span class="badge {{ $isOnline ? 'text-bg-success' : 'text-bg-secondary' }}">
                                    {{ strtoupper($device->status) }}
                                </span>
                            </td>
                            <td>{{ $device->last_sync ?? 'N/A' }}</td>
                            <td>{{ optional($device->logs()->latest('log_time')->first())->log_time ?? 'N/A' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Aún no hay dispositivos registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
