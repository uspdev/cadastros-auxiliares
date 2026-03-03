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
- `publico`: controle de visibilidade (binário):
  - `true` (`Sim`): exibe para todos (inclusive não logados);
  - `false` (`Não`): exibe somente para usuários logados.
- `created_at` e `updated_at`: auditoria básica de criação e atualização.

Regras implementadas:

- exibir somente mensagens com `ativo = true`;
- respeitar a janela entre `inicio_exibicao` e `fim_exibicao`;
- ordenar por `prioridade` e, em seguida, por data de atualização;
- permitir filtros por `sistema`, `publico`, `ativos` e `limite` via API;
- no cadastro web, a coluna `público` usa apenas `Sim/Não`.

Exemplo de payload (registro de mensagem):

```json
{
  "id": 42,
  "titulo": "Indisponibilidade programada",
  "conteudo": "O sistema ficará indisponível em 05/03/2026, das 22h às 23h30, para manutenção.",
  "tipo": "aviso",
  "ativo": true,
  "inicio_exibicao": "2026-03-03T11:00:00.000000Z",
  "fim_exibicao": "2026-03-06T02:30:00.000000Z",
  "prioridade": 10,
  "sistema": "cadastros-auxiliares,ponto",
  "publico": true,
  "updated_at": "2026-03-02T13:15:00.000000Z"
}
```

### Exemplo de endpoint para consumo

Observação de operação:

- O cadastro (criação, edição e exclusão) de mensagens é feito somente pela interface web em `/mensagens`.
- O CRUD web de mensagens é restrito a usuários com perfil/permissão `admin`.
- A API de mensagens é somente leitura e disponibiliza apenas endpoints `GET`.

Endpoints:

- `GET /api/mensagens`
- `GET /api/mensagens?limite=10`
- `GET /api/mensagens?sistema=cadastros-auxiliares&publico=true&ativos=true&limite=5`

Parâmetros de filtro (query string):

- `sistema`: restringe mensagens por sistema (ex.: `cadastros-auxiliares`).
- `publico`: filtra por público (aceita `true/false`, `1/0`, `sim/nao`, `usuario/todos`).
  - `true` / `sim`: mensagens públicas (todos);
  - `false` / `nao`: mensagens apenas para usuários logados.
- `ativos`: quando `true`, retorna apenas mensagens ativas na data/hora atual.
- `limite`: quantidade máxima de mensagens retornadas.

Exemplo de chamada:

```http
GET /api/mensagens?sistema=cadastros-auxiliares&publico=true&ativos=true&limite=5
```

Comportamento esperado:

- aplicar os filtros informados;
- considerar a vigência (`inicio_exibicao` e `fim_exibicao`) quando `ativos=true`;
- ordenar por `prioridade` (maior para menor) e depois por `updated_at` (mais recente primeiro).

### Visualização no laravel-usp-theme (opcional)

As mensagens podem ser exibidas no topo de **todas as páginas** da aplicação via `laravel-usp-theme`.

Configuração por `.env`:

- `CADASTROS_AUXILIARES_MENSAGENS_INTEGRACAO=false` (default).
- Quando `true`, usa `CADASTROS_AUXILIARES_MENSAGENS_ENDPOINT_URL` para buscar mensagens.

Exemplo para testes usando o próprio app:

```dotenv
CADASTROS_AUXILIARES_MENSAGENS_INTEGRACAO=true
CADASTROS_AUXILIARES_MENSAGENS_ENDPOINT_URL=
CADASTROS_AUXILIARES_SISTEMA_NAME=cadastros-auxiliares
CADASTROS_AUXILIARES_MENSAGENS_LIMITE=5
CADASTROS_AUXILIARES_MENSAGENS_TIMEOUT=5
CADASTROS_AUXILIARES_MENSAGENS_REFRESH=30
```

Significado:

- `CADASTROS_AUXILIARES_MENSAGENS_INTEGRACAO`: habilita/desabilita a integração.
- quando a variável não existir, estiver vazia ou for `false`, a integração fica desabilitada.
- `CADASTROS_AUXILIARES_MENSAGENS_ENDPOINT_URL`: endpoint `GET` do cadastros-auxiliares (ex.: `https://seu-app/api/mensagens`).
- `CADASTROS_AUXILIARES_SISTEMA_NAME`: nome do sistema consumidor para aplicar o filtro por sistema (ex.: `cadastros-auxiliares`, `ponto`).
- `CADASTROS_AUXILIARES_MENSAGENS_LIMITE`: quantidade máxima de mensagens consumidas.
- `CADASTROS_AUXILIARES_MENSAGENS_TIMEOUT`: tempo em segundos para cada mensagem desaparecer automaticamente.
- `CADASTROS_AUXILIARES_MENSAGENS_REFRESH`: intervalo (em segundos) para atualizar somente a área de mensagens sem precisar `F5`.

Comportamento de exibição no tema:

- O filtro por sistema só funciona quando `CADASTROS_AUXILIARES_SISTEMA_NAME` estiver configurada com o nome do sistema USPdev (ex.: `CADASTROS_AUXILIARES_SISTEMA_NAME=ponto` para o sistema `uspdev/ponto`).
- `CADASTROS_AUXILIARES_MENSAGENS_TIMEOUT` define por quantos segundos cada mensagem fica visível.
- Se `CADASTROS_AUXILIARES_MENSAGENS_TIMEOUT` estiver vazio ou `0`, as mensagens ficam visíveis até o usuário clicar em fechar.
- A área de mensagens é atualizada periodicamente sem recarregar a página, com intervalo definido por `CADASTROS_AUXILIARES_MENSAGENS_REFRESH`.
- Cada mensagem possui botão de fechar manual (`×`).
- Se o endpoint estiver indisponível, o comportamento é **silencioso** (sem erro na interface).

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
 