<?php

namespace App\Http\Controllers;

use OpenApi\Spec as OA;

#[OA\Info(version: '1.0.0', title: 'API Receitas')]
#[OA\Security\Scheme(securityScheme: 'token', type: 'http', scheme: 'bearer')]
#[OA\Tag(name: 'Autenticação', description: 'Cadastro, login e logout')]
#[OA\Tag(name: 'Categorias', description: 'Categorias fixas das receitas')]
#[OA\Tag(name: 'Receitas', description: 'Receitas do usuário logado')]
abstract class Controller {}
