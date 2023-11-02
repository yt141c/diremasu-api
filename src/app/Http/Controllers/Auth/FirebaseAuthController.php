<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterFirebaseUserRequest;
use App\Services\FirebaseAuthService;
use Illuminate\Http\JsonResponse;

class FirebaseAuthController extends Controller
{
    private FirebaseAuthService $firebaseAuthService;

    public function __construct(FirebaseAuthService $firebaseAuthService)
    {
        $this->firebaseAuthService = $firebaseAuthService;
    }

    public function firebaseSignUpWithEmailAndPassword(RegisterFirebaseUserRequest $request): JsonResponse
    {
        $response = $this->firebaseAuthService->createUser($request->validated());

        return response()->json($response, $response['status']);
    }
}
