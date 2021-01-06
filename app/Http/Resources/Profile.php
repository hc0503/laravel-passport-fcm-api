<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

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
            'id' => $this->id,
            'user_id' => $this->user_id,
            'type' => $this->type,
            'cover_photo' => $this->cover_photo,
            'profile_photo' => $this->profile_photo,
            'stage_name' => $this->stage_name,
            'about_you' => $this->about_you,
            'categories' => json_decode($this->categories),
            'tags' => json_decode($this->tags),

            'name' => $this->name,
            'interested_in' => $this->interested_in,
            'organization_type' => $this->organization_type,
            'facebook' => $this->facebook,
            'twitter' => $this->twitter,
            'linkedin' => $this->linkedin,
            'created_at' => $this->created_at->format('d/m/Y'),
            'updated_at' => $this->updated_at->format('d/m/Y'),
        ];
    }
}
