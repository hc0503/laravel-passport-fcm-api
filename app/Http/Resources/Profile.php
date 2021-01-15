<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Auth as AuthResource;
use App\Models\User;
use Carbon\Carbon;

class Profile extends JsonResource
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
            'user' => new AuthResource(User::findOrFail($this->user_id)),
            'type' => $this->type,
            'cover_photo' => $this->cover_photo ? asset("storage/".$this->cover_photo) : null,
            'profile_photo' => $this->profile_photo ? asset("storage/".$this->profile_photo) : null,
            'stage_name' => $this->stage_name,
            'about_you' => $this->about_you,
            'categories' => json_decode($this->categories),
            'tags' => json_decode($this->tags),

            'name' => $this->name,
            'interested_in' => json_decode($this->interested_in),
            'organization_type' => $this->organization_type,
            'streams' => '312',
            'followers' => '1,105',
            'claps' => '2.3K',
            'created_at' => Carbon::parse($this->created_at)->toDateTimeString(),
            'updated_at' => Carbon::parse($this->updated_at)->toDateTimeString()
        ];
    }
}
