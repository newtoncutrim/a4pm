<?php

namespace App\Http\Controllers;

use App\Http\Requests\CadastrarUsuarioRequest;
use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenApi\Spec as OA;

class AuthController extends Controller
{
    public function __construct(private AuthService $service) {}

    #[OA\Operation\Post(
        path: '/api/usuarios',
        summary: 'Cadastra um usuário',
        tags: ['Autenticação']
    )]
    #[OA\RequestBody(required: true, content: new OA\MediaType\Json(
        required: ['login', 'senha'],
        properties: ['nome' => OA\Type::String, 'login' => OA\Type::String, 'senha' => OA\Type::String],
    ))]
    #[OA\Response(response: Response::HTTP_CREATED, description: 'Usuário cadastrado')]
    #[OA\Response(response: Response::HTTP_UNPROCESSABLE_ENTITY, description: 'Dados inválidos ou login já usado')]
    public function cadastrar(CadastrarUsuarioRequest $request): JsonResponse
    {
        $usuario = $this->service->cadastrar($request->validated());

        return response()->json($usuario, Response::HTTP_CREATED);
    }

    #[OA\Operation\Post(
        path: '/api/login',
        summary: 'Faz login e retorna o token',
        tags: ['Autenticação']
    )]
    #[OA\RequestBody(required: true, content: new OA\MediaType\Json(
        required: ['login', 'senha'],
        properties: ['login' => OA\Type::String, 'senha' => OA\Type::String],
    ))]
    #[OA\Response(response: Response::HTTP_OK, description: 'Login feito, retorna o token')]
    #[OA\Response(response: Response::HTTP_UNAUTHORIZED, description: 'Login ou senha inválidos')]
    public function login(LoginRequest $request): JsonResponse
    {
        $dados = $request->validated();

        $resultado = $this->service->login($dados['login'], $dados['senha']);

        if (! $resultado) {
            return response()->json(['message' => 'Login ou senha inválidos.'], Response::HTTP_UNAUTHORIZED);
        }

        return response()->json($resultado);
    }

    #[OA\Operation\Post(
        path: '/api/logout',
        summary: 'Faz logoff e invalida o token',
        tags: ['Autenticação'],
        security: [new OA\Security\Requirement(scheme: 'token')]
    )]
    #[OA\Response(response: Response::HTTP_NO_CONTENT, description: 'Logoff feito')]
    #[OA\Response(response: Response::HTTP_UNAUTHORIZED, description: 'Token ausente ou inválido')]
    public function logout(Request $request): Response
    {
        $this->service->logout($request->user());

        return response()->noContent();
    }
}
