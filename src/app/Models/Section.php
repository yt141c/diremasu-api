<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Section extends Model
{
    use HasFactory, SoftDeletes;

    protected $appends = ['public_id', 'public_course_id'];

    protected $fillable = [
        'course_id',
        'order',
        'name',
    ];

    protected $hidden = [
        'id',
        'course_id',
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function lectures()
    {
        return $this->hasMany(Lecture::class);
    }

    // idをハッシュ化する
    public function getPublicIdAttribute()
    {
        return app('hashids')->encode($this->id);
    }

    public function getPublicCourseIdAttribute()
    {
        return app('hashids')->encode($this->course_id);
    }
}
