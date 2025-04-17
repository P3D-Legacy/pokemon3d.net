<?php

namespace App\Models;

use betterapp\LaravelDbEncrypter\Traits\EncryptableDbAttribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class GamejoltAccount extends BaseModel
{
    use EncryptableDbAttribute;
    use HasFactory;
    use LogsActivity;
    use SoftDeletes;

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
    protected $table = 'game_jolt_accounts';

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
    protected $fillable = ['id', 'username', 'token', 'verified_at', 'user_id'];

    /**
     * The attributes that should be encrypted/decrypted
     *
     * @var array
     */
    protected $encryptable = ['token'];

    /**
     * The attributes that should be hidden
     *
     * @var array
     */
    protected $hidden = ['token', 'aid'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'verified_at' => 'datetime',
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
     * The boot method of the model.
     */
    public static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }

    public function touchVerify()
    {
        $this->verified_at = $this->freshTimestamp();

        return $this->save();
    }

    /**
     * Get the user associated with the gamejolt account.
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * Get the skins that the user owns.
     */
    public function skins(): HasMany
    {
        return $this->hasMany(Skin::class, 'owner_id', 'id');
    }

    /**
     * Get the skins that the user owns.
     */
    public function publicSkins(): HasMany
    {
        return $this->hasMany(Skin::class, 'owner_id', 'id')->isPublic();
    }

    /**
     * Get the bans that the user has.
     */
    public function bans(): HasMany
    {
        return $this->hasMany(GamejoltAccountBan::class, 'gamejoltaccount_id', 'id');
    }

    /**
     * Get the trophies that the user has.
     */
    public function trophies(): HasMany
    {
        return $this->hasMany(GamejoltAccountTrophy::class, 'gamejolt_account_id', 'id')->orderBy('title', 'asc');
    }
}
