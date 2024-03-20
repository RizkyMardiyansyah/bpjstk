<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Diary extends Model
{

    protected $fillable = [
        'title',
        // 'theme',
        'description',
        'reference_type',
        'skills',
        'feedback',
        'organ_id',
        'dated_at',
    ];

    public static $referenceTypes = [
        'book',
        'article',
        'video',
        'coaching',
        'training',
        'mentoring',
        'other',
    ];

    protected $casts = [
        'dated_at' => 'datetime:d-m-Y',
        'created_at' => 'datetime:Y-m-d',
        'updated_at' => 'datetime:Y-m-d',
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
}
