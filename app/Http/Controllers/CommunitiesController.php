<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\CreateCommunityAPIRequest;
use App\Http\Requests\API\UpdateCommunityAPIRequest;
use App\Mail\InviteMail;
use App\Repositories\CommunityRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Laracasts\Flash\Flash as FlashFlash;

class CommunitiesController extends AppBaseController
{
    /**
     * Construct
     *
     * @param CommunityRepository $communityRepository
     */
    public function __construct(
        public CommunityRepository $communityRepository
    ) {
    }

    /**
     * Display a listing of the User.
     */
    public function index(Request $request)
    {
        $communities = $this->communityRepository->simplePaginate(10);
        
        return view('admin.communities.index')
            ->with('communities', $communities);
    }

    /**
     * Show the form for creating a new User.
     */
    public function create()
    {
        return view('admin.communities.create');
    }

    /**
     * Store a newly created User in storage.
     */
    public function store(CreateCommunityAPIRequest $request)
    {
        $input = $request->all();

        if (isset($input['invitation'])) {
            $input['invites'] = json_encode($input['invitation']);
        }

        $input['user_id'] = auth()->user()->id;

        if ($file = $request->file('logo')) {
            $profileImage = time() . "." . $file->getClientOriginalExtension();

            $file->move('storage/community/', $profileImage);

            $input['logo'] = "/storage/community/" . "$profileImage";
        }

        $input['link'] = url('/') . "/" . $input['name'];

        $this->communityRepository->create($input);

        if (isset($input['invitation'])) {
            foreach ($input['invitation'] as $invities) {
                if (isset($invities[0])) {
                    // Mail::to($invities[0])->send(new InviteMail($input['link']));
                }
            }
        }

        FlashFlash::success('Communities saved successfully.');

        return redirect(route('admin.communities.index'));
    }

    /**
     * Display the specified User.
     */
    public function show($id)
    {
        $coontact = $this->communityRepository->find($id);

        if (empty($coontact)) {
            FlashFlash::error('Communities not found');

            return redirect(route('admin.communities.index'));
        }

        return view('admin.communities.show')->with('coontact', $coontact);
    }

    /**
     * Show the form for editing the specified User.
     */
    public function edit($id)
    {
        $communities = $this->communityRepository->find($id);

        if (empty($communities)) {
            FlashFlash::error('Communities not found');

            return redirect(route('admin.communities.index'));
        }

        return view('admin.communities.edit')->with('communities', $communities);
    }

    /**
     * Update the specified User in storage.
     */
    public function update($id, UpdateCommunityAPIRequest $request)
    {
        $user = $this->communityRepository->find($id);

        $input = $request->all();

        if (empty($user)) {
            FlashFlash::error('Communities not found');

            return redirect(route('admin.communities.index'));
        }

        if ($file = $request->file('logo')) {
            $profileImage = time() . "." . $file->getClientOriginalExtension();

            $file->move('storage/community/', $profileImage);

            $input['logo'] = "/storage/community/" . "$profileImage";
        }

        if ($input['invitation']) {
            $input['invites'] = json_encode($input['invitation']);
        }

        $user = $this->communityRepository->update($input, $id);

        FlashFlash::success('Communities updated successfully.');

        return redirect(route('admin.communities.index'));
    }

    /**
     * Remove the specified User from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $user = $this->communityRepository->find($id);

        if (empty($user)) {
            FlashFlash::error('Communities not found');

            return redirect(route('admin.communities.index'));
        }

        $this->communityRepository->delete($id);

        FlashFlash::success('Communities deleted successfully.');

        return redirect(route('admin.communities.index'));
    }
}
