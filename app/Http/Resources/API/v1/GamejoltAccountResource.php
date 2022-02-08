<?php

namespace App\Http\Resources\API\v1;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\API\v1\GamejoltAccountBanResource;

class GamejoltAccountResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if ($request->user()->can('api.full')) {
            return parent::toArray($request);
        }
        if ($request->user()->can('api.moderate')) {
            return [
                'id' => $this->id,
                'username' => $this->username,
                'verified_at' => $this->verified_at,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
                'user' => new UserResource($this->user),
                'bans' => GamejoltAccountBanResource::collection(
                    $this->whenLoaded('bans')
                ),
            ];
        }
        if ($request->user()->can('api.minimal')) {
            return [
                'id' => $this->id,
                'username' => $this->username,
                'verified_at' => $this->verified_at,
            ];
        }
        return [
            'id' => $this->id,
            'username' => $this->username,
        ];
    }
}
