<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Digikraaft\ReviewRating\Traits\HasReviewRating;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GameVersion extends Model
{
    use HasFactory;
    use HasReviewRating;
    use LogsActivity;

    protected $fillable = ['version', 'title', 'release_date', 'page_url', 'download_url'];

    protected $dates = ['release_date'];

    public static function latest()
    {
        return self::query()
            ->orderBy('release_date', 'desc')
            ->first();
    }
}
