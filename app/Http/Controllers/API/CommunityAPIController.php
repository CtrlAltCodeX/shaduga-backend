<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateCommunityAPIRequest;
use App\Http\Requests\API\UpdateCommunityAPIRequest;
use App\Models\Community;
use App\Repositories\CommunityRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Mail\InviteMail;
use App\Repositories\MemberRepository;
use App\Repositories\ModuleRepository;
use Illuminate\Support\Facades\Mail;

/**
 * Class CommunityAPIController
 */
class CommunityAPIController extends AppBaseController
{
    private CommunityRepository $communityRepository;

    public function __construct(
        CommunityRepository $communityRepo,
        public MemberRepository $memberRepository,
        public ModuleRepository $moduleRepository
    ) {
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
     *          type="object",
     *          description="Logo of the community"
     *      ),
     *      @OA\Property(
     *          property="description",
     *          type="string",
     *          description="description of the community"
     *      ),
     *      @OA\Property(
     *           property="categories",
     *           type="array",
     *           @OA\Items(type="string"),
     *           example={"categories", "categories", "categories"}
     *       ),
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
     *      @OA\Property(
     *          property="link",
     *          type="string",
     *          description="link of the community"
     *      ),
     * @OA\Property(
     *     property="invitation",
     *     type="array",
     *     description="Invitation of the community",
     *     @OA\Items(
     *         type="array",
     *         @OA\Items(type="integer")
     *     ),
     *     example={{"admin@example.com", "Role"}, {"user@example.com", "Role"}}
     * )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $communities = $this->communityRepository->with('members')->all(
            ['*'],
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse('Communities retrieved successfully', $communities->toArray());
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
     * )
     */
    public function store(CreateCommunityAPIRequest $request): JsonResponse
    {
        try {
            $input = $request->all();

            if ($file = $request->file('logo')) {
                $profileImage = time() . "." . $file->getClientOriginalExtension();

                $file->move('storage/community/', $profileImage);

                $input['logo'] = "/storage/community/" . "$profileImage";
            }

            $input['categories'] = implode(',', $input['categories']);

            if (isset($input['invitation'])) {
                $input['invites'] = json_encode($input['invitation']);
            }

            $input['user_id'] = auth()->user()->id;

            $community = $this->communityRepository->create($input);

            $input['link'] = url('/') . "/cw/$community->name/" . $community->id;

            $communityUpdate = $this->communityRepository->find($community->id);

            $communityUpdate->update(['link' => $input['link']]);

            $data = $community->toArray();
            $data['link'] = $input['link'];

            if (isset($input['invitation'])) {
                foreach ($input['invitation'] as $invities) {
                    if (isset($invities[0])) {
                        Mail::to($invities[0])->send(new InviteMail($input['link']));
                    }
                }
            }

            $defaultModules = ['Rewards Section', 'Social Support', 'Onboarding Module', 'Tutorial'];

            foreach ($defaultModules as $module) {
                $this->moduleRepository->create([
                    'name' => $module,
                    'desc' => 'This is the Default Modules',
                    'community_id' => $community->id,
                    'user_id' => auth()->user()->id,
                ]);
            }

            return $this->sendResponse('Community saved successfully', $data);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
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

        $memberExist = $this->memberRepository->findByField(['community_id' => $community->id, 'user_id' => auth()->user()->id]);

        $commArr = $community->toArray();
        $commArr['joined']  = count($memberExist) ? true : false;

        if (empty($community)) {
            return $this->sendError('Community not found');
        }

        return $this->sendResponse('Community retrieved successfully', $commArr);
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

        return $this->sendResponse('Community updated successfully', $community->toArray());
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

        return $this->sendResponse('Community deleted successfully');
    }

    /**
     * @OA\Get(
     *     path="/api/user/communities/{id}",
     *     summary="Get current communities for a user",
     *     description="Returns all communities for a specific user",
     *     operationId="getCurrentCommunities",
     *     tags={"Communities"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the user",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(
     *                     property="logo",
     *                     type="string",
     *                     description="Logo URL of the community"
     *                 ),
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     description="Name of the community"
     *                 ),
     *                 @OA\Property(
     *                     property="description",
     *                     type="string",
     *                     description="Description of the community"
     *                 ),
     *                 @OA\Property(
     *                     property="is_blockchain",
     *                     type="boolean",
     *                     description="Indicates if the community is blockchain-related"
     *                 ),
     *                 @OA\Property(
     *                     property="website",
     *                     type="string",
     *                     description="Website URL of the community"
     *                 ),
     *                 @OA\Property(
     *                     property="categories",
     *                     type="array",
     *                     @OA\Items(
     *                         type="string"
     *                     ),
     *                     description="Categories associated with the community"
     *                 ),
     *                 @OA\Property(
     *                     property="invites",
     *                     type="integer",
     *                     description="Number of invites available in the community"
     *                 ),
     *                 @OA\Property(
     *                     property="link",
     *                     type="string",
     *                     description="Link to the community"
     *                 ),
     *                 @OA\Property(
     *                     property="user_id",
     *                     type="integer",
     *                     description="ID of the user who owns the community"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    public function getCurrentCommunities($id)
    {
        $community = $this->communityRepository
            ->withCount('members')
            ->findWhere(["user_id" => $id])
            ->toArray();

        $communityMembers = $this->memberRepository->findWhere(['user_id' => $id]);

        $communities = [];

        foreach ($communityMembers as $members) {
            $communities[] = $this->communityRepository->find($members->community_id)->toArray();
        }

        $data = array_merge($community, $communities);

        return $this->sendResponse('All Communities', $data);
    }
    
    /**
     * @OA\Get(
     *     path="/api/communities/{community_id}/members",
     *     summary="Get all members by community ID",
     *     tags={"Communities"},
     *     @OA\Parameter(
     *         name="community_id",
     *         in="path",
     *         description="ID of the community",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/CommunityWithMembers")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Community not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     * * @OA\Schema(
     *     schema="CommunityWithMembers",
     *     type="object",
     *     @OA\Property(
     *         property="id",
     *         type="integer",
     *         description="Community ID"
     *     ),
     *     @OA\Property(
     *         property="name",
     *         type="string",
     *         description="Community name"
     *     ),
     *     @OA\Property(
     *         property="members",
     *         type="array",
     *         @OA\Items(ref="#/components/schemas/Member")
     *     )
     * )
     */
    public function memberByCommunity($community_id)
    {
        $communitiesWithMembers = $this->communityRepository->with('members.user')->find($community_id);

        return $this->sendResponse('All Communities with Members', $communitiesWithMembers);
    }

    /**
     * @OA\Post(
     *     path="/api/send/invitation",
     *     summary="Send invitations",
     *     description="Sends invitations via email",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="link",
     *                 type="string",
     *                 description="The email address to send the invite to",
     *                 example="example@example.com"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Invitation sent successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Invitation Send"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="error",
     *                 type="string",
     *                 example="Invalid request"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="error",
     *                 type="string",
     *                 example="Server error"
     *             )
     *         )
     *     )
     * )
     */
    public function sendInvities()
    {
        Mail::to(request()->link)->send(new InviteMail(request()->link));

        return $this->sendResponse('Invitation Send');
    }
}
