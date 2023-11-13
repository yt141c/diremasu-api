<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Log;

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
}
