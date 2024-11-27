<?php

use App\Http\Controllers\RoomController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/rooms', [RoomController::class, 'index']);
Route::get('/rooms/{id}/messages', [RoomController::class, 'getMessages']);

//Route::middleware('auth:sanctum')->group(function () {
//    Route::get('/rooms', [RoomController::class, 'index']);
//    Route::get('/rooms/{id}/messages', [RoomController::class, 'getMessages']);
//});
