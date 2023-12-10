<?php

namespace App\Http\Resources\API\v1;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class GamejoltAccountBanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     */
    public function toArray(Request $request): array|JsonSerializable|Arrayable
    {
        $this->loadMissing(['banned_by', 'reason']); // Eager load relationships

        return parent::toArray($request);
    }
}
