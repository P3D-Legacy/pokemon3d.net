<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Overtrue\LaravelLike\Traits\Liker;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class GJUser extends BaseModel
{
    use HasFactory;
    use Liker;
    use LogsActivity;
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'gjuser';

    // Lets use the gjid for primary key
    protected $primaryKey = 'gjid';

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['gjid', 'gju', 'is_admin'];

    /**
     * The attributes that should be logged for the user.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty();
    }

    /**
     * Get the skins that the user owns.
     */
    public function skins(): HasMany
    {
        return $this->hasMany(Skin::class, 'owner_id', 'gjid');
    }

    /**
     * Get the skins that the user owns.
     */
    public function publicSkins(): HasMany
    {
        return $this->hasMany(Skin::class, 'owner_id', 'gjid')->isPublic();
    }
}
