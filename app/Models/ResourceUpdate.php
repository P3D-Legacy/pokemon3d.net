<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ResourceUpdate extends BaseModel implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    use LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'description', 'resource_id', 'game_version_id', 'downloads'];

    /**
     * The attributes that should be logged for the user.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty();
    }

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    public function registerMediaCollections(?Media $media = null): void
    {
        $this->addMediaCollection('resource_update_file')->singleFile();
    }

    public function incrementDownload()
    {
        $this->downloads++;
        $this->save();
    }

    /**
     * Get the game_version related to this resource.
     */
    public function game_version(): HasOne
    {
        return $this->hasOne(GameVersion::class, 'id', 'game_version_id');
    }
}
