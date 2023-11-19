<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Kreait\Firebase\Exception\Auth\UserNotFound;
use Kreait\Firebase\Contract\Auth as FirebaseAuth;
use App\Models\User;

class UserController extends Controller
{
    /**
     * 新規ユーザーを作成します。
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // Firebase認証済みのUIDを取得
        $uid = $request->attributes->get('firebase_uid');
        $name = $request->input('name');


        // 既存のユーザーを確認
        $existingUser = User::where('firebase_uid', $uid)->first();
        if ($existingUser) {
            // ユーザーが既に存在する場合
            return response()->json(['user' => $existingUser], 200);
        }

        // 新規ユーザーをデータベースに登録
        $user = User::create([
            'firebase_uid' => $uid,
            'name' => $name,
        ]);

        // 新規ユーザー情報をレスポンスとして返却
        return response()->json(['user' => $user], 201);
    }

    /**
     * ユーザーが存在するかどうかを確認します。
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $uid = $request->attributes->get('firebase_uid');

        $userExists = User::where('firebase_uid', $uid)->exists();
        return response()->json(['exists' => $userExists]);
    }

    /**
     * ユーザー情報を取得します。
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $uid = $request->attributes->get('firebase_uid');
        Log::info($uid);

        $user = User::where('firebase_uid', $uid)->first();
        Log::info($user);
        if ($user) {
            return response()->json(['user' => $user]);
        } else {
            return response()->json(['message' => 'User not found'], 404);
        }
    }


    /**
     * ユーザー情報を削除します。
     *
     * @param Request $request
     * @param Auth $firebaseAuth
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, FirebaseAuth $firebaseAuth)
    {
        DB::beginTransaction();

        try {
            $uid = $request->attributes->get('firebase_uid');
            Log::info('Deleting user with UID: ' . $uid);

            $user = User::where('firebase_uid', $uid)->first();
            if (!$user) {
                DB::rollBack();
                return response()->json(['message' => 'User not found'], 404);
            }

            $user->delete();

            try {
                $firebaseAuth->deleteUser($uid);
            } catch (UserNotFound $e) {
                Log::error('Firebase user not found: ' . $e->getMessage());
                DB::rollBack();
                return response()->json(['message' => 'Firebase user not found'], 404);
            }

            DB::commit();
            return response()->json(['message' => 'User deleted'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting user: ' . $e->getMessage());
            return response()->json(['message' => 'Error deleting user'], 500);
        }
    }
}
