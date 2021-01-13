<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Profile as ProfileResource;
use App\Models\Profile;
use Carbon\Carbon;

class Social extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->guid,
            'profile' => new ProfileResource(Profile::findOrFail($this->profile_id)),
            'provider' => $this->provider,
            'social_id' => $this->social_id,
            'token' => $this->token,
            'refresh_token' => $this->refresh_token,
            'expires_in' => $this->expires_in,
            'nickname' => $this->nickname,
            'name' => $this->name,
            'email' => $this->email,
            'avatar' => $this->avatar,
            'created_at' => Carbon::parse($this->created_at)->toDateTimeString(),
            'updated_at' => Carbon::parse($this->updated_at)->toDateTimeString()
        ];
    }
}
