<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Kreait\Firebase\Contract\Auth as FirebaseAuth;
use Kreait\Firebase\Exception\Auth\FailedToVerifyToken;


class FirebaseAuthentication
{
    protected $auth;

    public function __construct(FirebaseAuth $auth)
    {
        $this->auth = $auth;
        $credintialsPath = storage_path(config('firebase.projects.diremasu.credentials'));
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $idTokenString = str_replace('Bearer ', '', $request->bearerToken());

        // トークンが空かどうかをチェック
        if (empty($idTokenString)) {
            return response(['message' => 'Authorization token is required'], 401);
        }


        try {
            $verifiedIdToken = $this->auth->verifyIdToken($idTokenString);
        } catch (FailedToVerifyToken $e) {
            // トークンが無効、または取り消されている場合の処理
            return response(['message' => $e->getMessage()], 401);
        }

        $request->attributes->set('firebase_uid', $verifiedIdToken->claims()->get('sub'));

        return $next($request);
    }
}
