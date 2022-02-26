<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DiscordRole extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $primaryKey = 'id';

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
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['color', 'hoist', 'id', 'managed', 'mentionable', 'name', 'permissions', 'position'];

    /**
     * The attributes that should be logged for the user.
     *
     * @return array
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }

    public function accounts()
    {
        return $this->belongsToMany(DiscordAccount::class);
    }
}
