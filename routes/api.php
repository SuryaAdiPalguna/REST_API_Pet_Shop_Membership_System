<?php

use App\Http\Controllers\AdoptController;
use App\Http\Controllers\BreedController;
use App\Http\Controllers\CareController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\PuppyController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::resource('/login', LoginController::class)->only('store');
Route::resource('/register', UserController::class)->only('store');

Route::middleware('auth:sanctum')->group(function () {
    Route::resource('/users', UserController::class)->except(['create', 'store', 'edit']);
    Route::resource('/members', MemberController::class)->except(['create', 'edit']);
    Route::resource('/breeds', BreedController::class)->except(['create', 'edit']);
    Route::resource('/cares', CareController::class)->except(['create', 'edit']);
    Route::resource('/puppies', PuppyController::class)->except(['create', 'edit']);
    Route::resource('/adopts', AdoptController::class)->except(['create', 'edit', 'update']);
    Route::resource('/logout', LogoutController::class)->only('store');
});
