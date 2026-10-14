<?php

namespace App\Models;

use Database\Factories\WikiRedirectFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class WikiRedirect extends BaseModel
{
    /** @use HasFactory<WikiRedirectFactory> */
    use HasFactory;

    protected $fillable = [
        'from_slug',
        'redirectable_type',
        'redirectable_id',
    ];

    public function redirectable(): MorphTo
    {
        return $this->morphTo();
    }
}
