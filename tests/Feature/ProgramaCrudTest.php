<?php

namespace Tests\Feature;

use App\Models\Programa;
use App\Models\User;
use App\Services\ReplicadoPosgraduacaoService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class ProgramaCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_redireciona_visitante_para_login(): void
    {
        $response = $this->get('/programas');

        $response->assertRedirect('/login');
    }

    public function test_admin_lista_programas(): void
    {
        $admin = $this->criarAdmin();

        Programa::query()->create([
            'codcur' => 5001,
            'codslg' => 'PPG-ALFA',
        ]);

        $this->mock(ReplicadoPosgraduacaoService::class, function ($mock) {
            $mock->shouldReceive('listarProgramas')
                ->once()
                ->andReturn([
                    ['codcur' => 5001, 'nomcur' => 'Programa Alfa'],
                ]);
        });

        $response = $this->actingAs($admin)->get('/programas');

        $response->assertOk();
        $response->assertSee('Programas de Pós-Graduação');
        $response->assertSee('5001');
        $response->assertSee('Programa Alfa');
        $response->assertSee('PPG-ALFA');
    }

    public function test_admin_cria_programa(): void
    {
        $admin = $this->criarAdmin();

        $response = $this->actingAs($admin)->post('/programas', [
            'codcur' => 6001,
            'codslg' => 'PPG-BETA',
        ]);

        $response->assertRedirect('/programas');

        $this->assertDatabaseHas('programas', [
            'codcur' => 6001,
            'codslg' => 'PPG-BETA',
        ]);
    }

    public function test_admin_atualiza_programa(): void
    {
        $admin = $this->criarAdmin();

        $programa = Programa::query()->create([
            'codcur' => 7001,
            'codslg' => 'PPG-GAMMA',
        ]);

        $response = $this->actingAs($admin)->put("/programas/{$programa->id}", [
            'codslg' => 'PPG-DELTA',
        ]);

        $response->assertRedirect('/programas');

        $this->assertDatabaseHas('programas', [
            'id' => $programa->id,
            'codcur' => 7001,
            'codslg' => 'PPG-DELTA',
        ]);
    }

    public function test_admin_visualiza_show_com_nome_do_programa(): void
    {
        $admin = $this->criarAdmin();

        $programa = Programa::query()->create([
            'codcur' => 7101,
            'codslg' => 'PPG-ETA',
        ]);

        $this->mock(ReplicadoPosgraduacaoService::class, function ($mock) {
            $mock->shouldReceive('obterPrograma')
                ->once()
                ->with(7101)
                ->andReturn([
                    'codcur' => 7101,
                    'nomcur' => 'Programa Eta',
                ]);
        });

        $response = $this->actingAs($admin)->get("/programas/{$programa->id}");

        $response->assertOk();
        $response->assertSee('Programa Eta');
    }

    public function test_admin_visualiza_edit_com_nome_do_programa(): void
    {
        $admin = $this->criarAdmin();

        $programa = Programa::query()->create([
            'codcur' => 7201,
            'codslg' => 'PPG-THETA',
        ]);

        $this->mock(ReplicadoPosgraduacaoService::class, function ($mock) {
            $mock->shouldReceive('obterPrograma')
                ->once()
                ->with(7201)
                ->andReturn([
                    'codcur' => 7201,
                    'nomcur' => 'Programa Theta',
                ]);
        });

        $response = $this->actingAs($admin)->get("/programas/{$programa->id}/edit");

        $response->assertOk();
        $response->assertSee('Programa Theta');
    }

    public function test_admin_nao_consegue_editar_codcur_no_update(): void
    {
        $admin = $this->criarAdmin();

        $programa = Programa::query()->create([
            'codcur' => 7301,
            'codslg' => 'PPG-IOTA',
        ]);

        $response = $this->actingAs($admin)->put("/programas/{$programa->id}", [
            'codcur' => 9999,
            'codslg' => 'PPG-IOTA-2',
        ]);

        $response->assertSessionHasErrors('codcur');

        $this->assertDatabaseHas('programas', [
            'id' => $programa->id,
            'codcur' => 7301,
            'codslg' => 'PPG-IOTA',
        ]);
    }

    public function test_admin_remove_programa(): void
    {
        $admin = $this->criarAdmin();

        $programa = Programa::query()->create([
            'codcur' => 8001,
            'codslg' => 'PPG-EPSILON',
        ]);

        $response = $this->actingAs($admin)->delete("/programas/{$programa->id}");

        $response->assertRedirect('/programas');
        $this->assertDatabaseMissing('programas', ['id' => $programa->id]);
    }

    private function criarAdmin(): User
    {
        Permission::findOrCreate('admin', 'web');

        $user = User::factory()->create();
        $user->givePermissionTo('admin');

        return $user;
    }
}
