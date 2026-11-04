<?php

namespace App\Repositories;

use App\Models\Usuario;

class UsuarioRepository
{
    public function criar(array $dados): Usuario
    {
        return Usuario::create($dados);
    }

    public function buscarPorLogin(string $login): ?Usuario
    {
        return Usuario::where('login', $login)->first();
    }
}
