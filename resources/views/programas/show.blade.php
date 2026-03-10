@extends('layouts.app')

@section('content')
  <div class="card">
    <div class="card-header h5">Programa #{{ $programa->id }}</div>
    <div class="card-body">
      <dl class="row mb-0">
        <dt class="col-sm-2">Código</dt>
        <dd class="col-sm-10">{{ $programa->codcur }}</dd>

        <dt class="col-sm-2">Programa</dt>
        <dd class="col-sm-10">{{ $nomePrograma ?? '-' }}</dd>

        <dt class="col-sm-2">Sigla</dt>
        <dd class="col-sm-10">{{ $programa->codslg }}</dd>
      </dl>

      <div class="mt-3">
        <a href="{{ route('programas.edit', $programa) }}" class="btn btn-primary">Editar</a>
        <a href="{{ route('programas.index') }}" class="btn btn-secondary">Voltar</a>
      </div>
    </div>
  </div>
@endsection
