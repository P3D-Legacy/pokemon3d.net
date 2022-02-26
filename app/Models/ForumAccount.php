<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use betterapp\LaravelDbEncrypter\Traits\EncryptableDbAttribute;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;

class ForumAccount extends Model
{
    use HasFactory;
    use EncryptableDbAttribute;
    use SoftDeletes;
    use Uuid;
    use LogsActivity;

    protected $primaryKey = 'uuid';

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
     *
     * @return array
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
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
