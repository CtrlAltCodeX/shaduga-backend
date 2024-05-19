<?php

use App\Http\Controllers\API\AuthController;
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

// Route::middleware('auth:sanctum')->group(function () {
Route::resource('communities', App\Http\Controllers\API\CommunityAPIController::class)
    ->except(['create', 'edit']);

Route::resource('users', App\Http\Controllers\API\UserAPIController::class)
    ->except(['create', 'edit']);

Route::resource('members', App\Http\Controllers\API\MemberAPIController::class)
    ->except(['create', 'edit']);

Route::post('send/otp', [UserAPIController::class, 'sendOTP']);

Route::get('current/user', [UserAPIController::class, 'currentUser']);

Route::post('verify/otp', [UserAPIController::class, 'verifyOTP']);

Route::resource('chats', App\Http\Controllers\API\ChatAPIController::class)
    ->except(['create', 'edit']);

Route::resource('reviews', App\Http\Controllers\API\ReviewAPIController::class)
    ->except(['create', 'edit']);

Route::resource('quests', App\Http\Controllers\API\QuestAPIController::class)
    ->except(['create', 'edit']);
Route::resource('leader-boards', App\Http\Controllers\API\LeaderBoardAPIController::class)
    ->except(['create', 'edit']);

Route::resource('subscriptions', App\Http\Controllers\API\SubscriptionAPIController::class)
    ->except(['create', 'edit']);
// });

Route::post('login', [UserAPIController::class, 'login'])->name('login');

Route::post('register', [UserAPIController::class, 'register'])->name('register');
