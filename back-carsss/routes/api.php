<?php

use App\Http\Controllers\CarFilteringController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/filterbooking', [CarFilteringController::class,"getFilteringData"]);
Route::post('/getYears', [CarFilteringController::class,"getYears"]);


Route::post('/temp',[CarFilteringController::class,"getTemptest"]);
