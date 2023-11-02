<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Kreait\Firebase\Factory;

class FirebaseLogin extends Command
{
    protected $signature = 'firebase:login {email} {password}';
    protected $description = 'Login to Firebase using email and password';

    public function handle()
    {
        $email = $this->argument('email');
        $password = $this->argument('password');

        $credintialsPath = config('firebase.projects.diremasu.credentials');
        $factory = (new Factory)->withServiceAccount($credintialsPath); // サービスアカウントのパスを指定
        $auth = $factory->createAuth();


        // ここでは、ユーザーのIDトークンやリフレッシュトークンを取得する方法を示します
        // 実際にユーザーとしてログインすることはできませんが、これらのトークンを取得することは可能です

        $signInResult = $auth->signInWithEmailAndPassword($email, $password);


        $idToken = $signInResult->idToken(); // IDトークン
        $refreshToken = $signInResult->refreshToken(); // リフレッシュトークン

        $this->info('ID Token: ' . $idToken);
        $this->info('Refresh Token: ' . $refreshToken);
    }
}