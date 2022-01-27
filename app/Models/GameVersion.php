<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameVersion extends Model
{
    use HasFactory;

    protected $fillable = [
        'version',
        'title',
        'release_date',
        'page_url',
        'downloadable_url',
    ];

    protected $dates = [
        'release_date',
    ];

    public function latest()
    {
        return $this->orderBy('release_date', 'desc')->first();
    }

}
