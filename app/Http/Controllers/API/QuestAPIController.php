<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateQuestAPIRequest;
use App\Http\Requests\API\UpdateQuestAPIRequest;
use App\Models\Quest;
use App\Repositories\QuestRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Models\QuestAdditional;
use Illuminate\Http\UploadedFile;

/**
 * Class QuestAPIController
 */
class QuestAPIController extends AppBaseController
{
    private QuestRepository $questRepository;

    public function __construct(
        QuestRepository $questRepo,
        public QuestAdditional $questRepoAdditional
    ) {
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
        $quests = $this->questRepository->with('additionals')->all(
            ['*'],
            $request->get('skip'),
            $request->get('limit')
        );

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
     *             @OA\Property(property="status", type="integer", example=1),
     *             @OA\Property(property="user_id", type="integer", example="1"),
     *             @OA\Property(property="image", type="object", example=""),
     *             @OA\Property(property="module_id", type="string", example="1"),
     *             @OA\Property(
     *                 property="additionals",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="link", type="string", example="link"),
     *                     @OA\Property(property="task_type", type="string", example="link"),
     *                     @OA\Property(property="number_invitation", type="string", example=1),
     *                     @OA\Property(property="description", type="string", example="For API"),
     *                     @OA\Property(property="endpoint", type="string", example="For API"),
     *                     @OA\Property(property="methods", type="string", example="For API"),
     *                     @OA\Property(property="api_key", type="string", example="For API"),
     *                     @OA\Property(property="partnership", type="string", example="for partnership link"),
     *                     @OA\Property(property="request_type", type="string", example="text, url, number"),
     *                     @OA\Property(property="correct_answer", type="string", example="for answer"),
     *                     @OA\Property(property="stars", type="string", example="2"),
     *                     @OA\Property(property="steps", type="string", example="0 to 10"),
     *                     @OA\Property(property="labels", type="string", example="0 to 10"),
     *                     @OA\Property(
     *                         property="files",
     *                         type="array",
     *                         @OA\Items(type="object"),
     *                         example={"object", "object", "object"}
     *                     )
     *                 )
     *             )
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
     *                 @OA\Property(property="image", type="object", example=""),
     *                 @OA\Property(property="module_id", type="string", example="1"),
     *                 @OA\Property(
     *                     property="additionals",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="link", type="string", example="link"),
     *                         @OA\Property(property="task_type", type="string", example="link"),
     *                         @OA\Property(property="number_invitation", type="string", example=1),
     *                         @OA\Property(property="description", type="string", example="For API"),
     *                         @OA\Property(property="endpoint", type="string", example="For API"),
     *                         @OA\Property(property="methods", type="string", example="For API"),
     *                         @OA\Property(property="api_key", type="string", example="For API"),
     *                         @OA\Property(property="partnership", type="string", example="for partnership link"),
     *                         @OA\Property(property="request_type", type="string", example="text, url, number"),
     *                         @OA\Property(property="correct_answer", type="string", example="for answer"),
     *                         @OA\Property(property="stars", type="string", example="2"),
     *                         @OA\Property(property="steps", type="string", example="0 to 10"),
     *                         @OA\Property(property="labels", type="string", example="0 to 10"),
     *                         @OA\Property(
     *                             property="files",
     *                             type="array",
     *                             @OA\Items(type="object"),
     *                             example={"object", "object", "object"}
     *                         )
     *                     )
     *                 )
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

        if ($file = $request->file('image')) {
            if ($file instanceof UploadedFile) {
                $profileImage = time() . "." . $file->getClientOriginalExtension();

                $file->move('storage/quest/', $profileImage);

                $input['image'] = "/storage/quest/" . "$profileImage";
            }
        }

        $quest = $this->questRepository->create($input);

        foreach ($input['additionals'] as $additionals) {
            $additionals['quest_id'] = $quest->id;

            if (isset($additionals['files'])) {
                $files = $additionals['files'];
                $images = [];
                foreach ($additionals['files'] as $file) {
                    if ($file instanceof UploadedFile) {
                        $getFile = time() . "." . $file->getClientOriginalExtension();

                        $file->move('storage/quest/', $getFile);

                        $images['image'][] = "/storage/quest/" . "$getFile";
                    }
                }

                if (isset($images['image'])) {
                    $additionals['files'] = implode(',', $images['image']);
                }
            }

            $this->questRepoAdditional->create($additionals);
        }

        $questAdditional = QuestAdditional::where('quest_id', $quest->id)
            ->get()
            ->toArray();

        $data = $quest->toArray();
        $data['additional'] = $questAdditional;

        return $this->sendResponse($data, 'Quest saved successfully');
    }


    /**
     * @OA\Get(
     *     path="/api/quests/{module_id}",
     *     summary="Display the specified Quest",
     *     description="Retrieve a quest by its module_id.",
     *     operationId="getQuestBymodule_id",
     *     tags={"Quests"},
     *     @OA\Parameter(
     *         name="module_id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         description="The module_id of the quest"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Quest retrieved successfully",
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
     *                 @OA\Property(property="image", type="object", example="{}"),
     *                 @OA\Property(property="module_id", type="integer", example="1"),
     *                 @OA\Property(
     *                     property="additionals",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="link", type="string", example="link"),
     *                         @OA\Property(property="task_type", type="string", example="link"),
     *                         @OA\Property(property="number_invitation", type="string", example=1),
     *                         @OA\Property(property="description", type="string", example="For API"),
     *                         @OA\Property(property="endpoint", type="string", example="For API"),
     *                         @OA\Property(property="methods", type="string", example="For API"),
     *                         @OA\Property(property="api_key", type="string", example="For API"),
     *                         @OA\Property(property="partnership", type="string", example="for partnership link"),
     *                         @OA\Property(property="request_type", type="string", example="text, url, number"),
     *                         @OA\Property(property="correct_answer", type="string", example="for answer"),
     *                         @OA\Property(property="stars", type="string", example="2"),
     *                         @OA\Property(property="steps", type="string", example="0 to 10"),
     *                         @OA\Property(property="labels", type="string", example="0 to 10"),
     *                         @OA\Property(
     *                             property="files",
     *                             type="array",
     *                             @OA\Items(type="object"),
     *                             example={"object", "object", "object"}
     *                         )
     *                     )
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="Quest saved successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Quest not found",
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
     *                 example="Quest not found"
     *             )
     *         )
     *     )
     * )
     */
    public function show($module_id): JsonResponse
    {
        /** @var Quest $quest */
        $quest = $this->questRepository->with('additionals')->findByField('module_id', $module_id);

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
