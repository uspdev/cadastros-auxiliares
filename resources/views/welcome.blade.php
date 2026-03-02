@extends('layouts.app')

@section('content')
  <div class="card">
    <div class="card-header h4">
      Cadastros auxiliares
    </div>
    <div class="card-body">
      <p>Serviço para manter e disponibilizar cadastros complementares que não estão cobertos (ou não estão suficientemente atualizados) nos sistemas corporativos da USP.</p>
      <p><strong>Objetivo:</strong></p>
        <p>Centralizar dados auxiliares usados por sistemas locais, permitindo:</p>
        <ul>
            <li>sincronizar informações vindas do replicado;</li>
            <li>ajustar ou complementar dados manualmente quando necessário;</li>
            <li>disponibilizar esses dados por endpoints JSON para consumo por outras aplicações.</li>
        </ul>
    </div>
  </div>
@endsection
