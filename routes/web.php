<?php

use App\Http\Controllers\Blade\FriendRequestController;
use App\Http\Controllers\Blade\PostCommentController;
use App\Http\Controllers\Blade\PostController;
use App\Http\Controllers\Blade\PostLikeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Blade\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::group(['middleware' => 'auth'], function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('profile/{id}', [UserController::class, 'index'])->name('profile');
    Route::get('profile-edit', [UserController::class, 'edit'])->name('profile.edit');
    Route::post('profile/update', [UserController::class, 'update'])->name('profile.update');
    Route::get('profile/password/edit', [UserController::class, 'editPassword'])->name('profile.edit.password');
    Route::post('profile/password/update', [UserController::class, 'updatePassword'])->name('profile.update.password');

    Route::get('friends', [UserController::class, 'newUsers'])->name('new.users');
    Route::get('new-users', [UserController::class, 'newUsers'])->name('new.users');
    Route::get('users-requests', [FriendRequestController::class, 'usersRequests'])->name('new.users.requests');
    Route::get('my-requests', [FriendRequestController::class, 'myRequests'])->name('my.requests');
    Route::get('send-friend-request/{id}', [FriendRequestController::class, 'sendRequest'])->name('send-friend-request');
    Route::post('request-response/{userId}/{response}', [FriendRequestController::class, 'responseOnRequest'])->name('request-response');
    Route::post('remove-request/{userId}', [FriendRequestController::class, 'removeRequest'])->name('remove.request');

    Route::get('create-post', [PostController::class, 'create'])->name('post.create');
    Route::post('create-post', [PostController::class, 'store'])->name('posts.store');
    Route::get('edit-post/{id}', [PostController::class, 'edit'])->name('posts.edit');
    Route::post('update-post/{id}', [PostController::class, 'update'])->name('posts.update');
    Route::post('delete-post/{id}', [PostController::class, 'delete'])->name('posts.delete');
    Route::get('post/{id}', [PostController::class, 'show'])->name('posts.show');

    Route::post('post-like/{posId}', [PostLikeController::class, 'like'])->name('posts.like');
    Route::get('post-likes/{posId}', [PostLikeController::class, 'likes'])->name('posts.likes');
    Route::post('post-comment/{posId}', [PostCommentController::class, 'comment'])->name('posts.comment');
});
