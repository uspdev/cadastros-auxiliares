<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMensagemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'titulo' => ['required', 'string', 'max:255'],
            'conteudo' => ['required', 'string'],
            'tipo' => ['required', 'string', 'max:20'],
            'ativo' => ['nullable', 'boolean'],
            'inicio_exibicao' => ['nullable', 'date'],
            'fim_exibicao' => ['nullable', 'date', 'after_or_equal:inicio_exibicao'],
            'prioridade' => ['nullable', 'integer', 'min:0'],
            'sistema' => ['required', 'string', 'max:255'],
            'publico_opcao' => ['required', 'in:sim,nao'],
            'publico' => ['required', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $publicoOpcao = $this->input('publico_opcao', 'nao');

        $this->merge([
            'publico' => $publicoOpcao === 'sim',
        ]);
    }
}
