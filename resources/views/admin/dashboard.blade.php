@extends('layouts.app')
@section('content')
<div class="container" x-data="{showMonitor:true}">
    @include('admin.partials.nav')
    <h3>Dashboard Biométrico</h3>
    <div class="row g-3 mb-4">
        <div class="col-md-4"><div class="card"><div class="card-body">Total empleados: <strong>{{ $metrics['employees'] }}</strong></div></div></div>
        <div class="col-md-4"><div class="card"><div class="card-body">Total dispositivos: <strong>{{ $metrics['devices'] }}</strong></div></div></div>
        <div class="col-md-4"><div class="card"><div class="card-body">Asistencias hoy: <strong>{{ $metrics['today_attendance'] }}</strong></div></div></div>
        <div class="col-md-4"><div class="card"><div class="card-body">Dispositivos offline: <strong>{{ $metrics['offline_devices'] }}</strong></div></div></div>
        <div class="col-md-8"><div class="card"><div class="card-body">Última sincronización: <strong>{{ $metrics['last_sync'] ?? 'N/A' }}</strong></div></div></div>
    </div>
    <button class="btn btn-sm btn-secondary mb-2" @click="showMonitor=!showMonitor">Toggle monitor</button>
    <div class="card" x-show="showMonitor">
        <div class="card-header">Monitor de dispositivos</div>
        <div class="table-responsive">
            <table class="table mb-0"><thead><tr><th>Dispositivo</th><th>Sucursal</th><th>Estado</th><th>Últ. sync</th><th>Últ. log</th></tr></thead>
            <tbody>@foreach($metrics['devices_status'] as $device)<tr><td>{{ $device->name }}</td><td>{{ $device->branch->name ?? 'N/A' }}</td><td>{{ $device->status }}</td><td>{{ $device->last_sync }}</td><td>{{ optional($device->logs()->latest('log_time')->first())->log_time ?? 'N/A' }}</td></tr>@endforeach</tbody></table>
        </div>
    </div>
</div>
@endsection
