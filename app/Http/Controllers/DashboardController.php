<?php

namespace App\Http\Controllers;

use App\Repositories\CommunityRepository;
use App\Repositories\ContactRepository;
use App\Repositories\ModuleRepository;
use App\Repositories\PropertyBoardsRepository;
use App\Repositories\PropertyRepository;
use App\Repositories\ReviewRepository;
use App\Repositories\UserRepository;

class DashboardController extends AppBaseController
{

    public function __construct(
        public UserRepository $userRepository,
        public ReviewRepository $reviewRepository,
        public CommunityRepository $communityRepository,
        public ModuleRepository $moduleRepository
    ) {
    }

    /**
     * Send a success response.
     *
     * @param string $message
     * @param mixed $data
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $users = $this->userRepository->all()
            ->count();

        $reviews = $this->reviewRepository->all()
            ->count();

        $communities = $this->communityRepository->all()
            ->count();

        $modules = $this->moduleRepository->all()
            ->count();

        return view('admin.content', compact('users', 'reviews', 'communities', 'modules'));
    }
}
