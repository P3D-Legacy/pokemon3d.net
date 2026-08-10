<?php

namespace Database\Factories;

use App\Enums\GameSaveFixRequestStatus;
use App\Models\GameSaveFixRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<GameSaveFixRequest>
 */
class GameSaveFixRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'assignee_id' => null,
            'description' => $this->faker->paragraph(),
            'status' => GameSaveFixRequestStatus::Open,
            'consent_accepted_at' => now(),
            'consent_text' => config('game-save.fix_request_consent_text'),
            'resolved_at' => null,
            'notify_database' => true,
            'notify_mail' => true,
            'stale_notified_at' => null,
        ];
    }

    public function claimed(?User $assignee = null): static
    {
        return $this->state(fn (): array => [
            'status' => GameSaveFixRequestStatus::Claimed,
            'assignee_id' => $assignee?->id ?? User::factory(),
        ]);
    }

    public function resolved(?User $assignee = null): static
    {
        return $this->state(fn (): array => [
            'status' => GameSaveFixRequestStatus::Resolved,
            'assignee_id' => $assignee?->id ?? User::factory(),
            'resolved_at' => now(),
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(fn (): array => [
            'status' => GameSaveFixRequestStatus::Cancelled,
        ]);
    }
}
