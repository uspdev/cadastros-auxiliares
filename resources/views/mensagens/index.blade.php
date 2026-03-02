@extends('layouts.app')

@section('content')
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <span class="h5 mb-0">Mensagens</span>
      <a href="{{ route('mensagens.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus"></i> Nova mensagem
      </a>
    </div>
    <div class="card-body">
      @if($mensagens->isEmpty())
        <p class="mb-0">Nenhuma mensagem cadastrada.</p>
      @else
        <div class="table-responsive">
          <table class="table table-striped datatable-simples">
            <thead>
              <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Tipo</th>
                <th>Sistema</th>
                <th>Público</th>
                <th>Prioridade</th>
                <th>Ativo</th>
                <th>Atualizado</th>
                <th class="text-end">Ações</th>
              </tr>
            </thead>
            <tbody>
              @foreach($mensagens as $mensagem)
                <tr>
                  <td>{{ $mensagem->id }}</td>
                  <td>{{ $mensagem->titulo }}</td>
                  <td>{{ $mensagem->tipo }}</td>
                  <td>{{ $mensagem->sistema }}</td>
                  <td>{{ $mensagem->publico ? 'Sim' : 'Não' }}</td>
                  <td>{{ $mensagem->prioridade }}</td>
                  <td>{{ $mensagem->ativo ? 'Sim' : 'Não' }}</td>
                  <td>{{ $mensagem->updated_at?->format('d/m/Y H:i') }}</td>
                  <td class="text-end">
                    <a href="{{ route('mensagens.edit', $mensagem) }}" class="btn btn-sm btn-outline-primary">Editar</a>
                    <form action="{{ route('mensagens.destroy', $mensagem) }}" method="POST" class="d-inline">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Confirma a exclusão?')">
                        Excluir
                      </button>
                    </form>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        {{ $mensagens->links() }}
      @endif
    </div>
  </div>
@endsection
