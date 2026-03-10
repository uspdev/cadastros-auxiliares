<?php

namespace Tests\Feature;

use App\Models\Programa;
use App\Services\ReplicadoPosgraduacaoService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiPosProgramasTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config()->set('cadastros-auxiliares.password', '');
    }

    public function test_lista_programas_com_codslg_local_e_nomcur_do_replicado(): void
    {
        Programa::query()->create([
            'codcur' => 1001,
            'codslg' => 'PPG-ALFA',
        ]);

        Programa::query()->create([
            'codcur' => 1002,
            'codslg' => 'PPG-BETA',
        ]);

        $this->mock(ReplicadoPosgraduacaoService::class, function ($mock) {
            $mock->shouldReceive('listarProgramas')
                ->once()
                ->andReturn([
                    ['codcur' => 1001, 'nomcur' => 'Programa Alfa'],
                    ['codcur' => 1002, 'nomcur' => 'Programa Beta'],
                ]);
        });

        $response = $this->getJson('/api/pos/programas');

        $response->assertOk();
        $response->assertJsonCount(2);
        $response->assertJsonPath('0.codcur', 1001);
        $response->assertJsonPath('0.nomcur', 'Programa Alfa');
        $response->assertJsonPath('0.codslg', 'PPG-ALFA');
        $response->assertJsonPath('1.codcur', 1002);
        $response->assertJsonPath('1.nomcur', 'Programa Beta');
        $response->assertJsonPath('1.codslg', 'PPG-BETA');
    }

    public function test_obtem_programa_por_codcur(): void
    {
        $programa = Programa::query()->create([
            'codcur' => 2001,
            'codslg' => 'PPG-GAMA',
        ]);

        $this->mock(ReplicadoPosgraduacaoService::class, function ($mock) {
            $mock->shouldReceive('obterPrograma')
                ->once()
                ->with(2001)
                ->andReturn([
                    'codcur' => 2001,
                    'nomcur' => 'Programa Gama',
                ]);
        });

        $response = $this->getJson('/api/pos/programas/2001');

        $response->assertOk();
        $response->assertJsonPath('id', $programa->id);
        $response->assertJsonPath('codcur', 2001);
        $response->assertJsonPath('nomcur', 'Programa Gama');
        $response->assertJsonPath('codslg', 'PPG-GAMA');
    }

    public function test_retorna_404_quando_programa_nao_esta_cadastrado_localmente(): void
    {
        $this->mock(ReplicadoPosgraduacaoService::class, function ($mock) {
            $mock->shouldNotReceive('obterPrograma');
        });

        $response = $this->getJson('/api/pos/programas/9999');

        $response->assertNotFound();
    }

    public function test_retorna_404_quando_programa_nao_existe_no_replicado(): void
    {
        Programa::query()->create([
            'codcur' => 3001,
            'codslg' => 'PPG-DELTA',
        ]);

        $this->mock(ReplicadoPosgraduacaoService::class, function ($mock) {
            $mock->shouldReceive('obterPrograma')
                ->once()
                ->with(3001)
                ->andReturn(null);
        });

        $response = $this->getJson('/api/pos/programas/3001');

        $response->assertNotFound();
        $response->assertJsonPath('message', 'Programa não encontrado no replicado.');
    }
}
