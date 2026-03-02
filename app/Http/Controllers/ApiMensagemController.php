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
            $publicos = collect(explode(',', $request->string('publico')->toString()))
                ->map(fn ($item) => trim($item))
                ->filter()
                ->values();

            if ($publicos->isNotEmpty()) {
                $query->where(function ($q) use ($publicos) {
                    foreach ($publicos as $publico) {
                        $q->orWhereJsonContains('publico', $publico);
                    }
                });
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
