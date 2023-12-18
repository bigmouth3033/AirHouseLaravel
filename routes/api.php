<?php

use App\Http\Controllers\StartController;
use App\Http\Controllers\TransactionController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\HostController;
use App\Http\Controllers\AmenityController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\ProvinceController;
use App\Http\Controllers\RoomTypeController;
use App\Http\Controllers\PropertyTypeController;
use App\Http\Controllers\PaymentController;


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
    $user = $request->user();
    $token = $request->bearerToken();
    return response(compact('user', 'token'));
  });

  Route::get('/user/{id}', [UserController::class, 'readById']);

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

  Route::get('retrieveRoomType', [RoomTypeController::class, 'getRoom']);
  Route::post('createRoomType', [RoomTypeController::class, 'create']);
  Route::post('updateRoomType', [RoomTypeController::class, 'update']);
  Route::post('deleteRoomType', [RoomTypeController::class, 'deleteRoomType']);
  Route::get('/readRoomType/{page}', [RoomTypeController::class, 'readCurrentPage']);
  Route::get('/filterByIdRoomType', [RoomTypeController::class, 'filterById']);

  Route::post('createProperty', [PropertyController::class, 'create']);
  Route::post('deleteProperty/{id}', [PropertyController::class, 'delete']);

  Route::post('/createPropertyType', [PropertyTypeController::class, 'create']);
  Route::get('/readPropertyType', [PropertyTypeController::class, 'read']);
  Route::post('/updatePropertyType', [PropertyTypeController::class, 'update']);
  Route::post('deletePropertyType', [PropertyTypeController::class, 'delete']);
  Route::post('filterByNamePropertyType', [PropertyTypeController::class, 'filterByName']);
  Route::get('/readPropertyType/{page}', [PropertyTypeController::class, 'readCurrentPage']);
  Route::get('filterByIdPropertyType', [PropertyTypeController::class, 'filterById']);


  Route::post('sendMessage', [ChatController::class, 'sendMessage']);
  Route::post('getMessage', [ChatController::class, 'getMessage']);


  Route::post('/create-property', [HostController::class, 'create']);
  Route::post('/read-properties', [HostController::class, 'read']);
  Route::post('/update-property', [HostController::class, 'update']);
  Route::get('/delete-property/{id}', [HostController::class, 'delete']);
  Route::get('/read-properties/all/{page}', [HostController::class, 'readAllStatusCurrentPage']);
  Route::get('/read-property/{id}', [HostController::class, 'readById']);
  Route::post('property/accept', [HostController::class, 'acceptProperty']);
  Route::post('property/deny', [HostController::class, 'denyProperty']);

  Route::post("blog/uploadImage", [BlogController::class, 'uploadImage']);

  Route::post('sendMessage', [ChatController::class, 'sendMessage']);
  Route::get('getMessage/', [ChatController::class, 'getMessage']);
  Route::get('getAllUser', [ChatController::class, 'getAllUser']);

  //booings
  Route::post('user-booking', [BookingController::class, 'createBooking']);
  Route::post('create-transaction', [TransactionController::class, 'createTransaction']);

  //payment

  Route::post('/create-payment-intent', [PaymentController::class, 'createPaymentIntent']);
  Route::post('/successBooking', [PaymentController::class, 'success']);
  Route::get('/readSuccessBooking', [PaymentController::class, 'readSuccess']);

  //starts
  Route::post('/createStart',[StartController::class, 'createStart']) ;
  Route::get('/readStart',[StartController::class, 'readStart']) ;
  Route::get('filterByIdProperty', [PropertyController::class, 'readById']);
  Route::get('readBooking', [BookingController::class, 'readBooking']);
});

//public route
Route::get('check-email-unique', [UserController::class, 'checkEmailUnique']);
Route::get('showUserPropertyById', [PropertyController::class, 'showUserPropertyById']);
Route::post('readBlog', [BlogController::class, 'read']);
Route::get('show-property-index', [PropertyController::class, 'showInIndex']);
Route::get('readCategory', [CategoryController::class, 'read']);
Route::get('/readPropertyType', [PropertyTypeController::class, 'read']);
Route::get('/readPropertyType', [PropertyTypeController::class, 'read']);
Route::get('/readAmenity', [AmenityController::class, 'read']);
Route::get('retrieveRoomType', [RoomTypeController::class, 'getRoom']);
Route::get("/getProvinces", [ProvinceController::class, 'get']);
Route::get("/getDistrictAll", [DistrictController::class, 'get']);
Route::get("/getDistrict/province/{provinceID}", [DistrictController::class, 'getBasedOnProvinces']);
Route::post('/signup', [UserController::class, 'signup']);
Route::post('/login', [UserController::class, 'login']);
