<?php

namespace App\Enums;

enum GameSaveFixRequestStatus: string
{
    case Open = 'open';
    case Claimed = 'claimed';
    case Resolved = 'resolved';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Open => 'Open',
            self::Claimed => 'Claimed',
            self::Resolved => 'Resolved',
            self::Cancelled => 'Cancelled',
        };
    }

    public function isOpenOrClaimed(): bool
    {
        return $this === self::Open || $this === self::Claimed;
    }
}
