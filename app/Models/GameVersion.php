<?php

namespace App\Models;

use Digikraaft\ReviewRating\Traits\HasReviewRating;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class GameVersion extends BaseModel
{
    use HasFactory;
    use HasReviewRating;
    use LogsActivity;

    protected $fillable = ['version', 'title', 'release_date', 'page_url', 'download_url', 'post_id'];

    protected $casts = [
        'release_date' => 'datetime',
    ];

    /**
     * The attributes that should be logged for the user.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty();
    }

    public static function latest()
    {
        return self::query()
            ->orderBy('release_date', 'desc')
            ->first();
    }

    public function post(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Post::class, 'id', 'post_id');
    }
}
