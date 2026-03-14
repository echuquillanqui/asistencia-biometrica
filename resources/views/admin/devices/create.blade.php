@extends('layouts.app')
@section('content')<div class="container">@include('admin.partials.nav')<h4>Nuevo dispositivo</h4><form method="POST" action="{{ route('devices.store') }}">@csrf @include('admin.devices.form')<button class="btn btn-primary">Guardar</button></form></div>@endsection
