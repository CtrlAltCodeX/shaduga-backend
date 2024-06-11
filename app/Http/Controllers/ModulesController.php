<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\CreateModuleAPIRequest;
use App\Http\Requests\API\UpdateModuleAPIRequest;
use App\Repositories\CommunityRepository;
use App\Repositories\ContactRepository;
use App\Repositories\ModuleRepository;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Laracasts\Flash\Flash as FlashFlash;

class ModulesController extends AppBaseController
{
    /** @var ModuleRepository $moduleRepository*/
    private $moduleRepository;

    public function __construct(
        ModuleRepository $moduleRepository,
        public CommunityRepository $communityRepository
    ) {
        $this->moduleRepository = $moduleRepository;
    }

    /**
     * Display a listing of the User.
     */
    public function index(Request $request)
    {
        $modules = $this->moduleRepository->with('community')->paginate(10);

        return view('admin.modules.index')
            ->with('modules', $modules);
    }

    /**
     * Show the form for creating a new User.
     */
    public function create()
    {
        $communities = $this->communityRepository->all(['name', 'id']);

        return view('admin.modules.create', compact('communities'));
    }

    /**
     * Store a newly created User in storage.
     */
    public function store(CreateModuleAPIRequest $request)
    {
        $input = $request->all();

        $this->moduleRepository->create($input);

        FlashFlash::success('Contact saved successfully.');

        return redirect(route('admin.modules.index'));
    }

    /**
     * Display the specified User.
     */
    public function show($id)
    {
        $coontact = $this->moduleRepository->find($id);

        if (empty($coontact)) {
            FlashFlash::error('Contact not found');

            return redirect(route('modules.index'));
        }

        return view('modules.show')->with('coontact', $coontact);
    }

    /**
     * Show the form for editing the specified User.
     */
    public function edit($id)
    {
        $modules = $this->moduleRepository->find($id);

        $communities = $this->communityRepository->all(['name', 'id']);

        if (empty($modules)) {
            FlashFlash::error('modules not found');

            return redirect(route('admin.modules.index'));
        }

        return view('admin.modules.edit', compact('communities', 'modules'));
    }

    /**
     * Update the specified User in storage.
     */
    public function update($id, UpdateModuleAPIRequest $request)
    {
        $modules = $this->moduleRepository->find($id);

        if (empty($modules)) {
            FlashFlash::error('Modules not found');

            return redirect(route('admin.contacts.index'));
        }

        $modules = $this->moduleRepository->update($request->all(), $id);

        FlashFlash::success('Modules updated successfully.');

        return redirect(route('admin.modules.index'));
    }

    /**
     * Remove the specified User from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $modules = $this->moduleRepository->find($id);

        if (empty($modules)) {
            FlashFlash::error('Contact not found');

            return redirect(route('admin.modules.index'));
        }

        $this->moduleRepository->delete($id);

        FlashFlash::success('Modules deleted successfully.');

        return redirect(route('admin.modules.index'));
    }
}
