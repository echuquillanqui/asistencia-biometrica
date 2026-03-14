@extends('layouts.app')
@section('content')<div class="container">@include('admin.partials.nav')<h4>Nuevo empleado</h4><form method="POST" action="{{ route('employees.store') }}">@csrf @include('admin.employees.form')<button class="btn btn-primary">Guardar</button></form></div>@endsection
