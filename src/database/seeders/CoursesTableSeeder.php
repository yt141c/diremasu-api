<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Course;
use League\Csv\Reader;

class CoursesTableSeeder extends Seeder
{
    public function run()
    {
        // CSVファイルのパス
        $csvPath =  storage_path('app/diremasu_course.csv');

        $csv = Reader::createFromPath($csvPath, 'r');
        $csv->setHeaderOffset(0); // CSVのヘッダーを設定

        foreach ($csv as $record) {
            Course::create([
                'name' => $record['name'],
                'title' => $record['title'],
                'description' => $record['description'],
                'published' => $record['published'] === 'true' ? 1 : 0,
            ]);
        }
    }
}
