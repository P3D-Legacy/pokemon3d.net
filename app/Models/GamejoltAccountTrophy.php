<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class GamejoltAccountTrophy extends BaseModel
{
    use HasFactory;
    use LogsActivity;

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->uuid = Str::uuid()->toString();
        });

        self::updating(function ($model) {
            if (! $model->uuid) {
                $model->uuid = Str::uuid()->toString();
            }
        });
    }

    protected $primaryKey = 'aid';

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
     * The attributes that will be used for multiple key binding on route models
     *
     * @var array
     */
    protected $routeBindingKeys = ['uuid'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'gamejolt_account_trophies';

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
    protected $fillable = ['id', 'title', 'difficulty', 'description', 'image_url', 'achieved', 'gamejolt_account_id'];

    /**
     * The attributes that should be hidden
     *
     * @var array
     */
    protected $hidden = ['aid'];

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
     * Get the gamejolt account associated with the gamejolt account ban.
     */
    public function account()
    {
        return $this->hasOne(GamejoltAccount::class, 'id', 'gamejolt_account_id');
    }
}
