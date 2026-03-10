<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProgramaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'codcur' => ['required', 'integer', 'min:1', 'unique:programas,codcur'],
            'codslg' => ['required', 'string', 'max:30', 'unique:programas,codslg'],
        ];
    }
}
