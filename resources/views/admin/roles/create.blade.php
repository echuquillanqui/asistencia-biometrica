@extends('layouts.app')
@section('content')<div class="container">@include('admin.partials.nav')<h4>Nuevo rol</h4><form method="POST" action="{{ route('roles.store') }}">@csrf @include('admin.roles.form')<button class="btn btn-primary">Guardar</button></form></div>@endsection
