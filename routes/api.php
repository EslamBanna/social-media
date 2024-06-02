<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\FriendRequestController;
use App\Http\Controllers\API\PostCommentController;
use App\Http\Controllers\API\PostController;
use App\Http\Controllers\API\PostLikeController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\AuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('login', [AuthController::class, 'login']);
Route::post('register',  [AuthController::class, 'register']);
Route::post('send-otp',  [AuthController::class, 'sendOTP']);
Route::post('verify-email',  [AuthController::class, 'verifyEmail']);
Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
Route::get('check-forget-password-token',  [AuthController::class, 'checkForgetPasswordToken']);
Route::post('change-password',  [AuthController::class, 'changePassword']);
Route::group(['middleware' => 'auth'], function () {

    Route::get('me',  [AuthController::class, 'me']);
    Route::post('update-password', [AuthController::class, 'updatePassword']);

    Route::get('profile/{id}', [UserController::class, 'index'])->name('profile');
    Route::post('profile/update', [UserController::class, 'update'])->name('profile.update');
    Route::post('profile/password/update', [UserController::class, 'updatePassword'])->name('profile.update.password');

    Route::get('friends', [UserController::class, 'newUsers'])->name('new.users');
    Route::get('new-users', [UserController::class, 'newUsers'])->name('new.users');
    Route::get('users-requests', [FriendRequestController::class, 'usersRequests'])->name('new.users.requests');
    Route::get('my-requests', [FriendRequestController::class, 'myRequests'])->name('my.requests');
    Route::get('send-friend-request/{id}', [FriendRequestController::class, 'sendRequest'])->name('send-friend-request');
    Route::post('request-response/{userId}/{response}', [FriendRequestController::class, 'responseOnRequest'])->name('request-response');
    Route::post('remove-request/{userId}', [FriendRequestController::class, 'removeRequest'])->name('remove.request');

    Route::post('create-post', [PostController::class, 'store'])->name('posts.store');
    Route::post('update-post/{id}', [PostController::class, 'update'])->name('posts.update');
    Route::post('delete-post/{id}', [PostController::class, 'delete'])->name('posts.delete');
    Route::get('post/{id}', [PostController::class, 'show'])->name('posts.show');

    Route::post('post-like/{posId}', [PostLikeController::class, 'like'])->name('posts.like');
    Route::get('post-likes/{posId}', [PostLikeController::class, 'likes'])->name('posts.likes');
    Route::post('post-comment/{posId}', [PostCommentController::class, 'comment'])->name('posts.comment');
});