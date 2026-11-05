<?php

namespace App\Services;

use App\Models\Categoria;
use App\Repositories\CategoriaRepository;
use Illuminate\Database\Eloquent\Collection;

class CategoriaService
{
    public function __construct(private CategoriaRepository $categorias) {}

    public function listar(): Collection
    {
        return $this->categorias->listar();
    }

    public function buscar(int $id): Categoria
    {
        return $this->categorias->buscar($id);
    }
}
