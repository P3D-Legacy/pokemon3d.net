<?php

namespace App\Http\Resources\API\v1;

use App\Http\Resources\API\v1\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class GamejoltAccountBanResource extends JsonResource
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
                'gamejoltaccount' => new GamejoltAccountResource($this->gamejoltaccount),
                'reason' => new BanReasonResource($this->reason),
                'banned_by' => new UserResource($this->banned_by),
                'updated_at' => $this->updated_at,
                'expire_at' => $this->expire_at,
            ];
        }
        if ($request->user()->can('api.minimal')) {
            return [
                'gamejoltaccount' => new GamejoltAccountResource($this->gamejoltaccount),
                'reason' => new BanReasonResource($this->reason),
                'expire_at' => $this->expire_at,
            ];
        }
        return [
            'gamejoltaccount' => new GamejoltAccountResource($this->gamejoltaccount),
            'reason' => new BanReasonResource($this->reason),
        ];
    }
}
