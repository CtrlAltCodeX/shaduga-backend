<?php

use App\Http\Controllers\CommunitiesController;
use App\Http\Controllers\ContactsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ModulesController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\ReviewsController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::group(['middleware' => ['auth', 'web']], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');

    Route::prefix('users')->group(function () {
        Route::get('list', [UserController::class, 'index'])
            ->name('admin.users.index');

        Route::get('all-users', [UserController::class, 'all'])
            ->name('admin.all.users');

        Route::get('create', [UserController::class, 'create'])
            ->name('admin.users.create');

        Route::post('create', [UserController::class, 'store'])
            ->name('admin.users.store');

        Route::get('edit/{id}', [UserController::class, 'edit'])
            ->name('admin.users.edit');

        Route::post('update/{id}', [UserController::class, 'update'])
            ->name('admin.users.update');

        Route::get('delete/{id}', [UserController::class, 'destroy'])
            ->name('admin.users.delete');
    });

    Route::prefix('module')->group(function () {
        Route::get('list', [ModulesController::class, 'index'])
            ->name('admin.modules.index');

        Route::get('create', [ModulesController::class, 'create'])
            ->name('admin.modules.create');

        Route::post('create', [ModulesController::class, 'store'])
            ->name('admin.modules.store');

        Route::get('edit/{id}', [ModulesController::class, 'edit'])
            ->name('admin.modules.edit');

        Route::post('update/{id}', [ModulesController::class, 'update'])
            ->name('admin.modules.update');

        Route::get('delete/{id}', [ModulesController::class, 'destroy'])
            ->name('admin.modules.delete');
    });

    Route::prefix('communities')->group(function () {
        Route::get('list', [CommunitiesController::class, 'index'])
            ->name('admin.communities.index');

        Route::get('create', [CommunitiesController::class, 'create'])
            ->name('admin.communities.create');

        Route::post('create', [CommunitiesController::class, 'store'])
            ->name('admin.communities.store');

        Route::get('edit/{id}', [CommunitiesController::class, 'edit'])
            ->name('admin.communities.edit');

        Route::post('update/{id}', [CommunitiesController::class, 'update'])
            ->name('admin.communities.update');

        Route::get('delete/{id}', [CommunitiesController::class, 'destroy'])
            ->name('admin.communities.delete');
    });

    Route::prefix('reviews')->group(function () {
        Route::get('list', [ReviewsController::class, 'index'])
            ->name('admin.reviews.index');

        Route::get('create', [ReviewsController::class, 'create'])
            ->name('admin.reviews.create');

        Route::post('create', [ReviewsController::class, 'store'])
            ->name('admin.reviews.store');

        Route::get('edit/{id}', [ReviewsController::class, 'edit'])
            ->name('admin.reviews.edit');

        Route::post('update/{id}', [ReviewsController::class, 'update'])
            ->name('admin.reviews.update');

        Route::get('delete/{id}', [ReviewsController::class, 'destroy'])
            ->name('admin.reviews.delete');
    });
});

Auth::routes();
