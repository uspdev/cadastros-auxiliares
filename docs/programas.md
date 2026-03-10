# Programas de Pós-Graduação

Cadastro auxiliar de programas para complementar dados do replicado.

Uso esperado:

- `codcur` e `nomcur` vêm do replicado;
- `codslg` é mantido localmente para uso dos sistemas consumidores.

## Endpoints de consumo

A API é somente leitura para programas e expõe:

- `GET /api/pos/programas`
- `GET /api/pos/programas/{codcur}`

Os endpoints usam o middleware `api.password`, com o mesmo comportamento de autenticação do endpoint de mensagens:

- permite acesso com senha via `password` (query) ou header `X-Cadastros-Auxiliares-Password`;
- permite acesso sem senha para usuário web autenticado;
- permite requisição same-origin.

## Retorno

### `GET /api/pos/programas`

Retorna lista dos programas cadastrados localmente com dados complementados do replicado:

- `id`: identificador local;
- `codcur`: código do programa no replicado;
- `nomcur`: nome do programa no replicado;
- `codslg`: sigla local do programa.

Exemplo:

```json
[
  {
    "id": 1,
    "codcur": 1001,
    "nomcur": "Programa Alfa",
    "codslg": "PPG-ALFA"
  },
  {
    "id": 2,
    "codcur": 1002,
    "nomcur": "Programa Beta",
    "codslg": "PPG-BETA"
  }
]
```

### `GET /api/pos/programas/{codcur}`

Retorna um programa específico pelo `codcur`:

- `id`: identificador local;
- `codcur`: código do programa no replicado;
- `nomcur`: nome do programa no replicado;
- `codslg`: sigla local do programa.

Exemplo:

```json
{
  "id": 1,
  "codcur": 1001,
  "nomcur": "Programa Alfa",
  "codslg": "PPG-ALFA"
}
```

## Possíveis respostas de erro

- `404`: quando `codcur` não está cadastrado localmente em `programas`;
- `404`: quando o `codcur` local não é encontrado no replicado.

