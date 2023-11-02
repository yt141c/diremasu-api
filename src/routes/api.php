<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
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

Route::middleware(['auth.firebase'])->get('/auth', function () {
    return response()->json(['status' => 'authorized'], 200);
});

Route::post('/users/email', [FirebaseAuthController::class, 'firebaseSignUpWithEmailAndPassword']);

Route::get('/test', function () {
    return response()->json(['status' => 'this is test sample json'], 200);
});
