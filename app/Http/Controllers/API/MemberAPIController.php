<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateMemberAPIRequest;
use App\Http\Requests\API\UpdateMemberAPIRequest;
use App\Models\Member;
use App\Repositories\MemberRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;

/**
 * Class MemberAPIController
 */
class MemberAPIController extends AppBaseController
{
    private MemberRepository $memberRepository;

    public function __construct(MemberRepository $memberRepo)
    {
        $this->memberRepository = $memberRepo;
    }

    /**
     * Display a listing of the Members.
     * GET|HEAD /api/members
     * 
     * * @OA\Get(
     *     path="/api/members",
     *     summary="Get all members",
     *     tags={"Members"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Member")
     *         )
     *     )
     * )
     * * @OA\Schema(
     *     schema="Member",
     *     title="Member",
     *     description="Member model",
     *     @OA\Property(
     *         property="id",
     *         type="integer",
     *         format="int64",
     *         description="ID of the member"
     *     ),
     * @OA\Property(
     *         property="community_id",
     *         type="integer",
     *         description="community_id of the member"
     *     ),
     *     @OA\Property(
     *         property="user_id",
     *         type="integer",
     *         description="user_id of the member"
     *     ),
     *     @OA\Property(
     *         property="join_date",
     *         type="Date",
     *         description="join_date of the member"
     *     ),
     *     @OA\Property(
     *         property="status",
     *         type="Integer",
     *         description="status of the member"
     *     ),
     *     @OA\Property(
     *         property="role",
     *         type="string",
     *         description="role of the member"
     *     ),
     *     @OA\Property(
     *         property="last_active",
     *         type="string",
     *         description="last_active of the member"
     *     ),
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $members = $this->memberRepository->all();

        return $this->sendResponse($members->toArray(), 'Members retrieved successfully');
    }

    /**
     * Store a newly created Member in storage.
     * POST /members
     */
    public function store(CreateMemberAPIRequest $request): JsonResponse
    {
        $input = $request->all();

        $member = $this->memberRepository->create($input);

        return $this->sendResponse($member->toArray(), 'Member saved successfully');
    }

    /**
     * Display the specified Member.
     * GET|HEAD /members/{id}
     */
    public function show($id): JsonResponse
    {
        /** @var Member $member */
        $member = $this->memberRepository->find($id);

        if (empty($member)) {
            return $this->sendError('Member not found');
        }

        return $this->sendResponse($member->toArray(), 'Member retrieved successfully');
    }

    /**
     * Update the specified Member in storage.
     * PUT/PATCH /members/{id}
     */
    public function update($id, UpdateMemberAPIRequest $request): JsonResponse
    {
        $input = $request->all();

        /** @var Member $member */
        $member = $this->memberRepository->find($id);

        if (empty($member)) {
            return $this->sendError('Member not found');
        }

        $member = $this->memberRepository->update($input, $id);

        return $this->sendResponse($member->toArray(), 'Member updated successfully');
    }

    /**
     * Remove the specified Member from storage.
     *
     * @OA\Delete(
     *     path="/api/members/{id}",
     *     summary="Delete a member",
     *     tags={"Members"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the member to delete",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Member deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Member not found"
     *     )
     * )
     *
     * @param  int  $id
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy($id): JsonResponse
    {
        /** @var Member $member */
        $member = $this->memberRepository->find($id);

        if (empty($member)) {
            return $this->sendError('Member not found');
        }

        $member->delete();

        return $this->sendSuccess('Member deleted successfully');
    }
}
