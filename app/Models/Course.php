<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Course extends Model
{
    protected $fillable = [
        'name',
        'description',
        'duration',
        'fee',
        'difficulty',
        'is_active',
    ];

    /**
     * The user who owns the course.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
