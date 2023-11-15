<?php


use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\GoogleController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::prefix('v1')->group(function(){
//     Route::get('booking',[BookingController::class,'getData']);
//     Route::get('categories',[CategoryController::class,'getData']);
//     Route::get('districts',[DistrictController::class,'getData']);
//     Route::get('amenities',[AmenityController::class,'getData']);
//     Route::get('properties',[PropertyController::class,'getData']);
//     Route::get('property_emenities',[PropertyAmenityController::class,'getData']);
//     Route::get('property_images',[PropertyImageController::class,'getData']);
//     Route::get('property_reviews',[PropertyReviewController::class,'getData']);
//     Route::get('property_type',[PropertyTypeController::class,'getData']);
//     Route::get('provinces',[ProvinceController::class,'getData']);
//     Route::get('room_type',[RoomTypeController::class,'getData']);
//     Route::get('transactions',[TransactionController::class,'getData']);
//     Route::get('users',[UserController::class,'getData']);
// });


Route::get("/signup", [AuthController::class, 'signup']);
Route::get('auth/google/url', [GoogleController::class,'loginUrl']);
Route::get('auth/google/callback', [GoogleController::class,'loginCallback']);
