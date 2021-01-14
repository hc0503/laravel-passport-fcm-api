<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Auth as AuthResource;
use App\Models\User;
use Carbon\Carbon;

class GigItem extends JsonResource
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
            'user' => new AuthResource(User::query()->findOrFail($this->user_id)),
            'title' => $this->title,
            'description' => $this->description,
            'created_at' => Carbon::parse($this->created_at)->toDateTimeString(),
            'updated_at' => Carbon::parse($this->updated_at)->toDateTimeString()
        ];
    }
}
