<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use betterapp\LaravelDbEncrypter\Traits\EncryptableDbAttribute;

class GameJoltAccount extends Model
{
    use HasFactory;
    use EncryptableDbAttribute;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'username',
        'token',
        'verified_at',
    ];

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
    protected $encryptable = [
        'token',
    ];

    /**
     * Get the user associated with the gamejolt account.
     */
    public function user()
    {
        return $this->hasOne(User::class);
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
}
