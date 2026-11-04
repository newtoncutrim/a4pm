<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\WithoutTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['nome'])]
#[WithoutTimestamps]
class Categoria extends Model
{
    use HasFactory;

    protected $table = 'categorias';

    public function receitas(): HasMany
    {
        return $this->hasMany(Receita::class, 'id_categorias');
    }
}
