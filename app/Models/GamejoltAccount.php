<?php

namespace App\Models;

use betterapp\LaravelDbEncrypter\Traits\EncryptableDbAttribute;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GamejoltAccount extends Model
{
    use HasFactory;
    use EncryptableDbAttribute;
    use SoftDeletes;
    use Uuid;

    protected $primaryKey = 'uuid';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'game_jolt_accounts';

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
    protected $fillable = ['id', 'username', 'token', 'verified_at', 'user_id'];

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
    protected $encryptable = ['token'];

    /**
     * The attributes that should be hidden
     *
     * @var array
     */
    protected $hidden = ['token', 'aid'];

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

    /**
     * Get the skins that the user owns.
     */
    public function skins()
    {
        return $this->hasMany(Skin::class, 'owner_id', 'id');
    }

    /**
     * Get the skins that the user owns.
     */
    public function publicSkins()
    {
        return $this->hasMany(Skin::class, 'owner_id', 'id')->isPublic();
    }

    /**
     * Get the bans that the user has.
     */
    public function bans()
    {
        return $this->hasMany(GamejoltAccountBan::class, 'gamejoltaccount_id', 'id');
    }

    /**
     * Get the trophies that the user has.
     */
    public function trophies()
    {
        return $this->hasMany(GamejoltAccountTrophy::class, 'gamejolt_account_id', 'id')->orderBy('title', 'asc');
    }
}
