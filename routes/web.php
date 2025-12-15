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

Route::get('/', function () {
    return view('welcome');
});
