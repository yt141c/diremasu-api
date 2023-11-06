<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Lecture;
use League\Csv\Reader;

class LecturesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // CSVファイルのパス
        $csvPath = storage_path('app/diremasu_lecture.csv');

        // CSVを読み込む
        $csv = Reader::createFromPath($csvPath, 'r');
        $csv->setHeaderOffset(0);

        // 各レコードを処理する
        foreach ($csv as $record) {
            Lecture::create([
                'section_id' => $record['section_id'],
                'order' => $record['order'],
                'name' => $record['name'],
                'description' => $record['description'],
                'video_url' => $record['video_url'],
            ]);
        }
    }
}
