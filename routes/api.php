<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\SearchController;
Route::middleware("api.auth")->group(function(){
    Route::post('/register', [UserController::class, 'Register'])->name('register');
    Route::post('login', [UserController::class, 'Login'])->name('login');
    Route::post('/search', [SearchController::class, 'Search'])->name('search');
   
});

Route::middleware(["api.auth", "auth:sanctum"])->group(function(){
     Route::get('/profile/', [UserController::class, 'Profile'])->name('profile');
    Route::put('/update-profile', [UserController::class, "UpdateProfile"])->name("upate.profile");
    Route::put('/update-password', [UserController::class, 'UpdatePassword'])->name('update.password');
    Route::post('/logout', [UserController::class, 'Logout'])->name('logout');

    Route::post('/publish', [PostController::class, 'Publish'])->name('publish');
    Route::delete('/delete-post', [PostController::class, 'DeletePost'])->name('delete-post');
    Route::get('/get-post', [PostController::class, "GetPost"])->name("get-post");


    Route::post('/add-comment/{id}', [CommentController::class, 'AddComment'])->name('add-comment');
});