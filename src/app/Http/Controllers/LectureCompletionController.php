<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserLessonProgress;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Log;

use function Psy\debug;

class LectureCompletionController extends Controller
{
    public function store(Request $request)
    {
        try {
            // ハッシュ化されたlectureIdをデコード
            $decodedIds = app('hashids')->decode($request->input('lectureId'));
            if (empty($decodedIds)) {
                return response()->json(['message' => 'Invalid lecture ID'], 400);
            }

            // 最初の要素を取り出す（Hashidsは配列を返す）
            $lectureId = $decodedIds[0];

            // バリデーション
            Validator::make(['lecture_id' => $lectureId], [
                'lecture_id' => 'required|exists:lectures,id',
            ])->validate();

            $firebaseUid = $request->attributes->get('firebase_uid');
            $user = User::where('firebase_uid', $firebaseUid)->firstOrFail();


            $progress = UserLessonProgress::firstOrCreate([
                'user_id' => $user->id,
                'lecture_id' => $lectureId,
            ], [
                'completed_at' => now(),
            ]);

            return response()->json(['message' => 'Operation successful'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Lecture not found'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }
}
