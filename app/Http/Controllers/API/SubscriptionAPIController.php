<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateSubscriptionAPIRequest;
use App\Http\Requests\API\UpdateSubscriptionAPIRequest;
use App\Models\Subscription;
use App\Repositories\SubscriptionRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;

/**
 * Class SubscriptionAPIController
 */
class SubscriptionAPIController extends AppBaseController
{
    private SubscriptionRepository $subscriptionRepository;

    public function __construct(SubscriptionRepository $subscriptionRepo)
    {
        $this->subscriptionRepository = $subscriptionRepo;
    }

    /**
     * @OA\Get(
     *     path="/api/subscriptions",
     *     summary="Display a listing of the Subscriptions",
     *     tags={"Subscriptions"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful retrieval of subscriptions",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Subscription")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found"
     *     )
     * )
     * * @OA\Components(
     *     @OA\Schema(
     *         schema="Subscription",
     *         type="object",
     *         description="Subscription model",
     *         required={"id", "name", "status"},
     *         @OA\Property(
     *             property="id",
     *             type="integer",
     *             description="ID of the subscription"
     *         ),
     *         @OA\Property(
     *             property="name",
     *             type="string",
     *             description="Name of the subscription"
     *         ),
     *         @OA\Property(
     *             property="status",
     *             type="integer",
     *             description="Status of the subscription"
     *         ),
     *         @OA\Property(
     *             property="created_at",
     *             type="date",
     *             format="date-time",
     *             description="Creation timestamp"
     *         ),
     *         @OA\Property(
     *             property="updated_at",
     *             type="date",
     *             format="date-time",
     *             description="Last update timestamp"
     *         )
     *     ),
     *     @OA\Schema(
     *         schema="CreateSubscriptionAPIRequest",
     *         type="object",
     *         description="Create subscription request",
     *         required={"name", "status"},
     *         @OA\Property(
     *             property="user_id",
     *             type="string",
     *             description="user_id of the subscription"
     *         ),
     *         @OA\Property(
     *             property="plan_id",
     *             type="string",
     *             description="plan_id of the subscription"
     *         ),
     *         @OA\Property(
     *             property="start_date",
     *             type="date",
     *             description="start_date of the subscription"
     *         ),
     *         @OA\Property(
     *             property="end_date",
     *             type="date",
     *             description="end_date of the subscription"
     *         ),
     *         @OA\Property(
     *             property="status",
     *             type="string",
     *             description="Status of the subscription"
     *         )
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $subscriptions = $this->subscriptionRepository->all(
            ['*'],
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($subscriptions->toArray(), 'Subscriptions retrieved successfully');
    }

    /**
     * @OA\Post(
     *     path="/api/subscriptions",
     *     summary="Store a newly created Subscription in storage",
     *     tags={"Subscriptions"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CreateSubscriptionAPIRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Subscription saved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Subscription")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Entity"
     *     )
     * )
     */
    public function store(CreateSubscriptionAPIRequest $request): JsonResponse
    {
        $input = $request->all();

        $subscription = $this->subscriptionRepository->create($input);

        return $this->sendResponse($subscription->toArray(), 'Subscription saved successfully');
    }

    /**
     * Display the specified Subscription.
     * GET|HEAD /subscriptions/{id}
     */
    public function show($id): JsonResponse
    {
        /** @var Subscription $subscription */
        $subscription = $this->subscriptionRepository->find($id);

        if (empty($subscription)) {
            return $this->sendError('Subscription not found');
        }

        return $this->sendResponse($subscription->toArray(), 'Subscription retrieved successfully');
    }

    /**
     * Update the specified Subscription in storage.
     * PUT/PATCH /subscriptions/{id}
     */
    public function update($id, UpdateSubscriptionAPIRequest $request): JsonResponse
    {
        $input = $request->all();

        /** @var Subscription $subscription */
        $subscription = $this->subscriptionRepository->find($id);

        if (empty($subscription)) {
            return $this->sendError('Subscription not found');
        }

        $subscription = $this->subscriptionRepository->update($input, $id);

        return $this->sendResponse($subscription->toArray(), 'Subscription updated successfully');
    }

    /**
     * Remove the specified Subscription from storage.
     * DELETE /subscriptions/{id}
     *
     * @throws \Exception
     */
    public function destroy($id): JsonResponse
    {
        /** @var Subscription $subscription */
        $subscription = $this->subscriptionRepository->find($id);

        if (empty($subscription)) {
            return $this->sendError('Subscription not found');
        }

        $subscription->delete();

        return $this->sendSuccess('Subscription deleted successfully');
    }
}
