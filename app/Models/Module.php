<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $fillable = [
    'class_id',
    'title',
    'content',
    'file_path',
    'file_name',
    'order',
    'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'order' => 'integer',
    ];

    public function class()
    {
        return $this->belongsTo(Classroom::class, 'class_id');
    }
}