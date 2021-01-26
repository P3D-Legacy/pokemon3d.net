<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GJUser extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'gjuser';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'gjid',
        'gju',
        'is_admin',
    ];

    protected static $logAttributes = [
        'gjid',
        'gju',
        'is_admin',
    ];
    protected static $logOnlyDirty = true;
    protected static $submitEmptyLogs = false;

    // Since we are using sessions with gamejolt logins we have to tap the activity to log the causer
    public function tapActivity(Activity $activity)
    {
        $activity->causer_id = $this->where('gjid', session()->get('gjid'))->first()->id;
        $activity->causer_type = get_class($this);
    }
}
