@extends('layouts.app')
@section('content')<div class="container">@include('admin.partials.nav')<h4>Nueva sucursal</h4><form method="POST" action="{{ route('branches.store') }}">@csrf @include('admin.branches.form')<button class="btn btn-primary">Guardar</button></form></div>@endsection
