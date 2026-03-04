<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mensagem extends Model
{
    use HasFactory;

    protected $table = 'mensagens';

    protected $fillable = [
        'titulo',
        'conteudo',
        'tipo',
        'ativo',
        'inicio_exibicao',
        'fim_exibicao',
        'prioridade',
        'sistema',
        'publico',
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'inicio_exibicao' => 'datetime',
        'fim_exibicao' => 'datetime',
        'publico' => 'array',
    ];
}
