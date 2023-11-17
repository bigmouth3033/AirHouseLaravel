<?php

use App\Http\Controllers\AmenityController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\GoogleController;
use App\Http\Controllers\Api\UserController;

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
  
});
//Amenity
Route::post('/createAmenities', [AmenityController::class, 'create']);
Route::get('/readAmenities', [AmenityController::class, 'read']);
Route::post('/updateAmenities', [AmenityController::class, 'update']);
Route::post('deleteAmenities', [AmenityController::class, 'delete']);


Route::post('/createPropertyType', [AmenityController::class, 'create']);
Route::get('/readPropertyType', [AmenityController::class, 'read']);
Route::post('/updatePropertyType', [AmenityController::class, 'update']);
Route::post('deletePropertyType', [AmenityController::class, 'delete']);

//public route
Route::post('/signup', [UserController::class, 'signup']);
Route::post('/login', [UserController::class, 'login']);

