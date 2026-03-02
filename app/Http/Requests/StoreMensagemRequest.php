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
            'publico_texto' => ['nullable', 'string'],
            'publico' => ['nullable', 'array'],
            'publico.*' => ['string', 'max:100'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('publico_texto')) {
            $publico = collect(explode(',', (string) $this->input('publico_texto')))
                ->map(fn ($item) => trim($item))
                ->filter()
                ->values()
                ->all();

            $this->merge([
                'publico' => empty($publico) ? null : $publico,
            ]);
        }
    }
}
