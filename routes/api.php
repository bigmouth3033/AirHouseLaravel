<?php

use App\Http\Controllers\AmenityController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HostController;
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
  Route::post('createProperty', [HostController::class, 'create']);

  Route::post('/logout', [UserController::class, 'logout']);


  Route::post('updateAmenties', [AmenityController::class, 'update']);
  Route::post('createAmenties', [AmenityController::class, 'create']);
  Route::post('deleteAmenties', [AmenityController::class, 'delete']);
  Route::post('readAmenties', [AmenityController::class, 'read']);
});




//public route
Route::post('/signup', [UserController::class, 'signup']);
Route::post('/login', [UserController::class, 'login']);

Route::post('createCategory', [CategoryController::class, 'create']);
Route::get('readCategory', [CategoryController::class, 'read']);
Route::post('updateCategory', [CategoryController::class, 'update']);
Route::get('deleteCategory/{id}', [CategoryController::class, 'delete']);
Route::post('filterByName', [CategoryController::class, 'filterByName']);


Route::get('deleteProperty/{id}', [HostController::class, 'delete']);
