@extends('layouts.app')

@section('content')
  <div class="card">
    <div class="card-header h5">Editar programa #{{ $programa->id }}</div>
    <div class="card-body">
      <div class="mb-3">
        <label class="form-label">Programa</label>
        <input type="text" class="form-control" value="{{ $nomePrograma ?? '-' }}" disabled>
      </div>

      <form action="{{ route('programas.update', $programa) }}" method="POST">
        @method('PUT')
        @include('programas._form')
      </form>
    </div>
  </div>
@endsection
