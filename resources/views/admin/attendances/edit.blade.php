@extends('layouts.app')
@section('content')<div class="container">@include('admin.partials.nav')<h4>Editar asistencia</h4><form method="POST" action="{{ route('attendances.update',$attendance) }}">@csrf @method('PUT') @include('admin.attendances.form')<button class="btn btn-primary">Actualizar</button></form></div>@endsection
