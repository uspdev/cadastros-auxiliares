@extends('layouts.app')

@section('content')
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <span class="h5 mb-0">Programas de Pós-Graduação</span>
      <a href="{{ route('programas.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus"></i> Novo programa
      </a>
    </div>
    <div class="card-body">
      @if($programas->isEmpty())
        <p class="mb-0">Nenhum programa cadastrado.</p>
      @else
        <div class="table-responsive">
          <table class="table table-striped datatable-simples">
            <thead>
              <tr>
                <th>ID</th>
                <th>Código</th>
                <th>Programa</th>
                <th>Sigla</th>
                <th>Atualizado</th>
                <th class="text-end">Ações</th>
              </tr>
            </thead>
            <tbody>
              @foreach($programas as $programa)
                <tr>
                  <td>{{ $programa->id }}</td>
                  <td>{{ $programa->codcur }}</td>
                  <td>{{ $nomesPorCodcur->get($programa->codcur) ?? '-' }}</td>
                  <td>{{ $programa->codslg }}</td>
                  <td>{{ $programa->updated_at?->format('d/m/Y H:i') }}</td>
                  <td class="text-end">
                    <a href="{{ route('programas.show', $programa) }}" class="btn btn-sm btn-outline-secondary">Ver</a>
                    <a href="{{ route('programas.edit', $programa) }}" class="btn btn-sm btn-outline-primary">Editar</a>
                    <form action="{{ route('programas.destroy', $programa) }}" method="POST" class="d-inline">
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

        {{ $programas->links() }}
      @endif
    </div>
  </div>
@endsection
