<?php

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GamejoltAccountTrophy extends Model
{
    use HasFactory;
    use Uuid;

    protected $primaryKey = 'uuid';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'gamejolt_account_trophies';

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
    protected $fillable = ['id', 'title', 'difficulty', 'description', 'image_url', 'achieved', 'gamejolt_account_id'];

    /**
     * The attributes that should be hidden
     *
     * @var array
     */
    protected $hidden = ['aid'];

    /**
     * Get the gamejolt account associated with the gamejolt account ban.
     */
    public function account()
    {
        return $this->hasOne(GamejoltAccount::class, 'id', 'gamejolt_account_id');
    }
}
