<?php

use App\Http\Controllers\API\CommunityAPIController;
use App\Http\Controllers\API\ReviewAPIController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserAPIController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::resource('communities', App\Http\Controllers\API\CommunityAPIController::class)
        ->except(['create', 'edit']);

    Route::get('user/communities/{id}', [CommunityAPIController::class, 'getCurrentCommunities']);

    Route::get('communities/{id}/members', [CommunityAPIController::class, 'memberByCommunity']);

    Route::resource('users', App\Http\Controllers\API\UserAPIController::class)
        ->except(['create', 'edit']);

    Route::resource('members', App\Http\Controllers\API\MemberAPIController::class)
        ->except(['create', 'edit']);

    Route::get('current/user', [UserAPIController::class, 'currentUser']);

    Route::resource('chats', App\Http\Controllers\API\ChatAPIController::class)
        ->except(['create', 'edit']);

    Route::resource('reviews', App\Http\Controllers\API\ReviewAPIController::class)
        ->except(['create', 'edit']);

    Route::get('reviews/{user_id}/{community_id}', [ReviewAPIController::class, 'getReviewByUserCommunity']);

    Route::resource('quests', App\Http\Controllers\API\QuestAPIController::class)
        ->except(['create', 'edit']);

    Route::resource('leader-boards', App\Http\Controllers\API\LeaderBoardAPIController::class)
        ->except(['create', 'edit']);

    Route::resource('subscriptions', App\Http\Controllers\API\SubscriptionAPIController::class)
        ->except(['create', 'edit']);

    Route::resource('tasks', App\Http\Controllers\API\TaskAPIController::class)
        ->except(['create', 'edit']);

    Route::resource('modules', App\Http\Controllers\API\ModuleAPIController::class)
        ->except(['create', 'edit']);

    Route::post('logout', [UserAPIController::class, 'logout']);
});

Route::post('login', [UserAPIController::class, 'login'])->name('login');

Route::post('register', [UserAPIController::class, 'register'])->name('register');

Route::post('send/otp', [UserAPIController::class, 'sendOTP']);

Route::post('verify/otp', [UserAPIController::class, 'verifyOTP']);

Route::post('forget-password', [UserAPIController::class, 'forgetPassword']);

Route::post('reset-password', [UserAPIController::class, 'resetPassword']);
