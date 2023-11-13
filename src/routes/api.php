<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\LectureController;
use App\Http\Controllers\UserController;

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

Route::middleware(['auth.firebase'])->group(function () {
    Route::get('/auth', function () {
        return response()->json(['status' => 'authorized'], 200);
    });
    Route::get('/courses', [CourseController::class, 'index']);
    Route::get('/courses/{hashid}', [CourseController::class, 'show']);

    Route::get('/lectures/{hashid}', [LectureController::class, 'show'])->name('lecture');

    Route::get('/user/exists', [UserController::class, 'index']);
    Route::post('/user/create', [UserController::class, 'create']);
});


Route::get('/courses/{hashid}', [CourseController::class, 'show']);
Route::get('/lectures/{hashid}/trial', [LectureController::class, 'showTrial'])->name('lecture');




Route::get('/test', function () {
    return response()->json(['status' => 'this is test sample json'], 200);
});
