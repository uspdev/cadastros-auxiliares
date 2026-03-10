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
    - Documentação dos endpoints: [docs/programas.md](docs/programas.md).

- **Gerais**
  - **Feriados**
    - Motivo: embora exista no replicado, o acompanhamento por decretos municipais pode exigir atualização mais rápida.

- **Mensagens (sistemas locais)**
  - **Mensagens institucionais e operacionais exibidas nos sistemas locais**.
  - Em integrações com `laravel-usp-theme`, o consumo é feito via proxy local do tema (`/_usp-theme/cadastros-auxiliares/mensagens`), mantendo a senha no backend.
  - Consumo via pacote `uspdev/cadastros-auxiliares-client` nos sistemas locais.
  - Documentação completa: [docs/mensagens.md](docs/mensagens.md).

## Seeder de programas de pós-graduação

Para pré-popular a tabela local `programas` com os dados do replicado (`Posgraduacao::listarProgramas()`), execute:

```bash
php artisan migrate
php artisan db:seed --class=ProgramaSeeder
```

Comportamento do seeder:

- cria registros novos com `codcur` e `codslg`;
- define `codslg` inicial no formato `PPG-<codcur>`;
- não duplica dados em reexecuções (`firstOrCreate`);
- não sobrescreve `codslg` já ajustado manualmente.

## Pontos a evoluir

- autenticação/autorização por endpoint e por aplicação (tokens);
- tornar o consumo pelos apps opcional e configurável;
- definir periodicidade e estratégia de atualização a partir do replicado.

## Sistemas ou bibliotecas que utilizam

- Sistema uspdev/web-ldap-admin (espaço)
- Sistema uspdev/empresta (cursos e habilitações)
- Biblioteca uspdev/laravel-usp-theme (mensagens)

## Contribuições

Este sistema está em início de desenvolvimento, e contribuições são muito bem-vindas neste momento.

Se você identificou um problema, tem uma sugestão de melhoria ou quer incluir um novo cadastro auxiliar, abra uma issue para discutirmos a proposta.

Pull requests com correções de documentação, ajustes de comportamento e novas funcionalidades também são incentivados.
 
