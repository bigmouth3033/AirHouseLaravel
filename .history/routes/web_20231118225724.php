<?php

use App\Http\Controllers\AmenityControllerCopy;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
   
});
Route::get("/", [AmenityControllerCopy::class, 'index']);
Route::post("/login", [AmenityControllerCopy::class, 'login']);
