<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Section;
use League\Csv\Reader;

class SectionsTableSeeder extends Seeder
{
    public function run()
    {
        // CSVファイルのパス
        $csvPath = storage_path('app/diremasu_section.csv');

        // CSVを読み込む
        $csv = Reader::createFromPath($csvPath, 'r');
        $csv->setHeaderOffset(0);

        // 各レコードを処理する
        foreach ($csv as $record) {
            Section::create([
                'course_id' => $record['course_id'],
                'order' => $record['order'],
                'name' => $record['name'],
            ]);
        }
    }
}
