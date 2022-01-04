<?php

namespace App\Models;

use Assada\Achievements\Achiever;
use Laravel\Sanctum\HasApiTokens;
use Origami\Consent\GivesConsent;
use Laravel\Jetstream\HasProfilePhoto;
use Overtrue\LaravelLike\Traits\Liker;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Glorand\Model\Settings\Traits\HasSettingsTable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail 
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRoles;
    use GivesConsent;
    use Liker;
    use Achiever;
    use HasSettingsTable;

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
        'birtdate',
        'last_active_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_active_at' => 'datetime',
        'birthdate' => 'date:d-m-Y',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public $defaultSettings = [
        'name' => true,
        'birthdate' => false,
        'age' => false,
    ];

    /**
     * Get the gamejolt account associated with the user.
     */
    public function gamejolt()
    {
        return $this->hasOne(GamejoltAccount::class);
    }

    /**
     * Get the discord account associated with the user.
     */
    public function discord()
    {
        return $this->hasOne(DiscordAccount::class);
    }

    /**
     * Get the forum account associated with the user.
     */
    public function forum()
    {
        return $this->hasOne(ForumAccount::class);
    }

    /**
     * Get the twitter account associated with the user.
     */
    public function twitter()
    {
        return $this->hasOne(TwitterAccount::class);
    }

    /**
     * Get the facebook account associated with the user.
     */
    public function facebook()
    {
        return $this->hasOne(FacebookAccount::class);
    }

    /**
     * Get the facebook account associated with the user.
     */
    public function twitch()
    {
        return $this->hasOne(TwitchAccount::class);
    }
}
