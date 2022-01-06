<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Activity;
use Overtrue\LaravelLike\Traits\Likeable;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;

class Skin extends Model
{
    use HasFactory;
    use SoftDeletes;
    use LogsActivity;
    use Likeable;
    use Uuid;

    protected $primaryKey = 'uuid';

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

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
        'name',
        'owner_id',
        'user_id',
        'public',
    ];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'uuid';
    }

    protected static $logAttributes = [
        'name',
        'owner_id',
        'user_id',
        'public',
    ];
    protected static $logOnlyDirty = true;
    protected static $submitEmptyLogs = false;

    // Since we are using sessions with gamejolt logins we have to tap the activity to log the causer
    public function tapActivity(Activity $activity)
    {
        $activity->subject_id = $this->id;
        $activity->causer_id = Auth::user()->id ?? null;
        $activity->causer_type = User::class;
    }

    /**
     * Get the user that owns the skin.
     */
    public function gamejoltaccount()
    {
        return $this->belongsTo(GamejoltAccount::class, 'owner_id', 'id');
    }

    /**
     * Get the user that owns the skin.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function scopeIsPublic($query) {
        return $query->where('public', 1);
    }

    public function path() {
        return $this->uuid.'.png';
    }
    
    public function urlPath() {
        return env('APP_URL').'/img/skin/'.$this->path();
    }

}
