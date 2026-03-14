@extends('layouts.app')
@section('content')<div class="container">@include('admin.partials.nav')<h4>Editar log</h4><form method="POST" action="{{ route('device-logs.update',$deviceLog) }}">@csrf @method('PUT') @include('admin.device_logs.form')<button class="btn btn-primary">Actualizar</button></form></div>@endsection
