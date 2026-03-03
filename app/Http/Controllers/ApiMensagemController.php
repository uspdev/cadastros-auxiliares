<?php

namespace App\Http\Controllers;

use App\Models\Mensagem;
use Illuminate\Http\Request;

class ApiMensagemController extends Controller
{
    public function index(Request $request)
    {
        $query = Mensagem::query();

        if ($request->filled('sistema')) {
            $sistema = mb_strtolower(trim($request->string('sistema')->toString()));
            $sistemaSemEspacos = str_replace(' ', '', $sistema);

            $query->where(function ($q) use ($sistema) {
                $sistemaSemEspacos = str_replace(' ', '', $sistema);

                $q->whereRaw('LOWER(TRIM(sistema)) = ?', [$sistema])
                    ->orWhereRaw('LOWER(TRIM(sistema)) = ?', ['geral'])
                    ->orWhereRaw('FIND_IN_SET(?, REPLACE(LOWER(sistema), " ", "")) > 0', [$sistemaSemEspacos]);
            });
        }

        if ($request->filled('publico')) {
            $publico = mb_strtolower(trim($request->string('publico')->toString()));
            $publico = str_replace('á', 'a', $publico);

            if (in_array($publico, ['1', 'true', 'sim', 'usuario', 'usuário'], true)) {
                $query->where('publico', true);
            } elseif (in_array($publico, ['0', 'false', 'nao', 'não', 'todos'], true)) {
                $query->where('publico', false);
            }
        }

        $filtrarAtivos = $request->has('ativos')
            ? $request->boolean('ativos')
            : true;

        if ($filtrarAtivos) {
            $query->where('ativo', true)
                ->where(function ($q) {
                    $q->whereNull('inicio_exibicao')
                        ->orWhereRaw('inicio_exibicao <= NOW()');
                })
                ->where(function ($q) {
                    $q->whereNull('fim_exibicao')
                        ->orWhereRaw('fim_exibicao >= NOW()');
                });
        }

        $limite = max(1, min($request->integer('limite', 20), 100));

        $mensagens = $query->orderByDesc('prioridade')
            ->orderByDesc('updated_at')
            ->limit($limite)
            ->get([
                'id',
                'titulo',
                'conteudo',
                'tipo',
                'ativo',
                'prioridade',
                'inicio_exibicao',
                'fim_exibicao',
                'sistema',
                'publico',
                'updated_at',
            ]);

        return response()->json($mensagens);
    }
}
