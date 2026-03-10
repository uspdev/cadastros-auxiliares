<?php

namespace Database\Seeders;

use App\Models\Programa;
use Illuminate\Database\Seeder;
use Uspdev\Replicado\Posgraduacao;

class ProgramaSeeder extends Seeder
{
    public function run(): void
    {
        $programasReplicado = Posgraduacao::listarProgramas();

        foreach ($programasReplicado as $programa) {
            $codcur = $this->valor($programa, 'codcur');

            if (!is_numeric($codcur)) {
                continue;
            }

            Programa::query()->firstOrCreate(
                ['codcur' => (int) $codcur],
                ['codslg' => 'PPG-' . (int) $codcur]
            );
        }
    }

    private function valor(array $dados, string $campo): mixed
    {
        if (array_key_exists($campo, $dados)) {
            return $dados[$campo];
        }

        $campoUpper = strtoupper($campo);
        if (array_key_exists($campoUpper, $dados)) {
            return $dados[$campoUpper];
        }

        return null;
    }
}
