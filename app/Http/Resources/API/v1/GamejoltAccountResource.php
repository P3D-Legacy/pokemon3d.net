<?php

namespace App\Http\Resources\API\v1;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GamejoltAccountResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array|Arrayable|\JsonSerializable
     */
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }
}
