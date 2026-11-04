<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'id_usuarios',
    'id_categorias',
    'nome',
    'tempo_preparo_minutos',
    'porcoes',
    'modo_preparo',
    'ingredientes',
])]
class Receita extends Model
{
    use HasFactory;

    const CREATED_AT = 'criado_em';

    const UPDATED_AT = 'alterado_em';

    protected $table = 'receitas';

    protected function casts(): array
    {
        return [
            'tempo_preparo_minutos' => 'integer',
            'porcoes' => 'integer',
        ];
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuarios');
    }

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class, 'id_categorias');
    }
}
