<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CheckAvailabilityRequest;
use App\Http\Requests\Api\CreateResourceRequest;
use App\Http\Requests\Api\UpdateResourceRequest;
use App\Repositories\Interfaces\ResourceRepositoryInterface;

class ResourceController extends Controller
{
    protected $resourceRepository;

    public function __construct(ResourceRepositoryInterface $resourceRepository)
    {
        $this->resourceRepository = $resourceRepository;
    }

    /**
     * Summary of index
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $resources = $this->resourceRepository->getAll();
        return response()->json($resources);
    }

    /**
     * Summary of store
     * @param \App\Http\Requests\Api\CreateResourceRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(CreateResourceRequest $request)
    {
        $resource = $this->resourceRepository->create($request->validated());
        return response()->json($resource, 201);
    }

    /**
     * Summary of show
     * @param int $id
     * @return mixed|\Illuminate\Http\JsonResponse
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
     * Summary of update
     * @param \App\Http\Requests\Api\UpdateResourceRequest $request
     * @param int $id
     * @return mixed|\Illuminate\Http\JsonResponse
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
     * Summary of destroy
     * @param int $id
     * @return mixed|\Illuminate\Http\JsonResponse
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

    public function checkAvailability(CheckAvailabilityRequest $request, int $resourceId)
    {
        try {
            $data = $request->validated();
            $isAvailable = $this->resourceRepository->checkAvailability(
                $resourceId,
                $data['reserved_at'],
                $data['duration']
            );
            return response()->json(['available' => $isAvailable]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Resource not found'], 404);
        }
    }
}
