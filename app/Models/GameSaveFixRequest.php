<?php

namespace App\Models;

use App\Enums\GameSaveFixRequestStatus;
use Database\Factories\GameSaveFixRequestFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class GameSaveFixRequest extends BaseModel
{
    /** @use HasFactory<GameSaveFixRequestFactory> */
    use HasFactory;

    use LogsActivity;

    protected $primaryKey = 'uuid';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'assignee_id',
        'description',
        'status',
        'consent_accepted_at',
        'consent_text',
        'resolved_at',
        'notify_database',
        'notify_mail',
        'stale_notified_at',
    ];

    protected static function boot(): void
    {
        parent::boot();

        self::creating(function (GameSaveFixRequest $model): void {
            if (! $model->uuid) {
                $model->uuid = Str::uuid()->toString();
            }
        });
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => GameSaveFixRequestStatus::class,
            'consent_accepted_at' => 'datetime',
            'resolved_at' => 'datetime',
            'notify_database' => 'boolean',
            'notify_mail' => 'boolean',
            'stale_notified_at' => 'datetime',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty();
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->whereIn('status', [
            GameSaveFixRequestStatus::Open,
            GameSaveFixRequestStatus::Claimed,
        ]);
    }

    public function scopeStale(Builder $query, int $days = 7): Builder
    {
        $threshold = now()->subDays($days);

        return $query
            ->active()
            ->where('updated_at', '<=', $threshold)
            ->where(function (Builder $builder) use ($threshold): void {
                $builder
                    ->whereNull('stale_notified_at')
                    ->orWhere('stale_notified_at', '<=', $threshold);
            });
    }

    public function markStaleNotified(): void
    {
        static::withoutTimestamps(function (): void {
            $this->forceFill([
                'stale_notified_at' => now(),
            ])->saveQuietly();
        });
    }
}
