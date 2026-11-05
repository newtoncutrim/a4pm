<?php

namespace App\Repositories;

use App\Models\Categoria;
use Illuminate\Database\Eloquent\Collection;

class CategoriaRepository
{
    public function listar(): Collection
    {
        return Categoria::orderBy('nome')->get();
    }

    public function buscar(int $id): Categoria
    {
        return Categoria::findOrFail($id);
    }
}
