<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Overtrue\LaravelLike\Traits\Likeable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Skin extends BaseModel
{
    use HasFactory;
    use Likeable;
    use LogsActivity;
    use SoftDeletes;

    protected $primaryKey = 'id';

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

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
    protected $fillable = ['name', 'owner_id', 'user_id', 'public'];

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

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
     * The boot method of the model.
     */
    public static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
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

    public function scopeIsPublic($query)
    {
        return $query->where('public', 1);
    }

    public function path()
    {
        return $this->uuid.'.png';
    }

    public function urlPath()
    {
        return env('APP_URL').'/img/skin/'.$this->path();
    }
}
