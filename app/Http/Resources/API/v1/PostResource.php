<?php

namespace App\Http\Resources\API\v1;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array|JsonSerializable|Arrayable
    {
        return parent::toArray($request);
    }
}
