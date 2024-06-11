<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Laracasts\Flash\Flash as FlashFlash;

class UserController extends AppBaseController
{
    /** @var UserRepository $userRepository*/
    private $userRepository;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepository = $userRepo;
    }

    /**
     * Display a listing of the User.
     */
    public function index(Request $request)
    {
        $users = $this->userRepository->paginate(10);

        return view('admin.users.index')
            ->with('users', $users);
    }

    /**
     * Show the form for creating a new User.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created User in storage.
     */
    public function store(CreateUserRequest $request)
    {
        $input = $request->all();

        $input['password'] = Hash::make($input['password']);

        if ($file = $request->file('profile')) {
            if ($file instanceof UploadedFile) {
                $profileImage = time() . "." . $file->getClientOriginalExtension();

                $file->move('storage/profile/', $profileImage);

                $input['profile_url'] = "/storage/profile/" . "$profileImage";
            }
        }

        $user = $this->userRepository->create($input);

        FlashFlash::success('User saved successfully.');

        return redirect(route('admin.users.index'));
    }

    /**
     * Display the specified User.
     */
    public function show($id)
    {
        $user = $this->userRepository->find($id);

        if (empty($user)) {
            FlashFlash::error('User not found');

            return redirect(route('users.index'));
        }

        return view('users.show')->with('user', $user);
    }

    /**
     * Show the form for editing the specified User.
     */
    public function edit($id)
    {
        $user = $this->userRepository->find($id);

        if (empty($user)) {
            FlashFlash::error('User not found');

            return redirect(route('users.index'));
        }

        return view('admin.users.edit')->with('user', $user);
    }

    /**
     * Update the specified User in storage.
     */
    public function update($id, UpdateUserRequest $request)
    {
        $user = $this->userRepository->find($id);

        $input = $request->all();

        if (empty($user)) {
            FlashFlash::error('User not found');

            return redirect(route('users.index'));
        }

        if ($file = $request->file('profile')) {
            if ($file instanceof UploadedFile) {
                $profileImage = time() . "." . $file->getClientOriginalExtension();

                $file->move('storage/profile/', $profileImage);

                $input['profile_url'] = "/storage/profile/" . "$profileImage";
            }
        }

        $user = $this->userRepository->update($input, $id);

        FlashFlash::success('User updated successfully.');

        return redirect(route('admin.users.index'));
    }

    /**
     * Remove the specified User from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $user = $this->userRepository->find($id);

        if (empty($user)) {
            FlashFlash::error('User not found');

            return redirect(route('users.index'));
        }

        $this->userRepository->delete($id);

        FlashFlash::success('User deleted successfully.');

        return redirect(route('admin.users.index'));
    }
}
