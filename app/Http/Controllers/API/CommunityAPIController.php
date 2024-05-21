<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateCommunityAPIRequest;
use App\Http\Requests\API\UpdateCommunityAPIRequest;
use App\Models\Community;
use App\Repositories\CommunityRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;

/**
 * Class CommunityAPIController
 */
class CommunityAPIController extends AppBaseController
{
    private CommunityRepository $communityRepository;

    public function __construct(CommunityRepository $communityRepo)
    {
        $this->communityRepository = $communityRepo;
    }

    /**
     * Display a listing of the Communities.
     * GET|HEAD /api/communities
     * 
     * @OA\Get(
     *      path="/api/communities",
     *      operationId="getCommunitiesList",
     *      tags={"Communities"},
     *      summary="Get list of communities",
     *      description="Returns a list of communities",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Community"))
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not found"
     *      )
     * )
     * * @OA\Schema(
     *      schema="Community",
     *      title="Community",
     *      description="Community model",
     *      @OA\Property(
     *          property="id",
     *          type="integer",
     *          format="int64",
     *          description="ID"
     *      ),
     *      @OA\Property(
     *          property="name",
     *          type="string",
     *          description="Name of the community"
     *      ),
     *      @OA\Property(
     *          property="logo",
     *          type="string",
     *          description="Logo of the community"
     *      ),
     *      @OA\Property(
     *          property="description",
     *          type="string",
     *          description="description of the community"
     *      ),
     *      @OA\Property(
     *          property="category_id",
     *          type="integer",
     *          description="category_id of the community"
     *      ),
     *      @OA\Property(
     *          property="is_blockchain",
     *          type="integer",
     *          description="is_blockchain of the community"
     *      ),
     *      @OA\Property(
     *          property="website",
     *          type="string",
     *          description="website of the community"
     *      ),
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $communities = $this->communityRepository->all(
            ['*'],
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($communities->toArray(), 'Communities retrieved successfully');
    }

    /**
     * Store a newly created Community in storage.
     * POST /api/communities
     * 
     * @OA\Post(
     *      path="/api/communities",
     *      operationId="storeCommunity",
     *      tags={"Communities"},
     *      summary="Store a new community",
     *      description="Creates a new community",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/Community")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Community created successfully",
     *          @OA\JsonContent(ref="#/components/schemas/Community")
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity"
     *      ),
     *      security={
     *          {"bearerAuth": {}}
     *      }
     * )
     */
    public function store(CreateCommunityAPIRequest $request): JsonResponse
    {
        $input = $request->all();

        $community = $this->communityRepository->create($input);

        return $this->sendResponse($community->toArray(), 'Community saved successfully');
    }

    /**
     * Display the specified Community.
     * GET|HEAD /api/communities/{id}
     * 
     * @OA\Get(
     *      path="/api/communities/{id}",
     *      operationId="getCommunityById",
     *      tags={"Communities"},
     *      summary="Get a specific community",
     *      description="Returns the specified community",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID of the community",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/Community")
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not found"
     *      )
     * )
     */
    public function show($id): JsonResponse
    {
        /** @var Community $community */
        $community = $this->communityRepository->find($id);

        if (empty($community)) {
            return $this->sendError('Community not found');
        }

        return $this->sendResponse($community->toArray(), 'Community retrieved successfully');
    }

    /**
     * Update the specified Community in storage.
     * PUT/PATCH /api/communities/{id}
     * 
     */
    public function update($id, UpdateCommunityAPIRequest $request): JsonResponse
    {
        $input = $request->all();

        /** @var Community $community */
        $community = $this->communityRepository->find($id);

        if (empty($community)) {
            return $this->sendError('Community not found');
        }

        $community = $this->communityRepository->update($input, $id);

        return $this->sendResponse($community->toArray(), 'Community updated successfully');
    }

    /**
     * Remove the specified Community from storage.
     * DELETE /api/communities/{id}
     * 
     * @throws \Exception
     */
    public function destroy($id): JsonResponse
    {
        /** @var Community $community */
        $community = $this->communityRepository->find($id);

        if (empty($community)) {
            return $this->sendError('Community not found');
        }

        $community->delete();

        return $this->sendSuccess('Community deleted successfully');
    }
}
