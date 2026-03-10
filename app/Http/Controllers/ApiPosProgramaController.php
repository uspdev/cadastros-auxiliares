<?php

namespace App\Http\Controllers;

use App\Models\Programa;
use App\Services\ReplicadoPosgraduacaoService;
use Illuminate\Http\JsonResponse;

class ApiPosProgramaController extends Controller
{
    public function __construct(private ReplicadoPosgraduacaoService $replicadoPosgraduacao)
    {
    }

    public function index(): JsonResponse
    {
        $nomesPorCodcur = collect($this->replicadoPosgraduacao->listarProgramas())
            ->mapWithKeys(function (array $programa): array {
                $codcur = $this->valorPrograma($programa, 'codcur');

                if ($codcur === null) {
                    return [];
                }

                return [(string) $codcur => $this->valorPrograma($programa, 'nomcur')];
            });

        $programas = Programa::query()
            ->orderBy('codslg')
            ->get()
            ->map(function (Programa $programa) use ($nomesPorCodcur): array {
                return [
                    'id' => $programa->id,
                    'codcur' => $programa->codcur,
                    'nomcur' => $nomesPorCodcur->get((string) $programa->codcur),
                    'codslg' => $programa->codslg,
                ];
            })
            ->values();

        return response()->json($programas);
    }

    public function show(int $codcur): JsonResponse
    {
        $programaCadastrado = Programa::query()
            ->where('codcur', $codcur)
            ->firstOrFail();

        $programaReplicado = $this->replicadoPosgraduacao->obterPrograma($codcur);

        if ($programaReplicado === null) {
            return response()->json([
                'message' => 'Programa não encontrado no replicado.',
            ], 404);
        }

        return response()->json([
            'id' => $programaCadastrado->id,
            'codcur' => $programaCadastrado->codcur,
            'nomcur' => $this->valorPrograma($programaReplicado, 'nomcur'),
            'codslg' => $programaCadastrado->codslg,
        ]);
    }

    private function valorPrograma(array $programa, string $campo): mixed
    {
        if (array_key_exists($campo, $programa)) {
            return $programa[$campo];
        }

        $campoUpper = strtoupper($campo);
        if (array_key_exists($campoUpper, $programa)) {
            return $programa[$campoUpper];
        }

        return null;
    }
}
