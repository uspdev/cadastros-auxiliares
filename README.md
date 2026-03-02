# Cadastros auxiliares

Serviço para manter e disponibilizar cadastros complementares que não estão cobertos (ou não estão suficientemente atualizados) nos sistemas corporativos da USP.

## Objetivo

Centralizar dados auxiliares usados por sistemas locais, permitindo:

- sincronizar informações vindas do replicado;
- ajustar ou complementar dados manualmente quando necessário;
- disponibilizar esses dados por endpoints JSON para consumo por outras aplicações.

## Funcionalidades

- **Importação de dados**: popula cadastros a partir do replicado.
- **Manutenção de cadastros**: permite criar, editar e corrigir registros usados localmente.
- **Exposição via API**: fornece endpoints JSON para integração com outros sistemas.
- **Padronização de referência**: mantém uma base única para múltiplos apps locais.

## Cadastros disponíveis

- **Estrutura**
  - **Espaços**
    - Motivo: apesar de existir no replicado, em muitos casos a sala não está cadastrada ou está com nome incorreto.

- **Graduação**
  - **Cursos e habilitações por departamento de ensino**
    - Motivo: há cenários em que o aluno de graduação precisa ser associado ao departamento de ensino.
    - Exemplo: somente alunos de graduação do departamento de Relações Públicas podem retirar equipamentos.

- **Pós-Graduação**
  - **Programas**
    - Motivo: o replicado não traz sigla e outras informações de rotatividade utilizadas pelos programas.

- **Gerais**
  - **Feriados**
    - Motivo: embora exista no replicado, o acompanhamento por decretos municipais pode exigir atualização mais rápida.

- **Mensagens (sistemas locais)**
  - **Mensagens institucionais e operacionais exibidas nos sistemas locais**
    - Motivo: permitir comunicação rápida com usuários sem depender de deploy de código.
    - Exemplos de uso:
      - aviso de indisponibilidade programada;
      - mensagem de manutenção emergencial;
      - comunicado de prazo, orientação ou regra temporária.
    - Uso esperado: cada sistema local consulta esse cadastro e exibe as mensagens ativas conforme contexto/regras do próprio sistema.

### Modelo sugerido para cadastro de mensagens

Campos recomendados:

- `id`: identificador único da mensagem.
- `titulo`: título curto para exibição.
- `conteudo`: texto principal da mensagem.
- `tipo`: categoria visual/semântica (ex.: `info`, `aviso`, `erro`, `sucesso`).
- `ativo`: indica se a mensagem está habilitada para exibição.
- `inicio_exibicao`: data/hora inicial de vigência.
- `fim_exibicao`: data/hora final de vigência.
- `prioridade`: ordem de destaque quando houver múltiplas mensagens.
- `sistema`: sistema-alvo da mensagem (ou `geral` para todos).
- `publico`: segmentação opcional (ex.: servidor, docente, discente, administrador).
- `created_at` e `updated_at`: auditoria básica de criação e atualização.

Regras mínimas sugeridas:

- exibir somente mensagens com `ativo = true`;
- respeitar a janela entre `inicio_exibicao` e `fim_exibicao`;
- ordenar por `prioridade` e, em seguida, por data de atualização;
- permitir que cada sistema aplique filtros próprios por `sistema` e `publico`.

Exemplo de payload (registro de mensagem):

```json
{
  "id": 42,
  "titulo": "Indisponibilidade programada",
  "conteudo": "O sistema ficará indisponível em 05/03/2026, das 22h às 23h30, para manutenção.",
  "tipo": "aviso",
  "ativo": true,
  "inicio_exibicao": "2026-03-03T08:00:00-03:00",
  "fim_exibicao": "2026-03-05T23:30:00-03:00",
  "prioridade": 10,
  "sistema": "empresta",
  "publico": ["Servidor", "Docente"],
  "created_at": "2026-03-02T10:15:00-03:00",
  "updated_at": "2026-03-02T10:15:00-03:00"
}
```

Exemplo de resposta para consumo por app:

```json
[
  {
    "id": 42,
    "titulo": "Indisponibilidade programada",
    "conteudo": "O sistema ficará indisponível em 05/03/2026, das 22h às 23h30, para manutenção.",
    "tipo": "aviso",
    "prioridade": 10,
    "inicio_exibicao": "2026-03-03T08:00:00-03:00",
    "fim_exibicao": "2026-03-05T23:30:00-03:00"
  }
]
```

### Exemplo de endpoint para consumo

Endpoint sugerido:

- `GET /api/mensagens`

Parâmetros de filtro (query string):

- `sistema`: restringe mensagens por sistema (ex.: `empresta`).
- `publico`: filtra por público-alvo (ex.: `Docente`, `Servidor`).
- `ativos`: quando `true`, retorna apenas mensagens ativas na data/hora atual.
- `limite`: quantidade máxima de mensagens retornadas.

Exemplo de chamada:

```http
GET /api/mensagens?sistema=empresta&publico=Docente&ativos=true&limite=5
```

Comportamento esperado:

- aplicar os filtros informados;
- considerar a vigência (`inicio_exibicao` e `fim_exibicao`) quando `ativos=true`;
- ordenar por `prioridade` (maior para menor) e depois por `updated_at` (mais recente primeiro).

## Pontos a evoluir

- autenticação/autorização por endpoint e por aplicação (tokens);
- tornar o consumo pelos apps opcional e configurável;
- definir periodicidade e estratégia de atualização a partir do replicado.

## Sistemas que utilizam

- uspdev/web-ldap-admin (espaço)
- uspdev/empresta (cursos e habilitações)
- uspdev/laravel-usp-theme (mensagens)

## Contribuições

Este sistema está em início de desenvolvimento, e contribuições são muito bem-vindas neste momento.

Se você identificou um problema, tem uma sugestão de melhoria ou quer incluir um novo cadastro auxiliar, abra uma issue para discutirmos a proposta.

Pull requests com correções de documentação, ajustes de comportamento e novas funcionalidades também são incentivados.
 