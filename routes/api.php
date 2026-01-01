<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::post('/user-login', [UserController::class, 'UserLogin']);
Route::middleware('jwt')->group(function(){
    Route::get('/profile', [UserController::class, 'Profile']);
});
