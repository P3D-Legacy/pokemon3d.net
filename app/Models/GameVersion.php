<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameVersion extends Model
{
    use HasFactory;

    protected $fillable = ['version', 'title', 'release_date', 'page_url', 'download_url'];

    protected $dates = ['release_date'];

    public static function latest()
    {
        return self::query()
            ->orderBy('release_date', 'desc')
            ->first();
    }
}
