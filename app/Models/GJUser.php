<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\LogOptions;
use Overtrue\LaravelLike\Traits\Liker;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Overtrue\LaravelLike\Traits\Liker;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class GJUser extends Model
{
    use HasFactory;
    use SoftDeletes;
    use LogsActivity;
    use Liker;

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
     *
     * @return array
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
    public function skins()
    {
        return $this->hasMany(Skin::class, 'owner_id', 'gjid');
    }

    /**
     * Get the skins that the user owns.
     */
    public function publicSkins()
    {
        return $this->hasMany(Skin::class, 'owner_id', 'gjid')->isPublic();
    }
}
