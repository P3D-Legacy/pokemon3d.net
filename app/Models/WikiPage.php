<?php

namespace App\Models;

use App\Models\Concerns\RecordsWikiRevisions;
use Database\Factories\WikiPageFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Tags\HasTags;

class WikiPage extends BaseModel implements HasMedia
{
    /** @use HasFactory<WikiPageFactory> */
    use HasFactory;

    use HasTags;
    use InteractsWithMedia;
    use RecordsWikiRevisions;
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'title',
        'slug',
        'body',
        'excerpt',
        'published',
        'user_id',
    ];

    protected static function booted(): void
    {
        static::creating(function (WikiPage $page): void {
            if (! $page->uuid) {
                $page->uuid = (string) Str::uuid();
            }

            if (! $page->slug) {
                $page->slug = Str::slug($page->title);
            }

            if (! $page->excerpt) {
                $page->excerpt = $page->makeExcerpt();
            }
        });

        static::updating(function (WikiPage $page): void {
            if ($page->isDirty('title') && ! $page->isDirty('slug')) {
                $page->slug = Str::slug($page->title);
            }

            if ($page->isDirty('body')) {
                $page->excerpt = $page->makeExcerpt();
            }
        });
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'published' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function registerMediaCollections(?Media $media = null): void
    {
        $this->addMediaCollection('images');
    }

    public function makeExcerpt(): string
    {
        return Str::limit(strip_tags((string) Str::of($this->body)->markdown()), 175);
    }

    /**
     * @param  Builder<WikiPage>  $query
     * @return Builder<WikiPage>
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('published', true);
    }

    /**
     * @param  Builder<WikiPage>  $query
     * @return Builder<WikiPage>
     */
    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (! filled($term)) {
            return $query;
        }

        return $query->where(function (Builder $builder) use ($term): void {
            $builder->where('title', 'like', "%{$term}%")
                ->orWhere('body', 'like', "%{$term}%")
                ->orWhere('excerpt', 'like', "%{$term}%");
        });
    }
}
