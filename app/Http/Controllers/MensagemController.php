<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMensagemRequest;
use App\Http\Requests\UpdateMensagemRequest;
use App\Models\Mensagem;

class MensagemController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'can:admin']);
    }

    public function index()
    {
        $mensagens = Mensagem::orderByDesc('prioridade')
            ->orderByDesc('updated_at')
            ->paginate(20);

        return view('mensagens.index', compact('mensagens'));
    }

    public function create()
    {
        return view('mensagens.create');
    }

    public function store(StoreMensagemRequest $request)
    {
        $data = $request->validated();
        $data['ativo'] = $request->boolean('ativo');
        unset($data['publico_opcao']);

        Mensagem::create($data);

        return redirect()
            ->route('mensagens.index')
            ->with('success', 'Mensagem cadastrada com sucesso.');
    }

    public function edit(Mensagem $mensagem)
    {
        return view('mensagens.edit', compact('mensagem'));
    }

    public function update(UpdateMensagemRequest $request, Mensagem $mensagem)
    {
        $data = $request->validated();
        $data['ativo'] = $request->boolean('ativo');
        unset($data['publico_opcao']);

        $mensagem->update($data);

        return redirect()
            ->route('mensagens.index')
            ->with('success', 'Mensagem atualizada com sucesso.');
    }

    public function destroy(Mensagem $mensagem)
    {
        $mensagem->delete();

        return redirect()
            ->route('mensagens.index')
            ->with('success', 'Mensagem removida com sucesso.');
    }
}
