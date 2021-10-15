<?php

namespace App\Http\Resources\API\v1;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
                'name' => $this->name,
                'email' => $this->email,
                'username' => $this->username,
                'email_verified_at' => $this->email_verified_at,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
                'profile_photo_url' => $this->profile_photo_url,
                'roles' => $this->roles,
            ];
        }
        if ($request->user()->can('api.minimal')) {
            return [
                'id' => $this->id,
                'name' => $this->name,
                'email' => $this->email,
                'created_at' => $this->created_at,
            ];
        }
        return [
            'id' => $this->id,
            'name' => $this->name
        ];
    }
}
