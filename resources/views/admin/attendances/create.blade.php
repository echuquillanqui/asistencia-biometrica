@extends('layouts.app')
@section('content')<div class="container">@include('admin.partials.nav')<h4>Nueva asistencia</h4><form method="POST" action="{{ route('attendances.store') }}">@csrf @include('admin.attendances.form')<button class="btn btn-primary">Guardar</button></form></div>@endsection
