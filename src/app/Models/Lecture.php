<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lecture extends Model
{
    use HasFactory, SoftDeletes;

    protected $appends = ['hash_id'];

    protected $fillable = ['section_id', 'title', 'description', 'order', 'video_url', 'published'];

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

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function getHashIdAttribute()
    {
        return app('hashids')->encode($this->id);
    }
}
