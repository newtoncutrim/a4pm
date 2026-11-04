<?php

namespace Database\Factories;

use App\Models\Categoria;
use App\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReceitaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id_usuarios' => Usuario::factory(),
            'id_categorias' => Categoria::factory(),
            'nome' => fake()->words(3, true),
            'tempo_preparo_minutos' => fake()->numberBetween(10, 120),
            'porcoes' => fake()->numberBetween(1, 8),
            'modo_preparo' => fake()->paragraph(),
            'ingredientes' => fake()->paragraph(),
        ];
    }
}
