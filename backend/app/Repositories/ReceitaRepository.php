<?php

namespace App\Repositories;

use App\Models\Receita;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ReceitaRepository
{
    public function pesquisar(int $idUsuario, array $filtros): LengthAwarePaginator
    {
        return Receita::with('categoria')
            ->where('id_usuarios', $idUsuario)
            ->when($filtros['nome'] ?? null, fn ($query, $nome) => $query->where('nome', 'like', "%{$nome}%"))
            ->when($filtros['id_categorias'] ?? null, fn ($query, $categoria) => $query->where('id_categorias', $categoria))
            ->orderBy('nome')
            ->paginate(10);
    }

    public function buscar(int $idUsuario, int $id): Receita
    {
        return Receita::with('categoria')
            ->where('id_usuarios', $idUsuario)
            ->findOrFail($id);
    }

    public function criar(array $dados): Receita
    {
        return Receita::create($dados)->load('categoria');
    }

    public function atualizar(Receita $receita, array $dados): Receita
    {
        $receita->update($dados);

        return $receita->load('categoria');
    }

    public function excluir(Receita $receita): void
    {
        $receita->delete();
    }
}
