<?php

namespace Tests\Feature;

use App\Models\Mensagem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiMensagensTest extends TestCase
{
    use RefreshDatabase;

    public function test_lista_mensagens_ativas_filtradas_e_ordenadas(): void
    {
        Mensagem::factory()->create([
            'titulo' => 'Mensagem ativa empresta docente',
            'sistema' => 'empresta',
            'publico' => ['Docente'],
            'ativo' => true,
            'prioridade' => 20,
            'inicio_exibicao' => now()->subDay(),
            'fim_exibicao' => now()->addDay(),
        ]);

        Mensagem::factory()->create([
            'titulo' => 'Mensagem geral ativa',
            'sistema' => 'geral',
            'publico' => ['Docente'],
            'ativo' => true,
            'prioridade' => 10,
            'inicio_exibicao' => now()->subDay(),
            'fim_exibicao' => now()->addDay(),
        ]);

        Mensagem::factory()->create([
            'titulo' => 'Mensagem inativa',
            'sistema' => 'empresta',
            'publico' => ['Docente'],
            'ativo' => false,
            'prioridade' => 99,
        ]);

        $response = $this->getJson('/api/mensagens?sistema=empresta&publico=Docente&ativos=true');

        $response->assertOk();
        $response->assertJsonCount(2);
        $response->assertJsonPath('0.titulo', 'Mensagem ativa empresta docente');
        $response->assertJsonPath('1.titulo', 'Mensagem geral ativa');
    }

    public function test_respeita_limite(): void
    {
        Mensagem::factory()->count(3)->create([
            'ativo' => true,
            'inicio_exibicao' => now()->subDay(),
            'fim_exibicao' => now()->addDay(),
        ]);

        $response = $this->getJson('/api/mensagens?ativos=true&limite=2');

        $response->assertOk();
        $response->assertJsonCount(2);
    }
}
