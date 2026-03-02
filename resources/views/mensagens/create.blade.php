@extends('layouts.app')

@section('content')
  <div class="card">
    <div class="card-header h5 mb-0">Nova mensagem</div>
    <div class="card-body">
      <form action="{{ route('mensagens.store') }}" method="POST">
        @include('mensagens._form')
      </form>
    </div>
  </div>
@endsection
