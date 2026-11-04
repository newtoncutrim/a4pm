<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

#[Fillable(['nome', 'login', 'senha'])]
#[Hidden(['senha'])]
class Usuario extends Authenticatable
{
    use HasApiTokens, HasFactory;

    const CREATED_AT = 'criado_em';

    const UPDATED_AT = 'alterado_em';

    protected $table = 'usuarios';

    protected $authPasswordName = 'senha';

    protected $rememberTokenName = '';

    protected function casts(): array
    {
        return [
            'senha' => 'hashed',
        ];
    }

    public function receitas(): HasMany
    {
        return $this->hasMany(Receita::class, 'id_usuarios');
    }
}
