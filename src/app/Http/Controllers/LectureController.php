<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lecture;
use App\Http\Resources\LectureResource;

class LectureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     */
    public function show(string $hashid)
    {
        // hashidをidにデコードしてlectureを取得する
        // $ids = app('hashids')->decode($hashid);
        $ids[0] = $hashid;

        //該当のlectureがなければ404を返す
        if (empty($ids)) {
            return response()->json(['message' => 'Lecture not found'], 404);
        }
        // デコードされたIDを使ってレクチャーを取得
        $lecture = Lecture::with('section.course')->findOrFail($ids[0]);

        // 前後のレクチャーを取得
        $prevLecture = Lecture::where('order', '<', $lecture->order)
            ->orderBy('order', 'desc')
            ->first();

        $nextLecture = Lecture::where('order', '>', $lecture->order)
            ->orderBy('order', 'asc')
            ->first();

        // LectureResourceのインスタンスを作成し、追加のデータを渡す
        $resource = new LectureResource($lecture);
        $resource->additional(['data' => [
            'prev_lecture_public_id' => $prevLecture ? app('hashids')->encode($prevLecture->id) : null,
            'next_lecture_public_id' => $nextLecture ? app('hashids')->encode($nextLecture->id) : null,
        ]]);

        return $resource;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
