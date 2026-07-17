<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivitySubmission extends Model
{
    protected $fillable = [
        'activity_id',
        'user_id',
        'content',
        'file_path',
        'score',
        'feedback',
        'status',
        'submitted_at',
    ];

    protected $casts = [
        'score' => 'integer',
        'submitted_at' => 'datetime',
    ];

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}