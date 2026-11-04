<?php

namespace Tests\Feature;

use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_cadastra_usuario(): void
    {
        $this->postJson('/api/usuarios', ['nome' => 'Maria', 'login' => 'maria', 'senha' => 'segredo123'])
            ->assertCreated()
            ->assertJsonPath('login', 'maria')
            ->assertJsonMissingPath('senha');

        $usuario = Usuario::where('login', 'maria')->first();
        $this->assertTrue(Hash::check('segredo123', $usuario->senha));
    }

    public function test_nao_cadastra_login_repetido(): void
    {
        Usuario::factory()->create(['login' => 'maria']);

        $this->postJson('/api/usuarios', ['login' => 'maria', 'senha' => 'segredo123'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('login');
    }

    public function test_login_retorna_token(): void
    {
        Usuario::factory()->create(['login' => 'maria', 'senha' => 'segredo123']);

        $this->postJson('/api/login', ['login' => 'maria', 'senha' => 'segredo123'])
            ->assertOk()
            ->assertJsonStructure(['token', 'usuario' => ['id', 'login']]);
    }

    public function test_login_com_senha_errada(): void
    {
        Usuario::factory()->create(['login' => 'maria', 'senha' => 'segredo123']);

        $this->postJson('/api/login', ['login' => 'maria', 'senha' => 'errada'])
            ->assertUnauthorized();
    }

    public function test_logout_apaga_o_token(): void
    {
        $usuario = Usuario::factory()->create();
        $token = $usuario->createToken('api')->plainTextToken;

        $this->withToken($token)->postJson('/api/logout')->assertNoContent();

        $this->assertSame(0, $usuario->tokens()->count());
    }

    public function test_rotas_exigem_token(): void
    {
        $this->postJson('/api/logout')->assertUnauthorized();
        $this->getJson('/api/receitas')->assertUnauthorized();
        $this->getJson('/api/categorias')->assertUnauthorized();
    }

    public function test_token_expira_em_um_dia(): void
    {
        $token = Usuario::factory()->create()->createToken('api')->plainTextToken;

        $this->travel(25)->hours();

        $this->withToken($token)->postJson('/api/logout')->assertUnauthorized();
    }
}
