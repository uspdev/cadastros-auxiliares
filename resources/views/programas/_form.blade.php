@csrf

<div class="mb-3">
  <label for="codcur" class="form-label">Código</label>
  @if(isset($programa))
    <input
      type="text"
      id="codcur"
      class="form-control"
      value="{{ $programa->codcur }}"
      disabled
    >
  @else
    <input
      type="number"
      id="codcur"
      name="codcur"
      class="form-control @error('codcur') is-invalid @enderror"
      value="{{ old('codcur', $programa->codcur ?? '') }}"
      required
      min="1"
    >
  @endif
  @error('codcur')
    <div class="invalid-feedback">{{ $message }}</div>
  @enderror
  <div class="form-text">Código do programa no replicado.</div>
</div>

<div class="mb-3">
  <label for="codslg" class="form-label">Sigla</label>
  <input
    type="text"
    id="codslg"
    name="codslg"
    class="form-control @error('codslg') is-invalid @enderror"
    value="{{ old('codslg', $programa->codslg ?? '') }}"
    required
    maxlength="30"
  >
  @error('codslg')
    <div class="invalid-feedback">{{ $message }}</div>
  @enderror
  <div class="form-text">Sigla do programa usada pelos sistemas locais (ex.: PPG-XXXX).</div>
</div>

<button type="submit" class="btn btn-primary">Salvar</button>
<a href="{{ route('programas.index') }}" class="btn btn-secondary">Cancelar</a>
