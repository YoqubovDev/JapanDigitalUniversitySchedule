<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\GroupController;
use App\Http\Controllers\API\GroupStudentController;
use App\Http\Controllers\API\GroupSubjectController;
use App\Http\Controllers\API\RoomController;
use App\Http\Controllers\API\SubjectController;
use App\Http\Controllers\API\SubjectTeacherController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function (){
    Route::post('logout', [AuthController::class, 'logout']);
    Route::resource('group-subject', GroupController::class);
    Route::resource('subjects', SubjectController::class);
    Route::resource('rooms', RoomController::class);
    Route::resource('groups', GroupController::class);

    Route::post('group/{id}/subject', [GroupSubjectController::class, 'attachSubjectToGroup']);
    Route::post('subject/{id}/teacher', [SubjectTeacherController::class, 'attachTeacherToSubject']);
    Route::post('group/{id}/student', [GroupStudentController::class, 'attachGroupToStudent']);

//    Route::resource('group/{id}/subject', GroupSubjectController::class);
});




