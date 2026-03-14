@extends('layouts.app')
@section('content')<div class="container">@include('admin.partials.nav')<h4>Editar empleado</h4><form method="POST" action="{{ route('employees.update',$employee) }}">@csrf @method('PUT') @include('admin.employees.form')<button class="btn btn-primary">Actualizar</button></form></div>@endsection
