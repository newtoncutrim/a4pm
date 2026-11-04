<?php

namespace App\Http\Controllers;

use OpenApi\Spec as OA;

#[OA\Info(version: '1.0.0', title: 'API Receitas')]
#[OA\Security\Scheme(securityScheme: 'token', type: 'http', scheme: 'bearer')]
abstract class Controller {}
