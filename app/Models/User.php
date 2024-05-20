<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Assada\Achievements\Achiever;
use Carbon\Carbon;
use DateTimeInterface;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Glorand\Model\Settings\Traits\HasSettingsTable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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
    use Achiever;
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
        return $this->hasRole('moderator') || $this->hasRole('admin') || $this->hasRole('super-admin') && $this->hasVerifiedEmail();
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
     * Get the twitter account associated with the user.
     */
    public function twitter(): HasOne
    {
        return $this->hasOne(TwitterAccount::class);
    }

    /**
     * Get the facebook account associated with the user.
     */
    public function facebook(): HasOne
    {
        return $this->hasOne(FacebookAccount::class);
    }

    /**
     * Get the facebook account associated with the user.
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
     * Get the gamesave associated with the user.
     */
    public function gamesave(): HasOne
    {
        return $this->hasOne(GameSave::class);
    }

    public function scopeVerified($query)
    {
        return $query->whereNotNull('email_verified_at');
    }
}
