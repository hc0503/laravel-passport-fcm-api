<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Auth as AuthResource;
use App\Models\User;
use Carbon\Carbon;

class Notification extends JsonResource
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
            'type' => $this->type,
            'notification_type' => $this->notification_type,
            'title' => $this->title,
            'body' => $this->body,
            'is_archive' => $this->is_archive,
            'created_at' => Carbon::parse($this->created_at)->toDateTimeString(),
            'updated_at' => Carbon::parse($this->updated_at)->toDateTimeString()
        ];
    }
}
