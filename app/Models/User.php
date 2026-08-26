<?php

namespace App\Models;

use Carbon\Carbon;
use DateTimeInterface;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Glorand\Model\Settings\Traits\HasSettingsTable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Origami\Consent\GivesConsent;
use Overtrue\LaravelLike\Traits\Liker;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser, MustVerifyEmail
{
    use GivesConsent;
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasRoles;
    use HasSettingsTable;
    use Liker;
    use LogsActivity;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'gender',
        'location',
        'about',
        'birthdate',
        'last_active_at',
        'timezone',
        'gamejolt_emblem',
        'profile_background',
        'created_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token', 'two_factor_recovery_codes', 'two_factor_secret'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['profile_photo_url'];

    public $defaultSettings = [
        'name' => true,
        'birthdate' => false,
        'age' => false,
    ];

    /**
     * The attributes that will be used for multiple key binding on route models
     */
    protected array $routeBindingKeys = ['username'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_active_at' => 'datetime',
            'birthdate' => 'date:d-m-Y',
        ];
    }

    /**
     * The attributes that should be logged for the user.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->logExcept(['last_active_at']);
    }

    // Overrides datetime object serialization
    protected function serializeDate(DateTimeInterface $date): string
    {
        $carbonInstance = Carbon::instance($date);

        return $carbonInstance->toDateTimeString();
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->hasVerifiedEmail()
            && $this->hasAnyRole(['moderator', 'admin', 'super-admin']);
    }

    /**
     * Get the URL to the user's profile photo.
     *
     * Prefer the configured public disk URL so pages do not need a fully
     * initialised S3 client just to render an avatar. Fall back to the default
     * avatar when object storage is misconfigured.
     */
    protected function profilePhotoUrl(): Attribute
    {
        return Attribute::get(function (): string {
            if (! $this->profile_photo_path) {
                return $this->defaultProfilePhotoUrl();
            }

            $disk = $this->profilePhotoDisk();
            $baseUrl = config("filesystems.disks.{$disk}.url")
                ?: config('filesystems.object_public_url');

            if (! filled($baseUrl)) {
                return $this->defaultProfilePhotoUrl();
            }

            return rtrim($baseUrl, '/').'/'.ltrim($this->profile_photo_path, '/');
        });
    }

    /**
     * Get the gamejolt account associated with the user.
     */
    public function gamejolt(): HasOne
    {
        return $this->hasOne(GamejoltAccount::class);
    }

    /**
     * Get the discord account associated with the user.
     */
    public function discord(): HasOne
    {
        return $this->hasOne(DiscordAccount::class);
    }

    /**
     * Get the forum account associated with the user.
     */
    public function forum(): HasOne
    {
        return $this->hasOne(ForumAccount::class);
    }

    /**
     * Get the twitch account associated with the user.
     */
    public function twitch(): HasOne
    {
        return $this->hasOne(TwitchAccount::class);
    }

    /**
     * Get the resources associated with the user.
     */
    public function resources(): HasMany
    {
        return $this->hasMany(Resource::class);
    }

    /**
     * Get the resources the user is following.
     */
    public function followedResources(): BelongsToMany
    {
        return $this->belongsToMany(Resource::class, 'resource_followers')->withTimestamps();
    }

    /**
     * Get the gamesave associated with the user.
     */
    public function gamesave(): HasOne
    {
        return $this->hasOne(GameSave::class);
    }

    /**
     * Get the save fix requests opened by the user.
     */
    public function gameSaveFixRequests(): HasMany
    {
        return $this->hasMany(GameSaveFixRequest::class);
    }

    public function scopeVerified($query)
    {
        return $query->whereNotNull('email_verified_at');
    }
}
