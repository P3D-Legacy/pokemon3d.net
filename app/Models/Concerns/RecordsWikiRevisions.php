<?php

namespace App\Models\Concerns;

use App\Models\User;
use App\Models\WikiRevision;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait RecordsWikiRevisions
{
    public function revisions(): MorphMany
    {
        return $this->morphMany(WikiRevision::class, 'revisionable')->latest();
    }

    public function recordRevision(?User $user = null, ?string $summary = null): WikiRevision
    {
        return $this->revisions()->create([
            'user_id' => $user?->id,
            'title' => $this->wikiTitle(),
            'body' => $this->wikiBody(),
            'snapshot' => $this->revisionSnapshot(),
            'summary' => $summary,
        ]);
    }

    public function wikiTitle(): string
    {
        return (string) ($this->title ?? $this->name);
    }

    public function wikiBody(): ?string
    {
        return $this->body ?? $this->notes;
    }

    /**
     * @return array<string, mixed>
     */
    public function revisionSnapshot(): array
    {
        return $this->only($this->getFillable());
    }
}
