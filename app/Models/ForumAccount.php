<?php

namespace App\Models;

use betterapp\LaravelDbEncrypter\Traits\EncryptableDbAttribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ForumAccount extends BaseModel
{
    use EncryptableDbAttribute;
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
    protected $fillable = ['username', 'password', 'verified_at', 'user_id'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'verified_at' => 'datetime',
    ];

    /**
     * The attributes that should be encrypted/decrypted
     *
     * @var array
     */
    protected $encryptable = ['password'];

    /**
     * The attributes that should be hidden
     *
     * @var array
     */
    protected $hidden = ['password', 'aid'];

    /**
     * The attributes that should be logged for the user.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty();
    }

    public function touchVerify()
    {
        $this->verified_at = $this->freshTimestamp();

        return $this->save();
    }

    /**
     * Get the user associated with the gamejolt account.
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
