@extends('layouts.app')
@section('content')<div class="container">@include('admin.partials.nav')<h4>Editar sucursal</h4><form method="POST" action="{{ route('branches.update',$branch) }}">@csrf @method('PUT') @include('admin.branches.form')<button class="btn btn-primary">Actualizar</button></form></div>@endsection
