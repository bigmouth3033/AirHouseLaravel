<?php

use App\Http\Controllers\AmenityController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\GoogleController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\PropertyTypeController;
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
Route::post('/createAmenity', [AmenityController::class, 'create']);
Route::get('/readAmenity', [AmenityController::class, 'read']);
Route::post('/updateAmenity', [AmenityController::class, 'update']);
Route::post('deleteAmenity', [AmenityController::class, 'delete']);
Route::post('filterByNameAmenity', [AmenityController::class, 'filterByName']);



Route::post('/createPropertyType', [PropertyTypeController::class, 'create']);
Route::get('/readPropertyType', [PropertyTypeController::class, 'read']);
Route::post('/updatePropertyType', [PropertyTypeController::class, 'update']);
Route::post('deletePropertyType', [PropertyTypeController::class, 'delete']);
Route::post('filterByNamePropertyType', [PropertyTypeController::class, 'filterByName']);

//public route
Route::post('/signup', [UserController::class, 'signup']);
Route::post('/login', [UserController::class, 'login']);

