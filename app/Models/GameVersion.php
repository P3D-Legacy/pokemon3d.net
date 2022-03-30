<?php

namespace App\Models;

use App\Models\BaseModel;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Digikraaft\ReviewRating\Traits\HasReviewRating;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GameVersion extends BaseModel
{
    use HasFactory;
    use HasReviewRating;
    use LogsActivity;

    protected $fillable = ['version', 'title', 'release_date', 'page_url', 'download_url'];

    protected $casts = [
        'release_date' => 'datetime',
    ];

    /**
     * The attributes that should be logged for the user.
     *
     * @return array
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
}
