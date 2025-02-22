<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\GroupController;
use App\Http\Controllers\API\RoomController;
use App\Http\Controllers\API\SubjectController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function (){
    Route::get('user', function (Request $request) {
        return $request->user();
    });
    Route::post('logout', [AuthController::class, 'logout']);
    Route::prefix('subjects')->group(function () {
        Route::get('/', [SubjectController::class, 'index']);
        Route::get('/{subject}', [SubjectController::class, 'show']);
        Route::put('/{subject}', [SubjectController::class, 'update']);
        Route::delete('/{subject}/delete', [SubjectController::class, 'destroy']);

    });
    Route::prefix('rooms')->group(function () {
        Route::get('/', [RoomController::class, 'index']);
        Route::get('/{room}', [RoomController::class, 'show']);
        Route::put('/{room}', [RoomController::class, 'update']);
        Route::delete('/{room}/delete', [RoomController::class, 'destroy']);

    });
    Route::prefix('groups')->group(function () {
        Route::get('/', [GroupController::class, 'index']);
        Route::get('/{group}', [GroupController::class, 'show']);
        Route::put('/{group}', [GroupController::class, 'update']);
        Route::delete('/{group}/delete', [GroupController::class, 'destroy']);

    });
});

