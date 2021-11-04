<?php

namespace App\Models;

use App\Models\GamejoltAccount;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;

class GamejoltAccountBan extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Uuid;

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
    protected $fillable = [
        'gamejoltaccount_id',
        'banned_by_id',
        'reason_id',
        'expire_at',
    ];
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'expire_at' => 'datetime',
    ];

    /**
     * Get the gamejolt account associated with the gamejolt account ban.
     */
    public function gamejoltaccount()
    {
        return $this->hasOne(GamejoltAccount::class, 'id', 'gamejoltaccount_id');
    }

    /**
     * Get the reason associated with the gamejolt account ban.
     */
    public function reason()
    {
        return $this->hasOne(BanReason::class, 'id', 'reason_id');
    }

    /**
     * Get the user associated with the gamejolt account ban.
     */
    public function banned_by()
    {
        return $this->hasOne(User::class, 'id', 'banned_by_id');
    }
}
