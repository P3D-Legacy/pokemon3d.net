<?php

namespace App\Models;

use Database\Factories\WikiRevisionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class WikiRevision extends BaseModel
{
    /** @use HasFactory<WikiRevisionFactory> */
    use HasFactory;

    protected $fillable = [
        'revisionable_type',
        'revisionable_id',
        'user_id',
        'title',
        'body',
        'snapshot',
        'summary',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'snapshot' => 'array',
        ];
    }

    public function revisionable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
