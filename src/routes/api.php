<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\LectureController;
use App\Http\Controllers\Auth\FirebaseAuthController;

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
    // すべてのレクチャーを取得する
    // Route::get('/lectures', [LectureController::class, 'index']);
    Route::get('/lectures/{hashid}', [LectureController::class, 'show'])->name('lecture');
});


Route::post('/users/create', [FirebaseAuthController::class, 'firebaseSignUpWithEmailAndPassword']);

Route::get('/test', function () {
    return response()->json(['status' => 'this is test sample json'], 200);
});
