<?php

namespace App\Services;

use Uspdev\Replicado\Posgraduacao;

class ReplicadoPosgraduacaoService
{
    public function listarProgramas(): array
    {
        return Posgraduacao::listarProgramas();
    }

    public function obterPrograma(int $codcur): ?array
    {
        $programa = Posgraduacao::obterPrograma($codcur);

        return is_array($programa) ? $programa : null;
    }
}
