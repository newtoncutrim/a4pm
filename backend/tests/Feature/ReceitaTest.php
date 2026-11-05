<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\Receita;
use App\Models\Usuario;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ReceitaTest extends TestCase
{
    use RefreshDatabase;

    private Usuario $usuario;

    protected function setUp(): void
    {
        parent::setUp();

        $this->usuario = Usuario::factory()->create();
        Sanctum::actingAs($this->usuario);
    }

    public function test_cadastra_receita(): void
    {
        $categoria = Categoria::factory()->create();

        $this->postJson('/api/receitas', [
            'id_categorias' => $categoria->id,
            'nome' => 'Bolo de cenoura',
            'modo_preparo' => 'Bata tudo e asse.',
        ])->assertCreated()->assertJsonPath('nome', 'Bolo de cenoura');

        $this->assertDatabaseHas('receitas', ['nome' => 'Bolo de cenoura', 'id_usuarios' => $this->usuario->id]);
    }

    public function test_nao_cadastra_sem_campos_obrigatorios(): void
    {
        $this->postJson('/api/receitas', [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['id_categorias', 'nome', 'modo_preparo']);
    }

    public function test_pesquisa_apenas_receitas_do_usuario(): void
    {
        Receita::factory()->for($this->usuario)->create(['nome' => 'Bolo de cenoura']);
        Receita::factory()->for($this->usuario)->create(['nome' => 'Lasanha']);
        Receita::factory()->create(['nome' => 'Bolo de fubá']);

        $this->getJson('/api/receitas?nome=bolo')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.nome', 'Bolo de cenoura');
    }

    public function test_mostra_receita(): void
    {
        $receita = Receita::factory()->for($this->usuario)->create();

        $this->getJson("/api/receitas/{$receita->id}")
            ->assertOk()
            ->assertJsonPath('id', $receita->id);
    }

    public function test_edita_receita(): void
    {
        $receita = Receita::factory()->for($this->usuario)->create();

        $this->putJson("/api/receitas/{$receita->id}", [
            'id_categorias' => $receita->id_categorias,
            'nome' => 'Novo nome',
            'modo_preparo' => 'Outro preparo',
        ])
            ->assertOk()
            ->assertJsonPath('nome', 'Novo nome');
    }

    public function test_exclui_receita(): void
    {
        $receita = Receita::factory()->for($this->usuario)->create();

        $this->deleteJson("/api/receitas/{$receita->id}")->assertNoContent();

        $this->assertModelMissing($receita);
    }

    public function test_nao_acessa_receita_de_outro_usuario(): void
    {
        $receita = Receita::factory()->create();

        $this->getJson("/api/receitas/{$receita->id}")->assertNotFound();
        $this->deleteJson("/api/receitas/{$receita->id}")->assertNotFound();
    }

    public function test_apagar_categoria_apaga_as_receitas(): void
    {
        $receita = Receita::factory()->for($this->usuario)->create();

        $receita->categoria->delete();

        $this->assertModelMissing($receita);
    }

    public function test_nao_apaga_usuario_que_tem_receitas(): void
    {
        Receita::factory()->for($this->usuario)->create();

        $this->expectException(QueryException::class);

        $this->usuario->delete();
    }
}
