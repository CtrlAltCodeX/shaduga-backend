<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateLeaderBoardAPIRequest;
use App\Http\Requests\API\UpdateLeaderBoardAPIRequest;
use App\Models\LeaderBoard;
use App\Repositories\LeaderBoardRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Repositories\MemberRepository;

/**
 * Class LeaderBoardAPIController
 */
class LeaderBoardAPIController extends AppBaseController
{
    private LeaderBoardRepository $leaderBoardRepository;

    public function __construct(
        LeaderBoardRepository $leaderBoardRepo,
        public MemberRepository $memberRepo
    ) {
        $this->leaderBoardRepository = $leaderBoardRepo;
    }

    /**
     * @OA\Get(
     *     path="/api/leader-boards",
     *     summary="Display a listing of the LeaderBoards",
     *     tags={"LeaderBoards"},
     *     @OA\Response(
     *         response=200,
     *         description="Leader Boards retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/LeaderBoard")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     ),
     * )
     * 
     * @OA\Schema(
     *     schema="LeaderBoard",
     *     type="object",
     *     title="LeaderBoard",
     *     description="LeaderBoard model",
     *     required={"id", "name", "score"},
     *     @OA\Property(
     *         property="id",
     *         type="integer",
     *         description="LeaderBoard ID"
     *     ),
     *     @OA\Property(
     *         property="user_id",
     *         type="integer",
     *         description="user_id of the LeaderBoard"
     *     ),
     *     @OA\Property(
     *         property="score",
     *         type="integer",
     *         description="Score of the LeaderBoard"
     *     ),
     *     @OA\Property(
     *         property="rank",
     *         type="string",
     *         description="rank of the LeaderBoard"
     *     ),
     *     @OA\Property(
     *         property="level",
     *         type="string",
     *         description="level of the LeaderBoard"
     *     )
     * )
     */
    // public function index(Request $request): JsonResponse
    // {
    //     $leaderBoards = $this->leaderBoardRepository->all();

    //     return $this->sendResponse($leaderBoards->toArray(), 'Leader Boards retrieved successfully');
    // }

    /**
     * Store a newly created LeaderBoard in storage.
     * POST /leader-boards
     */
    public function store(CreateLeaderBoardAPIRequest $request): JsonResponse
    {
        $input = $request->all();

        $leaderBoard = $this->leaderBoardRepository->create($input);

        return $this->sendResponse($leaderBoard->toArray(), 'Leader Board saved successfully');
    }

    /**
     * @OA\Get(
     *     path="/api/leader-boards/{id}",
     *     summary="Display the specified LeaderBoard",
     *     operationId="showLeaderBoard",
     *     tags={"LeaderBoards"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the LeaderBoard",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Leader Board retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean"
     *             ),
     *     @OA\Property(
     *         property="id",
     *         type="integer",
     *         description="LeaderBoard ID"
     *     ),
     *     @OA\Property(
     *         property="user_id",
     *         type="integer",
     *         description="user_id of the LeaderBoard"
     *     ),
     *     @OA\Property(
     *         property="score",
     *         type="integer",
     *         description="Score of the LeaderBoard"
     *     ),
     *     @OA\Property(
     *         property="rank",
     *         type="string",
     *         description="rank of the LeaderBoard"
     *     ),
     *     @OA\Property(
     *         property="level",
     *         type="string",
     *         description="level of the LeaderBoard"
     *     ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Leader Board not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean"
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string"
     *             )
     *         )
     *     )
     * )
     */
    public function show($id): JsonResponse
    {
        /** @var LeaderBoard $leaderBoard */
        $leaderBoard = $this->memberRepo->findWhere(['community_id' => $id]);

        if (empty($leaderBoard)) {
            return $this->sendError('Leader Board not found');
        }

        return $this->sendResponse($leaderBoard->toArray(), 'Leader Board retrieved successfully');
    }


    /**
     * Update the specified LeaderBoard in storage.
     * PUT/PATCH /leader-boards/{id}
     */
    public function update($id, UpdateLeaderBoardAPIRequest $request): JsonResponse
    {
        $input = $request->all();

        /** @var LeaderBoard $leaderBoard */
        $leaderBoard = $this->leaderBoardRepository->find($id);

        if (empty($leaderBoard)) {
            return $this->sendError('Leader Board not found');
        }

        $leaderBoard = $this->leaderBoardRepository->update($input, $id);

        return $this->sendResponse($leaderBoard->toArray(), 'LeaderBoard updated successfully');
    }

    /**
     * Remove the specified LeaderBoard from storage.
     * DELETE /leader-boards/{id}
     *
     * @throws \Exception
     */
    public function destroy($id): JsonResponse
    {
        /** @var LeaderBoard $leaderBoard */
        $leaderBoard = $this->leaderBoardRepository->find($id);

        if (empty($leaderBoard)) {
            return $this->sendError('Leader Board not found');
        }

        $leaderBoard->delete();

        return $this->sendSuccess('Leader Board deleted successfully');
    }
}
