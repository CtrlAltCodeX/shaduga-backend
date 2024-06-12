<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateModuleAPIRequest;
use App\Http\Requests\API\UpdateModuleAPIRequest;
use App\Models\Module;
use App\Repositories\ModuleRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;

/**
 * Class ModuleAPIController
 */
class ModuleAPIController extends AppBaseController
{
    private ModuleRepository $moduleRepository;

    public function __construct(ModuleRepository $moduleRepo)
    {
        $this->moduleRepository = $moduleRepo;
    }

    public function index(Request $request): JsonResponse
    {
        $modules = $this->moduleRepository->with('quest')->all(
            ['*'],
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse('Modules retrieved successfully', $modules->toArray());
    }

    /**
     * @OA\Post(
     *     path="/api/modules",
     *     summary="Store a newly created Module in storage",
     *     tags={"Modules"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "desc", "quest_id"},
     *             @OA\Property(
     *                 property="title",
     *                 type="string",
     *                 description="Title of the module"
     *             ),
     *             @OA\Property(
     *                 property="desc",
     *                 type="string",
     *                 description="Description of the module"
     *             ),
     *             @OA\Property(
     *                 property="community_id",
     *                 type="integer",
     *                 description="community ID of the related community"
     *             ),
     *             @OA\Property(
     *                 property="user_id",
     *                 type="integer",
     *                 description="user ID of the related community"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Module saved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=true
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="title",
     *                     type="string",
     *                     description="Title of the module"
     *                 ),
     *                 @OA\Property(
     *                     property="desc",
     *                     type="string",
     *                     description="Description of the module"
     *                 ),
     *                 @OA\Property(
     *                     property="quest_id",
     *                     type="integer",
     *                     description="ID of the related quest"
     *                 ),
     *                @OA\Property(
     *                     property="user_id",
     *                     type="integer",
     *                     description="user ID of the related community"
     *                  )
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Module saved successfully"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request"
     *     )
     * )
     */
    public function store(CreateModuleAPIRequest $request): JsonResponse
    {
        $input = $request->all();

        $module = $this->moduleRepository->create($input);

        return $this->sendResponse('Module saved successfully', $module->toArray());
    }

    /**
     * @OA\Get(
     *     path="/api/modules/{id}",
     *     operationId="getModuleById",
     *     tags={"Modules"},
     *     summary="Display the specified Module",
     *     description="Get a module by its ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="The ID of the community"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="title", type="string"),
     *                 @OA\Property(property="desc", type="string"),
     *                 @OA\Property(property="community_id", type="integer")
     *             ),
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Module not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="message", type="string")
     *         )
     *     )
     * )
     */
    public function show($id): JsonResponse
    {
        /** @var Module $module */
        $module = $this->moduleRepository->with('quest')->findWhere(['community_id' => $id]);

        if (empty($module)) {
            return $this->sendError('Module not found');
        }

        return $this->sendResponse('Module retrieved successfully', $module->toArray());
    }

    /**
     * Update the specified Module in storage.
     * PUT/PATCH /modules/{id}
     */
    public function update($id, UpdateModuleAPIRequest $request): JsonResponse
    {
        $input = $request->all();

        /** @var Module $module */
        $module = $this->moduleRepository->find($id);

        if (empty($module)) {
            return $this->sendError('Module not found');
        }

        $module = $this->moduleRepository->update($input, $id);

        return $this->sendResponse('Module updated successfully', $module->toArray());
    }

    /**
     * Remove the specified Module from storage.
     * DELETE /modules/{id}
     *
     * @throws \Exception
     */
    public function destroy($id): JsonResponse
    {
        /** @var Module $module */
        $module = $this->moduleRepository->find($id);

        if (empty($module)) {
            return $this->sendError('Module not found');
        }

        $module->delete();

        return $this->sendResponse('Module deleted successfully');
    }
}
