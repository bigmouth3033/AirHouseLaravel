<?php

use App\Http\Controllers\AmenityController;
use App\Http\Controllers\Api\AuthController;
<<<<<<< HEAD
use App\Http\Controllers\Api\GoogleController;
=======
use App\Http\Controllers\Api\UserController;
>>>>>>> tan
use App\Models\PropertyType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//protected route
Route::group(['middleware' => ['auth:sanctum']], function () {

  Route::get('/user', function (Request $request) {
    return $request->user();
  });

  Route::post('/logout', [UserController::class, 'logout']);


<<<<<<< HEAD
Route::get("/signup", [AuthController::class, 'signup']);
// Route::get('auth/google/url', [GoogleController::class,'loginUrl']);
// Route::get('auth/google/callback', [GoogleController::class,'loginCallback']);
=======
  Route::post('updateAmenties', [AmenityController::class, 'update']);
  Route::post('createAmenties', [AmenityController::class, 'create']);
  Route::post('deleteAmenties', [AmenityController::class, 'delete']);
  Route::post('readAmenties', [AmenityController::class, 'read']);
});




//public route
Route::post('/signup', [UserController::class, 'signup']);
Route::post('/login', [UserController::class, 'login']);
>>>>>>> tan
