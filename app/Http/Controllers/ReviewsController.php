<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\CreateReviewAPIRequest;
use App\Http\Requests\API\UpdateReviewAPIRequest;
use App\Repositories\CommunityRepository;
use App\Repositories\ReviewRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash as FlashFlash;

class ReviewsController extends AppBaseController
{
    /**
     * Construct
     *
     * @param ReviewRepository $reviewRepository
     */
    public function __construct(
        public ReviewRepository $reviewRepository,
        public UserRepository $userRepository,
        public CommunityRepository $communityRepository
    ) {
    }

    /**
     * Display a listing of the User.
     */
    public function index(Request $request)
    {
        $reviews = $this->reviewRepository->with('user')->paginate(10);

        return view('admin.reviews.index')
            ->with('reviews', $reviews);
    }

    /**
     * Show the form for creating a new User.
     */
    public function create()
    {
        $users = $this->userRepository->all();

        $communities = $this->communityRepository->all();

        return view('admin.reviews.create', compact('users', 'communities'));
    }

    /**
     * Store a newly created User in storage.
     */
    public function store(CreateReviewAPIRequest $request)
    {
        $input = $request->all();

        if ($file = $request->file('image')) {
            $profileImage = time() . "." . $file->getClientOriginalExtension();

            $file->move('storage/review/', $profileImage);

            $input['image'] = "/storage/review/" . "$profileImage";
        }

        $this->reviewRepository->create($input);

        FlashFlash::success('Boards saved successfully.');

        return redirect(route('admin.reviews.index'));
    }

    /**
     * Display the specified User.
     */
    public function show($id)
    {
        $reviews = $this->reviewRepository->find($id);

        if (empty($reviews)) {
            FlashFlash::error('Properties not found');

            return redirect(route('boards.index'));
        }

        return view('boards.show')->with('reviews', $reviews);
    }

    /**
     * Show the form for editing the specified User.
     */
    public function edit($id)
    {
        $reviews = $this->reviewRepository->find($id);

        $communities = $this->communityRepository->all();

        $users = $this->userRepository->all();

        if (empty($reviews)) {
            FlashFlash::error('reviews not found');

            return redirect(route('admin.reviews.index'));
        }

        return view('admin.reviews.edit', compact('users', 'reviews', 'communities'));
    }

    /**
     * Update the specified User in storage.
     */
    public function update($id, UpdateReviewAPIRequest $request)
    {
        $reviews = $this->reviewRepository->find($id);

        $input = $request->all();

        if (empty($reviews)) {
            FlashFlash::error('reviews not found');

            return redirect(route('admin.reviews.index'));
        }

        if ($file = $request->file('image')) {
            $profileImage = time() . "." . $file->getClientOriginalExtension();

            $file->move('storage/review/', $profileImage);

            $input['image'] = "/storage/review/" . "$profileImage";
        }

        $reviews = $this->reviewRepository->update($input, $id);

        FlashFlash::success('reviews updated successfully.');

        return redirect(route('admin.reviews.index'));
    }

    /**
     * Remove the specified User from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $reviews = $this->reviewRepository->find($id);

        if (empty($reviews)) {
            FlashFlash::error('reviews not found');

            return redirect(route('reviews.index'));
        }

        $this->reviewRepository->delete($id);

        FlashFlash::success('reviews deleted successfully.');

        return redirect(route('admin.reviews.index'));
    }
}
