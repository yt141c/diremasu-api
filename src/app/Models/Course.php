<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use SoftDeletes;

    // テーブル名を指定。規約に従っている場合は不要。

    // マスアサインメントで許可する属性
    protected $fillable = [
        'name',
        'description',
        'published'
    ];

    // apiで取得できない属性
    protected $hidden = [
        'id',
        'deleted_at',
        'published',
        'created_at',
        'updated_at',
    ];

    // apiに付け加える属性
    protected $appends = ['public_id'];

    // キャストする属性
    protected $casts = [
        'published' => 'boolean',
    ];

    // モデルのデフォルト値を定義
    protected $attributes = [
        'published' => false,
    ];

    // 日付へキャストする属性
    protected $dates = ['deleted_at'];

    // idをハッシュ化する
    public function getPublicIdAttribute()
    {
        return app('hashids')->encode($this->id);
    }

    // sectonへのリレーション
    public function sections()
    {
        return $this->hasMany('App\Models\Section');
    }
}
