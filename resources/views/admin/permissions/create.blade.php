@extends('layouts.app')
@section('content')<div class="container">@include('admin.partials.nav')<h4>Nuevo permiso</h4><form method="POST" action="{{ route('permissions.store') }}">@csrf @include('admin.permissions.form')<button class="btn btn-primary">Guardar</button></form></div>@endsection
