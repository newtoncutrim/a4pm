<?php

namespace App\Services;

use App\Models\Receita;
use App\Models\Usuario;
use App\Repositories\ReceitaRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ReceitaService
{
    public function __construct(private ReceitaRepository $receitas) {}

    public function pesquisar(Usuario $usuario, array $filtros): LengthAwarePaginator
    {
        return $this->receitas->pesquisar($usuario->id, $filtros);
    }

    public function buscar(Usuario $usuario, int $id): Receita
    {
        return $this->receitas->buscar($usuario->id, $id);
    }

    public function cadastrar(Usuario $usuario, array $dados): Receita
    {
        return $this->receitas->criar([...$dados, 'id_usuarios' => $usuario->id]);
    }

    public function editar(Usuario $usuario, int $id, array $dados): Receita
    {
        return $this->receitas->atualizar($this->buscar($usuario, $id), $dados);
    }

    public function excluir(Usuario $usuario, int $id): void
    {
        $this->receitas->excluir($this->buscar($usuario, $id));
    }
}
