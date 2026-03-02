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
      required
      maxlength="255"
    >
    @error('sistema')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
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
  <label for="publico" class="form-label">Público (separado por vírgula)</label>
  <input
    type="text"
    id="publico"
    name="publico_texto"
    class="form-control"
    value="{{ old('publico_texto', isset($mensagem->publico) && is_array($mensagem->publico) ? implode(', ', $mensagem->publico) : '') }}"
    placeholder="Servidor, Docente"
  >
  <div class="form-text">Ex.: Servidor, Docente, Discente, Administrador</div>
</div>

<button type="submit" class="btn btn-primary">Salvar</button>
<a href="{{ route('mensagens.index') }}" class="btn btn-secondary">Cancelar</a>
