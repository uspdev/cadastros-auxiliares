<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class UpdateProgramaRequest extends StoreProgramaRequest
{
    public function rules(): array
    {
        return [
            'codcur' => ['prohibited'],
            'codslg' => [
                'required',
                'string',
                'max:30',
                Rule::unique('programas', 'codslg')->ignore($this->route('programa')?->id),
            ],
        ];
    }
}
