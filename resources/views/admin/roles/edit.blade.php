@extends('layouts.app')
@section('content')<div class="container">@include('admin.partials.nav')<h4>Editar rol</h4><form method="POST" action="{{ route('roles.update',$role) }}">@csrf @method('PUT') @include('admin.roles.form')<button class="btn btn-primary">Actualizar</button></form></div>@endsection
