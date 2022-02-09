<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ResourceUpdate extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $guarded = [];

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
