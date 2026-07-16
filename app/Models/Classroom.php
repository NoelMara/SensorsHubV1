<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    protected $table = 'classes';

    protected $fillable = [
        'name',
        'section',
        'code',
        'description',
        'instructor_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'class_student', 'class_id', 'user_id');
    }

    public static function generateCode()
    {
        return strtoupper(substr(md5(uniqid()), 0, 6));
    }
}