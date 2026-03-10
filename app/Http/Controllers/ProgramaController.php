<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProgramaRequest;
use App\Http\Requests\UpdateProgramaRequest;
use App\Models\Programa;
use App\Services\ReplicadoPosgraduacaoService;

class ProgramaController extends Controller
{
    public function __construct(private ReplicadoPosgraduacaoService $replicadoPosgraduacao)
    {
        $this->middleware(['auth', 'can:admin']);
    }

    public function index()
    {
        $nomesPorCodcur = collect($this->replicadoPosgraduacao->listarProgramas())
            ->mapWithKeys(function (array $programa): array {
                $codcur = $this->valorPrograma($programa, 'codcur');

                if ($codcur === null) {
                    return [];
                }

                return [(int) $codcur => $this->valorPrograma($programa, 'nomcur')];
            });

        $programas = Programa::query()
            ->orderBy('codslg')
            ->paginate(20);

        return view('programas.index', compact('programas', 'nomesPorCodcur'));
    }

    public function create()
    {
        return view('programas.create');
    }

    public function store(StoreProgramaRequest $request)
    {
        Programa::query()->create($request->validated());

        return redirect()
            ->route('programas.index')
            ->with('success', 'Programa cadastrado com sucesso.');
    }

    public function edit(Programa $programa)
    {
        $nomePrograma = $this->obterNomePrograma($programa->codcur);

        return view('programas.edit', compact('programa', 'nomePrograma'));
    }

    public function show(Programa $programa)
    {
        $nomePrograma = $this->obterNomePrograma($programa->codcur);

        return view('programas.show', compact('programa', 'nomePrograma'));
    }

    public function update(UpdateProgramaRequest $request, Programa $programa)
    {
        $programa->update($request->validated());

        return redirect()
            ->route('programas.index')
            ->with('success', 'Programa atualizado com sucesso.');
    }

    public function destroy(Programa $programa)
    {
        $programa->delete();

        return redirect()
            ->route('programas.index')
            ->with('success', 'Programa removido com sucesso.');
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

    private function obterNomePrograma(int $codcur): ?string
    {
        $programaReplicado = $this->replicadoPosgraduacao->obterPrograma($codcur);

        if (!is_array($programaReplicado)) {
            return null;
        }

        $nomePrograma = $this->valorPrograma($programaReplicado, 'nomcur');

        return is_string($nomePrograma) ? $nomePrograma : null;
    }
}
