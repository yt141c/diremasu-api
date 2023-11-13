<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\User;
use App\Models\UserLessonProgress;
use Illuminate\Support\Facades\Log;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $courses = Course::all();
        return response()->json(['courses' => $courses], 200);
    }

    // /**
    //  * Store a newly created resource in storage.
    //  */
    // public function store(Request $request)
    // {
    //    
    // }

    // /**
    //  * Display the specified resource.
    //  */
    public function showTrial(string $name)
    {
        try {
            // nameでコースを検索、取得したコースに紐づくセクションとレクチャーを取得する。
            $course = Course::with('sections.lectures')->where('name', $name)->firstOrFail();
            return response()->json(['course' => $course], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Course not found'], 404);
        }
    }

    // /**
    //  * Display the specified resource.
    //  */
    public function show(Request $request, string $name)
    {
        try {
            $course = Course::with('sections.lectures')->where('name', $name)->firstOrFail();

            $firebaseUid = $request->attributes->get('firebase_uid');
            $user = User::where('firebase_uid', $firebaseUid)->firstOrFail();

            $completedLectures = UserLessonProgress::where('user_id', $user->id)
                ->pluck('completed_at', 'lecture_id');

            // 各レクチャーに完了フラグを追加
            foreach ($course->sections as $section) {
                foreach ($section->lectures as $lecture) {
                    $lecture->completed = $completedLectures->has($lecture->id);
                }
            }

            return response()->json(['course' => $course], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Course not found'], 404);
        }
    }

    // /**
    //  * Update the specified resource in storage.
    //  */
    // public function update(Request $request, string $id)
    // {
    //     
    // }

    // /**
    //  * Remove the specified resource from storage.
    //  */
    // public function destroy(string $id)
    // {
    //     //
    // }
}
