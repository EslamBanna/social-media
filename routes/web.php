<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Blade\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::group(['middleware' => 'auth'], function(){
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('profile/{id}', [UserController::class, 'index'])->name('profile');
    Route::get('profile-edit', [UserController::class, 'edit'])->name('profile.edit');
    Route::post('profile/update', [UserController::class, 'update'])->name('profile.update');
    Route::get('profile/password/edit', [UserController::class, 'editPassword'])->name('profile.edit.password');
    Route::post('profile/password/update', [UserController::class, 'updatePassword'])->name('profile.update.password');
});
