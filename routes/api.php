<?php

use App\Models\PropertyType;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AmenityController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HostController;
use App\Http\Controllers\PropertyTypeController;

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
    if (!$request->user()) {
      return response()->json([
          'error' => 'Unauthorized',
          'message' => 'Bạn không được phép truy cập tài nguyên này.',
      ], 401);
  }

    $user = $request->user();
    $token = $request->bearerToken();
    return response(compact('user', 'token'));
  });


  Route::post('/admin/signup', [UserController::class, 'signupAdmin']);
  Route::post('/logout', [UserController::class, 'logout']);

  Route::post('/createAmenity', [AmenityController::class, 'create']);
  Route::get('/readAmenity', [AmenityController::class, 'read']);
  Route::post('/updateAmenity', [AmenityController::class, 'update']);
  Route::post('deleteAmenity', [AmenityController::class, 'delete']);
  Route::get('filterByIdAmenity', [AmenityController::class, 'filterById']);
  Route::get('/readAmenity/{page}', [AmenityController::class, 'readCurrentPage']);

  Route::post('/createPropertyType', [PropertyTypeController::class, 'create']);
  Route::get('/readPropertyType', [PropertyTypeController::class, 'read']);
  Route::post('/updatePropertyType', [PropertyTypeController::class, 'update']);
  Route::post('deletePropertyType', [PropertyTypeController::class, 'delete']);
  Route::post('filterByNamePropertyType', [PropertyTypeController::class, 'filterByName']);
  Route::get('/readPropertyType/{page}', [PropertyTypeController::class, 'readCurrentPage']);
  Route::get('filterByIdPropertyType', [PropertyTypeController::class, 'filterById']);

  Route::post('createCategory', [CategoryController::class, 'create']);
  Route::get('readCategory', [CategoryController::class, 'read']);
  Route::post('updateCategory', [CategoryController::class, 'update']);
  Route::post('deleteCategory', [CategoryController::class, 'delete']);
  Route::post('filterByName', [CategoryController::class, 'filterByName']);
  Route::get('/readCategory/{page}', [CategoryController::class, 'readCurrentPage']);
  Route::get('/filterByIdCategory', [CategoryController::class, 'filterById']);


  Route::post('/createHost', [HostController::class, 'create']);
  Route::post('/readHost', [HostController::class, 'read']);
  Route::post('/updateHost', [HostController::class, 'update']);
  Route::get('deleteHost/{id}', [HostController::class, 'delete']);
});


//public route
Route::post('/signup', [UserController::class, 'signup']);
Route::post('/login', [UserController::class, 'login']);
