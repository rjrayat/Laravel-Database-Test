<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::post('/user-login', [UserController::class, 'UserLogin']);

Route::middleware('jwt')->get('/profile', [UserController::class, 'profile']);

Route::middleware('jwt')->group(function(){
    Route::post('/logout',[UserController::class,'Logout']);
});
