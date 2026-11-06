<?php

namespace Database\Seeders;

use App\Models\Usuario;
use Illuminate\Database\Seeder;

class ReceitaSeeder extends Seeder
{
    public function run(): void
    {
        $usuario = Usuario::where('login', 'admin')->first();

        if ($usuario->receitas()->exists()) {
            return;
        }

        $receitas = [
            [
                'id_categorias' => 1,
                'nome' => 'Bolo de cenoura',
                'tempo_preparo_minutos' => 50,
                'porcoes' => 10,
                'ingredientes' => '3 cenouras 4 ovos 1 xícara de óleo 2 xícaras de açúcar 2 xícaras de farinha de trigo 1 colher de fermento',
                'modo_preparo' => 'Bata no liquidificador a cenoura, os ovos e o óleo. Misture com o açúcar e a farinha, adicione o fermento e asse a 180 graus por 40 minutos.',
            ],
            [
                'id_categorias' => 1,
                'nome' => 'Torta de limão',
                'tempo_preparo_minutos' => 40,
                'porcoes' => 8,
                'ingredientes' => '1 pacote de biscoito maisena 100 g de manteiga 1 lata de leite condensado 1 caixa de creme de leite suco de 4 limões',
                'modo_preparo' => 'Triture o biscoito com a manteiga e forre a forma. Bata o leite condensado, o creme de leite e o suco de limão e coloque sobre a massa. Leve à geladeira por 3 horas.',
            ],
            [
                'id_categorias' => 2,
                'nome' => 'Picanha na manteiga',
                'tempo_preparo_minutos' => 30,
                'porcoes' => 4,
                'ingredientes' => '1 kg de picanha 3 colheres de manteiga 4 dentes de alho sal grosso',
                'modo_preparo' => 'Corte a picanha em bifes, tempere com sal grosso e grelhe. Doure o alho na manteiga e jogue por cima da carne.',
            ],
            [
                'id_categorias' => 2,
                'nome' => 'Carne de panela',
                'tempo_preparo_minutos' => 90,
                'porcoes' => 6,
                'ingredientes' => '1 kg de acém 2 cebolas 3 tomates 2 batatas sal e pimenta',
                'modo_preparo' => 'Doure a carne em pedaços, junte a cebola e o tomate, cubra com água e cozinhe na pressão por 40 minutos. Adicione as batatas e cozinhe mais 10 minutos.',
            ],
            [
                'id_categorias' => 3,
                'nome' => 'Frango assado',
                'tempo_preparo_minutos' => 80,
                'porcoes' => 6,
                'ingredientes' => '1 frango inteiro 1 limão 4 dentes de alho alecrim sal',
                'modo_preparo' => 'Tempere o frango com alho, limão, alecrim e sal e deixe descansar por 1 hora. Asse coberto com papel alumínio por 50 minutos e descoberto até dourar.',
            ],
            [
                'id_categorias' => 3,
                'nome' => 'Strogonoff de frango',
                'tempo_preparo_minutos' => 35,
                'porcoes' => 4,
                'ingredientes' => '500 g de peito de frango 1 cebola 1 caixa de creme de leite 3 colheres de ketchup 1 colher de mostarda',
                'modo_preparo' => 'Refogue o frango em cubos com a cebola. Junte o ketchup e a mostarda, cozinhe por 10 minutos e desligue o fogo antes de colocar o creme de leite.',
            ],
            [
                'id_categorias' => 4,
                'nome' => 'Moqueca de peixe',
                'tempo_preparo_minutos' => 45,
                'porcoes' => 4,
                'ingredientes' => '1 kg de peixe branco 1 pimentão 2 tomates 1 cebola 200 ml de leite de coco 2 colheres de azeite de dendê',
                'modo_preparo' => 'Tempere o peixe com sal e limão. Monte camadas de cebola, tomate, pimentão e peixe na panela, regue com leite de coco e dendê e cozinhe por 20 minutos.',
            ],
            [
                'id_categorias' => 4,
                'nome' => 'Camarão alho e óleo',
                'tempo_preparo_minutos' => 20,
                'porcoes' => 2,
                'ingredientes' => '500 g de camarão limpo 5 dentes de alho azeite salsinha',
                'modo_preparo' => 'Doure o alho no azeite, junte o camarão e refogue por 3 minutos. Finalize com salsinha.',
            ],
        ];

        foreach ($receitas as $receita) {
            $usuario->receitas()->create($receita);
        }
    }
}
