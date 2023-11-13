<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lecture extends Model
{
    use HasFactory, SoftDeletes;

    protected $appends = ['public_id', 'public_section_id'];

    protected $fillable = ['section_id', 'name', 'description', 'order', 'video_url', 'published', 'trial'];

    protected $hidden = [
        'id',
        'section_id',
        'is_premium',
        'public_section_id',
        'published',
        'deleted_at',
        'created_at',
        'updated_at',
        'description'
    ];


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

    public function getHashIdAttribute()
    {
        return app('hashids')->encode($this->id);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    // idをハッシュ化する
    public function getPublicIdAttribute()
    {
        return app('hashids')->encode($this->id);
    }

    public function getPublicSectionIdAttribute()
    {
        return app('hashids')->encode($this->section_id);
    }
}
