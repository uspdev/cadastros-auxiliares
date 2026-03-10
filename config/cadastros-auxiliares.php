<?php

$cadastrosAuxiliaresUrl = rtrim((string) env('CADASTROS_AUXILIARES_URL', ''), '/');
$mensagensEndpointUrl = $cadastrosAuxiliaresUrl !== ''
    ? $cadastrosAuxiliaresUrl . '/api/mensagens'
    : '';

return [
    'password' => env('CADASTROS_AUXILIARES_PASSWORD', ''),

    'mensagens' => [
        'integracao' => env('CADASTROS_AUXILIARES_MENSAGENS_INTEGRACAO', false),
        'endpoint_url' => env('CADASTROS_AUXILIARES_MENSAGENS_ENDPOINT_URL', $mensagensEndpointUrl),
        'sistema' => env('CADASTROS_AUXILIARES_SISTEMA_NAME', ''),
        'limite' => (int) env('CADASTROS_AUXILIARES_MENSAGENS_LIMITE', 5),
        'timeout' => env('CADASTROS_AUXILIARES_MENSAGENS_TIMEOUT', 5),
        'refresh' => (int) env('CADASTROS_AUXILIARES_MENSAGENS_REFRESH', 30),
    ],
];
