<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateQuestAPIRequest;
use App\Http\Requests\API\UpdateQuestAPIRequest;
use App\Models\Quest;
use App\Repositories\QuestRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;

/**
 * Class QuestAPIController
 */
class QuestAPIController extends AppBaseController
{
    private QuestRepository $questRepository;

    public function __construct(QuestRepository $questRepo)
    {
        $this->questRepository = $questRepo;
    }

    /**
     * @OA\Get(
     *     path="/api/quests",
     *     summary="Display a listing of the Quests",
     *     operationId="getQuestsList",
     *     tags={"Quests"},
     *     @OA\Response(
     *         response=200,
     *         description="Quests retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request"
     *     ),
     * security={{ "bearerAuth":{} }}
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $quests = $this->questRepository->all();

        return $this->sendResponse($quests->toArray(), 'Quests retrieved successfully');
    }

    /**
     * @OA\Post(
     *     path="/api/quests",
     *     summary="Store a newly created Quest in storage",
     *     operationId="storeQuest",
     *     tags={"Quests"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"name", "description", "difficulty", "recurrence", "cooldown", "claim_time", "condition", "reward", "module", "sprint", "status"},
     *             @OA\Property(property="name", type="string", example="Quest name"),
     *             @OA\Property(property="description", type="string", example="Quest description"),
     *             @OA\Property(property="difficulty", type="string", example="easy"),
     *             @OA\Property(property="recurrence", type="string", example="daily"),
     *             @OA\Property(property="cooldown", type="integer", example=24),
     *             @OA\Property(property="claim_time", type="string", format="date-time", example="2024-05-18T00:00:00Z"),
     *             @OA\Property(property="condition", type="string", example="Complete 3 tasks"),
     *             @OA\Property(property="reward", type="string", example="100 points"),
     *             @OA\Property(property="module", type="string", example="Module A"),
     *             @OA\Property(property="sprint", type="integer", example=2),
     *             @OA\Property(property="status", type="integer", example="1"),
     *             @OA\Property(property="user_id", type="integer", example="1")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Quest saved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object", 
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Quest name"),
     *                 @OA\Property(property="description", type="string", example="Quest description"),
     *                 @OA\Property(property="difficulty", type="string", example="easy"),
     *                 @OA\Property(property="recurrence", type="string", example="daily"),
     *                 @OA\Property(property="cooldown", type="integer", example=24),
     *                 @OA\Property(property="claim_time", type="string", format="date-time", example="2024-05-18T00:00:00Z"),
     *                 @OA\Property(property="condition", type="string", example="Complete 3 tasks"),
     *                 @OA\Property(property="reward", type="string", example="100 points"),
     *                 @OA\Property(property="module", type="string", example="Module A"),
     *                 @OA\Property(property="sprint", type="integer", example=2),
     *                 @OA\Property(property="status", type="integer", example="1"),
     *                 @OA\Property(property="user_id", type="integer", example="1"),
     *             ),
     *             @OA\Property(property="message", type="string", example="Quest saved successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Bad request")
     *         )
     *     )
     * )
     */
    public function store(CreateQuestAPIRequest $request): JsonResponse
    {
        $input = $request->all();

        $quest = $this->questRepository->create($input);

        return $this->sendResponse($quest->toArray(), 'Quest saved successfully');
    }


    /**
     * Display the specified Quest.
     * GET|HEAD /quests/{id}
     */
    public function show($id): JsonResponse
    {
        /** @var Quest $quest */
        $quest = $this->questRepository->find($id);

        if (empty($quest)) {
            return $this->sendError('Quest not found');
        }

        return $this->sendResponse($quest->toArray(), 'Quest retrieved successfully');
    }

    /**
     * Update the specified Quest in storage.
     * PUT/PATCH /quests/{id}
     */
    public function update($id, UpdateQuestAPIRequest $request): JsonResponse
    {
        $input = $request->all();

        /** @var Quest $quest */
        $quest = $this->questRepository->find($id);

        if (empty($quest)) {
            return $this->sendError('Quest not found');
        }

        $quest = $this->questRepository->update($input, $id);

        return $this->sendResponse($quest->toArray(), 'Quest updated successfully');
    }

    /**
     * @OA\Delete(
     *     path="/api/quests/{id}",
     *     summary="Remove the specified Quests from storage",
     *     description="Deletes a quests by its ID",
     *     operationId="destroyQuests",
     *     tags={"Quests"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the quests to be deleted",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Quests deleted successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=true
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Quests deleted successfully"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Quests not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=false
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Quests not found"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=false
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Internal server error"
     *             )
     *         )
     *     )
     * )
     */
    public function destroy($id): JsonResponse
    {
        /** @var Quest $quest */
        $quest = $this->questRepository->find($id);

        if (empty($quest)) {
            return $this->sendError('Quest not found');
        }

        $quest->delete();

        return $this->sendResponse('Quest deleted successfully');
    }
}
