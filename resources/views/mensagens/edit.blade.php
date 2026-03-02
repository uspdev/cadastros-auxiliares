@extends('layouts.app')

@section('content')
  <div class="card">
    <div class="card-header h5 mb-0">Editar mensagem #{{ $mensagem->id }}</div>
    <div class="card-body">
      <form action="{{ route('mensagens.update', $mensagem) }}" method="POST">
        @method('PUT')
        @include('mensagens._form')
      </form>
    </div>
  </div>
@endsection
