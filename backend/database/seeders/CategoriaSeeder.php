<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('categorias')->insertOrIgnore([
            ['id' => 1, 'nome' => 'Bolos e tortas doces'],
            ['id' => 2, 'nome' => 'Carnes'],
            ['id' => 3, 'nome' => 'Aves'],
            ['id' => 4, 'nome' => 'Peixes e frutos do mar'],
            ['id' => 5, 'nome' => 'Saladas, molhos e acompanhamentos'],
            ['id' => 6, 'nome' => 'Sopas'],
            ['id' => 7, 'nome' => 'Massas'],
            ['id' => 8, 'nome' => 'Bebidas'],
            ['id' => 9, 'nome' => 'Doces e sobremesas'],
            ['id' => 10, 'nome' => 'Lanches'],
            ['id' => 11, 'nome' => 'Prato Único'],
            ['id' => 12, 'nome' => 'Light'],
            ['id' => 13, 'nome' => 'Alimentação Saudável'],
        ]);
    }
}
