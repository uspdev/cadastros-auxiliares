@csrf

<div class="mb-3">
  <label for="titulo" class="form-label">Título</label>
  <input
    type="text"
    id="titulo"
    name="titulo"
    class="form-control @error('titulo') is-invalid @enderror"
    value="{{ old('titulo', $mensagem->titulo ?? '') }}"
    required
    maxlength="255"
  >
  @error('titulo')
    <div class="invalid-feedback">{{ $message }}</div>
  @enderror
</div>

<div class="mb-3">
  <label for="conteudo" class="form-label">Conteúdo</label>
  <textarea
    id="conteudo"
    name="conteudo"
    class="form-control @error('conteudo') is-invalid @enderror"
    rows="4"
    required
  >{{ old('conteudo', $mensagem->conteudo ?? '') }}</textarea>
  @error('conteudo')
    <div class="invalid-feedback">{{ $message }}</div>
  @enderror
</div>

<div class="row">
  <div class="col-md-3 mb-3">
    <label for="tipo" class="form-label">Tipo</label>
    <select id="tipo" name="tipo" class="form-select @error('tipo') is-invalid @enderror" required>
      @php $tipoAtual = old('tipo', $mensagem->tipo ?? 'info'); @endphp
      @foreach (['info' => 'Info', 'aviso' => 'Aviso', 'erro' => 'Erro', 'sucesso' => 'Sucesso'] as $valor => $rotulo)
        <option value="{{ $valor }}" @selected($tipoAtual === $valor)>{{ $rotulo }}</option>
      @endforeach
    </select>
    @error('tipo')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-md-3 mb-3">
    <label for="prioridade" class="form-label">Prioridade</label>
    <input
      type="number"
      id="prioridade"
      name="prioridade"
      class="form-control @error('prioridade') is-invalid @enderror"
      value="{{ old('prioridade', $mensagem->prioridade ?? 0) }}"
      min="0"
    >
    @error('prioridade')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-md-4 mb-3">
    <label for="sistema" class="form-label">Sistema</label>
    <input
      type="text"
      id="sistema"
      name="sistema"
      class="form-control @error('sistema') is-invalid @enderror"
      value="{{ old('sistema', $mensagem->sistema ?? 'geral') }}"
      placeholder="geral, cadastros-auxiliares, ponto, pessoas, chamados"
      required
      maxlength="255"
    >
    @error('sistema')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    <div class="form-text">Use <strong>geral</strong> para exibir em todos os sistemas (valor padrão). Exemplos: cadastros-auxiliares, ponto, pessoas, chamados.</div>
  </div>

  <div class="col-md-2 mb-3 d-flex align-items-end">
    <div class="form-check">
      <input
        class="form-check-input"
        type="checkbox"
        value="1"
        id="ativo"
        name="ativo"
        @checked(old('ativo', $mensagem->ativo ?? true))
      >
      <label class="form-check-label" for="ativo">
        Ativo
      </label>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-6 mb-3">
    <label for="inicio_exibicao" class="form-label">Início de exibição</label>
    <input
      type="datetime-local"
      id="inicio_exibicao"
      name="inicio_exibicao"
      class="form-control @error('inicio_exibicao') is-invalid @enderror"
      value="{{ old('inicio_exibicao', isset($mensagem->inicio_exibicao) ? $mensagem->inicio_exibicao->format('Y-m-d\TH:i') : '') }}"
    >
    @error('inicio_exibicao')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-md-6 mb-3">
    <label for="fim_exibicao" class="form-label">Fim de exibição</label>
    <input
      type="datetime-local"
      id="fim_exibicao"
      name="fim_exibicao"
      class="form-control @error('fim_exibicao') is-invalid @enderror"
      value="{{ old('fim_exibicao', isset($mensagem->fim_exibicao) ? $mensagem->fim_exibicao->format('Y-m-d\TH:i') : '') }}"
    >
    @error('fim_exibicao')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>
</div>

<div class="mb-3">
  <label for="publico_opcao" class="form-label">Público (visibilidade da mensagem)</label>
  @php
    $publicoAtual = old('publico_opcao');

    if ($publicoAtual === null) {
      if (is_bool($mensagem->publico ?? null)) {
        $publicoAtual = ($mensagem->publico ?? false) ? 'sim' : 'nao';
      } elseif (is_array($mensagem->publico ?? null)) {
        $publicoLista = collect($mensagem->publico)
          ->map(fn($item) => mb_strtolower(trim((string) $item)))
          ->all();

        $publicoAtual = in_array('usuário', $publicoLista, true) || in_array('usuario', $publicoLista, true)
          ? 'sim'
          : 'nao';
      } else {
        $publicoAtual = 'nao';
      }
    }
  @endphp

  <select id="publico_opcao" name="publico_opcao" class="form-select @error('publico_opcao') is-invalid @enderror" required>
    <option value="nao" @selected($publicoAtual === 'nao')>Não</option>
    <option value="sim" @selected($publicoAtual === 'sim')>Sim</option>
  </select>
  @error('publico_opcao')
    <div class="invalid-feedback">{{ $message }}</div>
  @enderror
  <div class="form-text">Sim: visível para todos (inclusive não logados). Não: visível somente para usuários logados.</div>
</div>

<button type="submit" class="btn btn-primary">Salvar</button>
<a href="{{ route('mensagens.index') }}" class="btn btn-secondary">Cancelar</a>
