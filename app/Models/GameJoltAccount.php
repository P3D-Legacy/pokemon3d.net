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
        'username',
        'token',
        'updated_at',
        'verified_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'updated_at' => 'datetime',
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
}
