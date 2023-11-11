<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Exception\Auth\UserNotFound;
use Kreait\Firebase\Exception\Auth\EmailExists as FirebaseEmailExists;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class FirebaseAuthService
{
    private $auth;

    const USER_CREATED = 201;
    const EMAIL_ALREADY_IN_USE = 409;
    const UNKNOWN_ERROR = 500;

    public function __construct()
    {
        $credentialsPath = base_path(config('firebase.projects.diremasu.credentials'));
        $this->auth = (new Factory)->withServiceAccount($credentialsPath)->createAuth();
    }


    /**
     * ログインユーザーの情報を取得します。
     *
     * @param string $uid FirebaseユーザーID
     * @return array レスポンスデータ
     */
    public function signInUser(array $credentials): array
    {
        try {
            $signInResult = $this->auth->signInWithEmailAndPassword($credentials['email'], $credentials['password']);
            // ここで必要なユーザー情報やトークンなどを取得してレスポンスに含める
            return ['user' => $signInResult->data(), 'status' => 200];
        } catch (\Throwable $e) {
            Log::error('An error occurred while signing in: ' . $e->getMessage());
            return $this->errorResponse('Failed to sign in', self::UNKNOWN_ERROR);
        }
    }


    /**
     * 新しいFirebaseユーザーを作成し、ローカルデータベースに同期します。
     *
     * @param array $userData ユーザー登録データ
     * @return array レスポンスデータ
     */
    public function createUser(array $userData): array
    {
        $firebaseUserResponse = $this->createFirebaseUser($userData);

        if ($this->hasError($firebaseUserResponse)) {
            return $firebaseUserResponse;
        }

        // 新規作成したユーザーでサインイン
        $signInResponse = $this->signInUser($userData);
        if ($this->hasError($signInResponse)) {
            return $signInResponse;
        }

        // DBにユーザーを作成
        $firebaseUser = $firebaseUserResponse['user'];
        $localUserResponse = $this->createLocalUser($firebaseUser, $userData);

        if ($this->hasError($localUserResponse)) {
            return $localUserResponse;
        }

        return $signInResponse;
    }

    /**
     * Firebaseにユーザーを作成します。
     *
     * @param array $userData ユーザー登録データ
     * @return array レスポンスデータ
     */
    private function createFirebaseUser(array $userData): array
    {
        try {
            $userProperties = $this->prepareUserProperties($userData);
            $createdUser = $this->auth->createUser($userProperties);
            return ['user' => $createdUser];
        } catch (FirebaseEmailExists $e) {
            Log::warning('Attempted to create a user with an existing email: ' . $userData['email']);
            return $this->errorResponse('The provided email is already in use', self::EMAIL_ALREADY_IN_USE);
        } catch (\Throwable $e) {
            Log::error('An unexpected error occurred while creating Firebase user: ' . $e->getMessage());
            return $this->errorResponse('An unexpected error occurred', self::UNKNOWN_ERROR);
        }
    }

    /**
     * ローカルデータベースにFirebaseユーザーを同期します。
     *
     * @param $firebaseUser Firebaseユーザーレコード
     * @param array $userData ユーザー登録データ
     * @return array レスポンスデータ
     */
    private function createLocalUser($firebaseUser, array $userData): array
    {
        try {
            $localUser = User::create([
                'firebase_uid' => $firebaseUser->uid,
                'name' => $userData['name'],
            ]);
            $this->auth->sendEmailVerificationLink($userData['email']);
            return ['user' => $localUser, 'status' => self::USER_CREATED];
        } catch (\Throwable $e) {
            $this->auth->deleteUser($firebaseUser->uid);
            Log::error('An error occurred while creating local user: ' . $e->getMessage());
            return $this->errorResponse('Failed to create local user record', self::UNKNOWN_ERROR);
        }
    }

    /**
     * ユーザープロパティを準備します。
     *
     * @param array $userData ユーザー登録データ
     * @return array ユーザープロパティ
     */
    private function prepareUserProperties(array $userData): array
    {
        return [
            'email' => $userData['email'],
            'emailVerified' => false,
            'password' => $userData['password'],
            'displayName' => $userData['name'],
            'disabled' => false,
        ];
    }

    /**
     * エラーレスポンスを生成します。
     *
     * @param string $message エラーメッセージ
     * @param int $status HTTPステータスコード
     * @return array エラーレスポンス
     */
    private function errorResponse($message, $status): array
    {
        return ['error' => $message, 'status' => $status];
    }

    /**
     * レスポンスデータにエラーが含まれているかを確認します。
     *
     * @param array $response レスポンスデータ
     * @return bool エラーが含まれている場合はtrue
     */
    private function hasError(array $response): bool
    {
        return isset($response['error']);
    }
}
