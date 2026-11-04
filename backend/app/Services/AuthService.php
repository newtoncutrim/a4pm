<?php

namespace App\Services;

use App\Models\Usuario;
use App\Repositories\UsuarioRepository;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

class AuthService
{
    public function __construct(private UsuarioRepository $usuarios) {}

    public function cadastrar(array $dados): Usuario
    {
        return $this->usuarios->criar($dados);
    }

    public function login(string $login, string $senha): ?array
    {
        $usuario = $this->usuarios->buscarPorLogin($login);

        if (! $usuario || ! Hash::check($senha, $usuario->senha)) {
            return null;
        }

        return [
            'token' => $usuario->createToken('api')->plainTextToken,
            'usuario' => $usuario,
        ];
    }

    public function logout(Usuario $usuario): void
    {
        $token = $usuario->currentAccessToken();

        if ($token instanceof PersonalAccessToken) {
            $token->delete();
        }
    }
}
