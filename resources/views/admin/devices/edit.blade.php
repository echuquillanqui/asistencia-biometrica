@extends('layouts.app')
@section('content')<div class="container">@include('admin.partials.nav')<h4>Editar dispositivo</h4><form method="POST" action="{{ route('devices.update',$device) }}">@csrf @method('PUT') @include('admin.devices.form')<button class="btn btn-primary">Actualizar</button></form></div>@endsection
