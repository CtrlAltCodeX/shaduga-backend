<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateReviewAPIRequest;
use App\Http\Requests\API\UpdateReviewAPIRequest;
use App\Models\Review;
use App\Repositories\ReviewRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;

/**
 * Class ReviewAPIController
 */
class ReviewAPIController extends AppBaseController
{
    private ReviewRepository $reviewRepository;

    public function __construct(ReviewRepository $reviewRepo)
    {
        $this->reviewRepository = $reviewRepo;
    }

    /**
     * Display a listing of the Reviews.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $reviews = $this->reviewRepository->all(
            ['*'],
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse('Reviews retrieved successfully', $reviews->toArray());
    }

    /**
     * @OA\Post(
     *     path="/api/reviews",
     *     summary="Store a new review",
     *     description="Create and store a new review in the system.",
     *     tags={"Reviews"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"user_id", "community_id", "star_rating", "body", "status", "bookmarked"},
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="community_id", type="integer", example=1),
     *             @OA\Property(property="star_rating", type="integer", example=5),
     *             @OA\Property(property="body", type="string", example="This is a review body."),
     *             @OA\Property(property="status", type="string", example="approved"),
     *             @OA\Property(property="bookmarked", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Review saved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Review saved successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="user_id", type="integer", example=1),
     *                 @OA\Property(property="community_id", type="integer", example=1),
     *                 @OA\Property(property="star_rating", type="integer", example=5),
     *                 @OA\Property(property="body", type="string", example="This is a review body."),
     *                 @OA\Property(property="status", type="string", example="approved"),
     *                 @OA\Property(property="bookmarked", type="boolean", example=true)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Invalid input")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="An error occurred")
     *         )
     *     )
     * )
     */
    public function store(CreateReviewAPIRequest $request): JsonResponse
    {
        $input = $request->all();

        $review = $this->reviewRepository->create($input);

        return $this->sendResponse('Review saved successfully', $review->toArray());
    }

    /**
     * Display the specified Review.
     * GET|HEAD /reviews/{id}
     */
    public function show($id): JsonResponse
    {
        /** @var Review $review */
        $review = $this->reviewRepository->find($id);

        if (empty($review)) {
            return $this->sendError('Review not found');
        }

        return $this->sendResponse('Review retrieved successfully', $review->toArray());
    }

    /**
     * Update the specified Review in storage.
     * PUT/PATCH /reviews/{id}
     */
    public function update($id, UpdateReviewAPIRequest $request): JsonResponse
    {
        $input = $request->all();

        /** @var Review $review */
        $review = $this->reviewRepository->find($id);

        if (empty($review)) {
            return $this->sendError('Review not found');
        }

        $review = $this->reviewRepository->update($input, $id);

        return $this->sendResponse('Review updated successfully', $review->toArray());
    }

    /**
     * Remove the specified Review from storage.
     * DELETE /reviews/{id}
     *
     * @throws \Exception
     */
    public function destroy($id): JsonResponse
    {
        /** @var Review $review */
        $review = $this->reviewRepository->find($id);

        if (empty($review)) {
            return $this->sendError('Review not found');
        }

        $review->delete();

        return $this->sendResponse('Review deleted successfully');
    }

    /**
     * @OA\Get(
     *     path="/api/reviews/{userId}/{communityId}",
     *     summary="Get a review by user and community",
     *     description="Retrieve a review based on user ID and community ID",
     *     operationId="getReviewByUserCommunity",
     *     tags={"Reviews"},
     *     @OA\Parameter(
     *         name="userId",
     *         in="path",
     *         description="ID of the user",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="communityId",
     *         in="path",
     *         description="ID of the community",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Review retrieved successfully"
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="user_id",
     *                     type="integer",
     *                     example=1
     *                 ),
     *                 @OA\Property(
     *                     property="community_id",
     *                     type="integer",
     *                     example=1
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Review not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    public function getReviewByUserCommunity($userId, $communityId)
    {
        $review = $this->reviewRepository->findByField(['user_id' => $userId, 'community_id' => $communityId]);

        return $this->sendResponse('Review retrieved successfully', $review);
    }
}
