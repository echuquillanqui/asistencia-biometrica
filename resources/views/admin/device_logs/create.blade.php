@extends('layouts.app')
@section('content')<div class="container">@include('admin.partials.nav')<h4>Nuevo log</h4><form method="POST" action="{{ route('device-logs.store') }}">@csrf @include('admin.device_logs.form')<button class="btn btn-primary">Guardar</button></form></div>@endsection
