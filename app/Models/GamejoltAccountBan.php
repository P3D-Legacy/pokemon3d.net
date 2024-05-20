<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class GamejoltAccountBan extends BaseModel
{
    use HasFactory;
    use LogsActivity;
    use SoftDeletes;

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
     * The attributes that will be used for multiple key binding on route models
     *
     * @var array
     */
    protected $routeBindingKeys = ['uuid'];

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
    protected $fillable = ['gamejoltaccount_id', 'banned_by_id', 'reason_id', 'expire_at'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'expire_at' => 'datetime',
        ];
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
     * Get the gamejolt account associated with the gamejolt account ban.
     */
    public function gamejoltaccount(): BelongsTo
    {
        return $this->belongsTo(GamejoltAccount::class, 'gamejoltaccount_id');
    }

    /**
     * Get the reason associated with the gamejolt account ban.
     */
    public function reason(): BelongsTo
    {
        return $this->belongsTo(BanReason::class);
    }

    /**
     * Get the user associated with the gamejolt account ban.
     */
    public function banned_by(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
