<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CheckAvailabilityRequest;
use App\Http\Requests\Api\CreateResourceRequest;
use App\Http\Requests\Api\UpdateResourceRequest;
use App\Repositories\Interfaces\ResourceRepositoryInterface;

/**
 * @OA\Tag(
 *   name="Resources",
 *   description="Endpoints de recursos",
 * )
 */
class ResourceController extends Controller
{
    protected $resourceRepository;

    public function __construct(ResourceRepositoryInterface $resourceRepository)
    {
        $this->resourceRepository = $resourceRepository;
    }

    /**
     * @OA\Get(
     *     path="/api/resources",
     *     summary="Obtener todos los recursos",
     *     tags={"Resources"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de recursos",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Resource")
     *         )
     *     )
     * )
     */
    public function index()
    {
        $resources = $this->resourceRepository->getAll();
        return response()->json($resources);
    }

    /**
     * @OA\Post(
     *     path="/api/resources",
     *     summary="Crear un recurso",
     *     tags={"Resources"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CreateResourceRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Recurso creado",
     *         @OA\JsonContent(ref="#/components/schemas/Resource")
     *     )
     * )
     */
    public function store(CreateResourceRequest $request)
    {
        $resource = $this->resourceRepository->create($request->validated());
        return response()->json($resource, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/resources/{id}",
     *     summary="Obtener un recurso",
     *     tags={"Resources"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del recurso",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Recurso encontrado",
     *         @OA\JsonContent(ref="#/components/schemas/Resource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Recurso no encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string")
     *         )
     *     )
     * )
     */
    public function show(int $id)
    {
        try {
            $resource = $this->resourceRepository->findById($id);
            return response()->json($resource);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Resource not found'], 404);
        }
    }

    /**
     * @OA\Patch(
     *     path="/api/resources/{id}",
     *     summary="Actualizar un recurso",
     *     tags={"Resources"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del recurso",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateResourceRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Recurso actualizado",
     *         @OA\JsonContent(ref="#/components/schemas/Resource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Recurso no encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string")
     *         )
     *     )
     * )
     */
    public function update(UpdateResourceRequest $request, int $id)
    {
        try {
            $resource = $this->resourceRepository->update($request->validated(), $id);
            return response()->json($resource);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Resource not found'], 404);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/resources/{id}",
     *     summary="Desactivar un recurso",
     *     tags={"Resources"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del recurso",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Recurso desactivado",
     *         @OA\JsonContent(ref="#/components/schemas/Resource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Recurso no encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string")
     *         )
     *     )
     * )
     */
    public function destroy(int $id)
    {
        try {
            $resource = $this->resourceRepository->delete($id);
            return response()->json($resource);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Resource not found'], 404);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/resources/{id}/availability",
     *     summary="Verificar disponibilidad de un recurso",
     *     tags={"Resources"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\Parameter(
     *         name="resourceId",
     *         in="path",
     *         required=true,
     *         description="ID del recurso",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CheckAvailabilityRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Recurso disponible",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="available", type="boolean")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Recurso no disponible",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="available", type="boolean")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Recurso no encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string")
     *         )
     *     )
     * )
     */
    public function checkAvailability(CheckAvailabilityRequest $request, int $resourceId)
    {
        try {
            $data = $request->validated();
            $isAvailable = $this->resourceRepository->checkAvailability(
                $resourceId,
                $data['reserved_at'],
                $data['duration']
            );
            return response()->json(['available' => $isAvailable], $isAvailable ? 200 : 400);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Resource not found'], 404);
        }
    }
}
