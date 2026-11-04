<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class UsuarioFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nome' => fake()->name(),
            'login' => fake()->unique()->userName(),
            'senha' => 'password',
        ];
    }
}
