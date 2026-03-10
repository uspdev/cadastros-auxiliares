@extends('layouts.app')

@section('content')
  <div class="card">
    <div class="card-header h5">Novo programa</div>
    <div class="card-body">
      <form action="{{ route('programas.store') }}" method="POST">
        @include('programas._form')
      </form>
    </div>
  </div>
@endsection
