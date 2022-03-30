<?php

namespace App\Models;

use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BaseModel extends Model
{
    use HasFactory;

    // Overrides datetime object serialization
    protected function serializeDate(DateTimeInterface $date)
    {
        $carbonInstance = Carbon::instance($date);
        return $carbonInstance->toDateTimeString();
    }
}
