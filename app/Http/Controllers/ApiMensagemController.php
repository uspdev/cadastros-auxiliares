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
            $sistema = $request->string('sistema')->toString();
            $query->where(function ($q) use ($sistema) {
                $q->where('sistema', $sistema)
                    ->orWhere('sistema', 'geral');
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

        if ($request->boolean('ativos')) {
            $now = now();
            $query->where('ativo', true)
                ->where(function ($q) use ($now) {
                    $q->whereNull('inicio_exibicao')
                        ->orWhere('inicio_exibicao', '<=', $now);
                })
                ->where(function ($q) use ($now) {
                    $q->whereNull('fim_exibicao')
                        ->orWhere('fim_exibicao', '>=', $now);
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
