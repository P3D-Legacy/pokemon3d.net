<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ResourceUpdate extends Model implements HasMedia
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
    protected $fillable = [
        'title',
        'description',
        'resource_id',
        'game_version_id',
        'downloads',
    ];

    /**
     * The attributes that should be logged for the user.
     *
     * @return array
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty();
    }

    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }

    public function registerMediaCollections(Media $media = null): void
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
    public function game_version()
    {
        return $this->hasOne(GameVersion::class, 'id', 'game_version_id');
    }
}
