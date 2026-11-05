<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReceitaRequest;
use App\Services\ReceitaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenApi\Spec as OA;

class ReceitaController extends Controller
{
    private const CAMPOS = [
        'id_categorias' => OA\Type::Integer,
        'nome' => OA\Type::String,
        'tempo_preparo_minutos' => OA\Type::Integer,
        'porcoes' => OA\Type::Integer,
        'modo_preparo' => OA\Type::String,
        'ingredientes' => OA\Type::String,
    ];

    public function __construct(private ReceitaService $service) {}

    #[OA\Operation\Get(
        path: '/api/receitas',
        summary: 'Pesquisa as receitas do usuário',
        tags: ['Receitas'],
        security: [new OA\Security\Requirement(scheme: 'token')]
    )]
    #[OA\Parameter\Query(name: 'nome', schema: new OA\Schema(type: OA\Type::String))]
    #[OA\Parameter\Query(name: 'id_categorias', schema: new OA\Schema(type: OA\Type::Integer))]
    #[OA\Response(response: Response::HTTP_OK, description: 'Lista de receitas')]
    public function index(Request $request): JsonResponse
    {
        $receitas = $this->service->pesquisar($request->user(), $request->only('nome', 'id_categorias'));

        return response()->json($receitas);
    }

    #[OA\Operation\Post(
        path: '/api/receitas',
        summary: 'Cadastra uma receita',
        tags: ['Receitas'],
        security: [new OA\Security\Requirement(scheme: 'token')]
    )]
    #[OA\RequestBody(required: true, content: new OA\MediaType\Json(
        required: ['modo_preparo'],
        properties: self::CAMPOS,
    ))]
    #[OA\Response(response: Response::HTTP_CREATED, description: 'Receita cadastrada')]
    #[OA\Response(response: Response::HTTP_UNPROCESSABLE_ENTITY, description: 'Dados inválidos')]
    public function store(ReceitaRequest $request): JsonResponse
    {
        $receita = $this->service->cadastrar($request->user(), $request->validated());

        return response()->json($receita, Response::HTTP_CREATED);
    }

    #[OA\Operation\Get(
        path: '/api/receitas/{id}',
        summary: 'Mostra uma receita para impressão',
        tags: ['Receitas'],
        security: [new OA\Security\Requirement(scheme: 'token')]
    )]
    #[OA\Parameter\Path(name: 'id', schema: new OA\Schema(type: OA\Type::Integer))]
    #[OA\Response(response: Response::HTTP_OK, description: 'Receita')]
    #[OA\Response(response: Response::HTTP_NOT_FOUND, description: 'Receita não encontrada')]
    public function show(Request $request, int $id): JsonResponse
    {
        return response()->json($this->service->buscar($request->user(), $id));
    }

    #[OA\Operation\Put(
        path: '/api/receitas/{id}',
        summary: 'Edita uma receita',
        tags: ['Receitas'],
        security: [new OA\Security\Requirement(scheme: 'token')]
    )]
    #[OA\Parameter\Path(name: 'id', schema: new OA\Schema(type: OA\Type::Integer))]
    #[OA\RequestBody(required: true, content: new OA\MediaType\Json(
        required: ['modo_preparo'],
        properties: self::CAMPOS,
    ))]
    #[OA\Response(response: Response::HTTP_OK, description: 'Receita alterada')]
    #[OA\Response(response: Response::HTTP_NOT_FOUND, description: 'Receita não encontrada')]
    #[OA\Response(response: Response::HTTP_UNPROCESSABLE_ENTITY, description: 'Dados inválidos')]
    public function update(ReceitaRequest $request, int $id): JsonResponse
    {
        $receita = $this->service->editar($request->user(), $id, $request->validated());

        return response()->json($receita);
    }

    #[OA\Operation\Delete(
        path: '/api/receitas/{id}',
        summary: 'Exclui uma receita',
        tags: ['Receitas'],
        security: [new OA\Security\Requirement(scheme: 'token')]
    )]
    #[OA\Parameter\Path(name: 'id', schema: new OA\Schema(type: OA\Type::Integer))]
    #[OA\Response(response: Response::HTTP_NO_CONTENT, description: 'Receita excluída')]
    #[OA\Response(response: Response::HTTP_NOT_FOUND, description: 'Receita não encontrada')]
    public function destroy(Request $request, int $id): Response
    {
        $this->service->excluir($request->user(), $id);

        return response()->noContent();
    }
}
