<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthenticationController;
use App\Http\Controllers\Api\OrderController;


// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


//Register
Route::post('/register', [AuthenticationController::class, 'register']);
//Login
Route::post('/login', [AuthenticationController::class, 'login']);

Route::group([
    "middleware" => ['auth:sanctum']

],function(){
    //profile
    Route::get('/profile', [AuthenticationController::class, 'profile']);

    //logout
    Route::get('/logout', [AuthenticationController::class, 'logout']);

    //order
    Route::post('/new-order', [OrderController::class, 'store']);


});
