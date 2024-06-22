<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PointController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/points', [PointController::class, 'index']);
Route::post('/points', [PointController::class, 'store']);
Route::get('/points/{id}', [PointController::class, 'info']);
Route::put('/points/{id}', [PointController::class, 'update']);
Route::delete('/points/{id}', [PointController::class, 'destroy']);