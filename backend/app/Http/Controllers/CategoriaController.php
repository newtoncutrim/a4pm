<?php

namespace App\Http\Controllers;

use App\Services\CategoriaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use OpenApi\Spec as OA;

class CategoriaController extends Controller
{
    public function __construct(private CategoriaService $service) {}

    #[OA\Operation\Get(
        path: '/api/categorias',
        summary: 'Lista as categorias',
        tags: ['Categorias'],
        security: [new OA\Security\Requirement(scheme: 'token')]
    )]
    #[OA\Response(response: Response::HTTP_OK, description: 'Lista de categorias')]
    public function index(): JsonResponse
    {
        return response()->json($this->service->listar());
    }

    #[OA\Operation\Get(
        path: '/api/categorias/{id}',
        summary: 'Mostra uma categoria',
        tags: ['Categorias'],
        security: [new OA\Security\Requirement(scheme: 'token')]
    )]
    #[OA\Parameter\Path(name: 'id', schema: new OA\Schema(type: OA\Type::Integer))]
    #[OA\Response(response: Response::HTTP_OK, description: 'Categoria')]
    #[OA\Response(response: Response::HTTP_NOT_FOUND, description: 'Categoria não encontrada')]
    public function show(int $id): JsonResponse
    {
        return response()->json($this->service->buscar($id));
    }
}
