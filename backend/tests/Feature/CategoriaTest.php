<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\Usuario;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CategoriaTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Sanctum::actingAs(Usuario::factory()->create());
    }

    public function test_lista_categorias(): void
    {
        Categoria::factory()->count(2)->create();

        $this->getJson('/api/categorias')->assertOk()->assertJsonCount(2);
    }

    public function test_mostra_categoria(): void
    {
        $categoria = Categoria::factory()->create(['nome' => 'Massas']);

        $this->getJson("/api/categorias/{$categoria->id}")
            ->assertOk()
            ->assertJsonPath('nome', 'Massas');
    }

    public function test_categoria_inexistente(): void
    {
        $this->getJson('/api/categorias/999')->assertNotFound();
    }

    public function test_nao_permite_nome_repetido(): void
    {
        Categoria::create(['nome' => 'Massas']);

        $this->expectException(QueryException::class);

        Categoria::create(['nome' => 'Massas']);
    }
}
