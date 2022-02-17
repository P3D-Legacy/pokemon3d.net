<?php

namespace App\Models;

use Overtrue\LaravelLike\Traits\Liker;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GJUser extends Model
{
    use HasFactory, SoftDeletes, LogsActivity, Liker;

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

    protected static $logAttributes = ['gjid', 'gju', 'is_admin'];
    protected static $logOnlyDirty = true;
    protected static $submitEmptyLogs = false;

    // Since we are using sessions with gamejolt logins we have to tap the activity to log the causer
    public function tapActivity(Activity $activity)
    {
        $activity->causer_id = $this->where('gjid', session()->get('gjid'))->first()->id;
        $activity->causer_type = get_class($this);
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
