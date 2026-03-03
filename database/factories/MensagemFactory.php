<?php

namespace Database\Factories;

use App\Models\Mensagem;
use Illuminate\Database\Eloquent\Factories\Factory;

class MensagemFactory extends Factory
{
    protected $model = Mensagem::class;

    public function definition(): array
    {
        return [
            'titulo' => $this->faker->sentence(3),
            'conteudo' => $this->faker->paragraph(),
            'tipo' => $this->faker->randomElement(['info', 'aviso', 'erro', 'sucesso']),
            'ativo' => true,
            'inicio_exibicao' => now()->subHour(),
            'fim_exibicao' => now()->addHour(),
            'prioridade' => $this->faker->numberBetween(0, 10),
            'sistema' => 'geral',
            'publico' => true,
        ];
    }
}
