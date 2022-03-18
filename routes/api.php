<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('/checkingAuthenticated', function () {
        return response()->json(['message' => 'You are Logged in', 'status' => 200], 200);
    });

    Route::post('logout', [\App\Http\Controllers\AuthController::class, 'logout']);
    //get user details
    Route::get('userdetails/{email}', [\App\Http\Controllers\UserController::class,'getUserDetails']);


    //get existing user
    Route::post('/existing_user',[\App\Http\Controllers\AuthController::class, 'existingUser']);
    //start user profile section
    Route::resource('profile', ProfileController::class);
    //end user profile section

});

Route::post('register', [\App\Http\Controllers\AuthController::class, 'register']);
Route::post('login', [\App\Http\Controllers\AuthController::class, 'login']);
Route::get('reactjs', [\App\Http\Controllers\UserController::class, 'reactjs']);
