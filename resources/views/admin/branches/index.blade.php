@extends('layouts.app')
@section('content')
<div class="container" x-data="{q:''}">@include('admin.partials.nav')<div class="d-flex justify-content-between"><h4>Sucursales</h4><a href="{{ route('branches.create') }}" class="btn btn-primary">Nueva</a></div>
<input class="form-control my-2" x-model="q" placeholder="Filtrar">
<table class="table"><tr><th>Nombre</th><th>Ciudad</th><th></th></tr>@foreach($branches as $row)<tr x-show="'{{ strtolower($row->name.' '.$row->city) }}'.includes(q.toLowerCase())"><td>{{ $row->name }}</td><td>{{ $row->city }}</td><td><a href="{{ route('branches.edit',$row) }}" class="btn btn-sm btn-warning">Editar</a><form method="POST" action="{{ route('branches.destroy',$row) }}" class="d-inline" onsubmit="return confirm('¿Eliminar?')">@csrf @method('DELETE')<button class="btn btn-sm btn-danger">Eliminar</button></form></td></tr>@endforeach</table>{{ $branches->links() }}</div>
@endsection
