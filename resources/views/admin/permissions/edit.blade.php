@extends('layouts.app')
@section('content')<div class="container">@include('admin.partials.nav')<h4>Editar permiso</h4><form method="POST" action="{{ route('permissions.update',$permission) }}">@csrf @method('PUT') @include('admin.permissions.form')<button class="btn btn-primary">Actualizar</button></form></div>@endsection
