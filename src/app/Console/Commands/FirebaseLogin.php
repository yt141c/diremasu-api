<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Kreait\Firebase\Factory;

class FirebaseLogin extends Command
{
    protected $signature = 'fb:login {email} {password}';
    protected $description = 'Login to Firebase using email and password';

    public function handle()
    {
        $email = $this->argument('email');
        $password = $this->argument('password');

        $credintialsPath = config('firebase.projects.diremasu.credentials');
        $factory = (new Factory)->withServiceAccount($credintialsPath);
        $auth = $factory->createAuth();


        // ここでは、ユーザーのIDトークンやリフレッシュトークンを取得する
        $signInResult = $auth->signInWithEmailAndPassword($email, $password);


        $idToken = $signInResult->idToken();
        $refreshToken = $signInResult->refreshToken();

        $this->info('ID Token: ' . $idToken);
        $this->info('Refresh Token: ' . $refreshToken);
    }
}
