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
     * @OA\Get(
     *     path="/reviews",
     *     summary="Display a listing of the Reviews",
     *     tags={"Reviews"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/Review")
     *     )
     * )
     *  * @OA\Schema(
     *     schema="Review",
     *     @OA\Property(
     *         property="id",
     *         type="integer",
     *         format="int64",
     *         description="Unique identifier for the review"
     *     ),
     *     @OA\Property(
     *         property="user_id",
     *         type="integer",
     *         format="int64",
     *         description="Unique identifier for the User"
     *     ),
     *     @OA\Property(
     *         property="rating",
     *         type="integer",
     *         format="int64",
     *         description="Rating"
     *     ),
     *     @OA\Property(
     *         property="title",
     *         type="integer",
     *         format="int64",
     *         description="Title"
     *     ),
     *     @OA\Property(
     *         property="content",
     *         type="string",
     *         description="Content of the review"
     *     ),
     *     @OA\Property(
     *         property="status",
     *         type="string",
     *         description="Status of the review"
     *     ),
     *     @OA\Property(
     *         property="created_at",
     *         type="string",
     *         format="date-time",
     *         description="Creation date of the review"
     *     ),
     *     @OA\Property(
     *         property="updated_at",
     *         type="string",
     *         format="date-time",
     *         description="Last update date of the review"
     *     )
     * )
     *
     * @param Request $request
     * @return JsonResponse
     */
    // public function index(Request $request): JsonResponse
    // {
    //     $reviews = $this->reviewRepository->all(
    //         $request->except(['skip', 'limit']),
    //         $request->get('skip'),
    //         $request->get('limit')
    //     );

    //     return $this->sendResponse($reviews->toArray(), 'Reviews retrieved successfully');
    // }

    /**
     * Store a newly created Review in storage.
     *
     * @OA\Post(
     *     path="/reviews",
     *     summary="Store a newly created Review in storage",
     *     tags={"Reviews"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CreateReviewAPIRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/Review")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Entity",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object", example={"field": {"The field is required."}})
     *         )
     *     )
     * )
     * 
     * @OA\Schema(
     *     schema="CreateReviewAPIRequest",
     *     title="Create Review API Request",
     *     description="Request body parameters for creating a review.",
     *     required={"product_id", "rating", "comment"},
     *     @OA\Property(
     *         property="id",
     *         type="integer",
     *         format="int64",
     *         description="Unique identifier for the review"
     *     ),
     *     @OA\Property(
     *         property="user_id",
     *         type="integer",
     *         format="int64",
     *         description="Unique identifier for the User"
     *     ),
     *     @OA\Property(
     *         property="rating",
     *         type="integer",
     *         format="int64",
     *         description="Rating"
     *     ),
     *     @OA\Property(
     *         property="title",
     *         type="integer",
     *         format="int64",
     *         description="Title"
     *     ),
     *     @OA\Property(
     *         property="content",
     *         type="string",
     *         description="Content of the review"
     *     ),
     *     @OA\Property(
     *         property="status",
     *         type="string",
     *         description="Status of the review"
     *     ),
     *     @OA\Property(
     *         property="created_at",
     *         type="string",
     *         format="date-time",
     *         description="Creation date of the review"
     *     ),
     *     @OA\Property(
     *         property="updated_at",
     *         type="string",
     *         format="date-time",
     *         description="Last update date of the review"
     *     )
     * )
     * 
     *
     * @param CreateReviewAPIRequest $request
     * @return JsonResponse
     */
    // public function store(CreateReviewAPIRequest $request): JsonResponse
    // {
    //     $input = $request->all();

    //     $review = $this->reviewRepository->create($input);

    //     return $this->sendResponse($review->toArray(), 'Review saved successfully');
    // }

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

        return $this->sendResponse($review->toArray(), 'Review retrieved successfully');
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

        return $this->sendResponse($review->toArray(), 'Review updated successfully');
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

        return $this->sendSuccess('Review deleted successfully');
    }
}
