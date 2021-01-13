<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class Auth extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'email_verified_at' => Carbon::parse($this->email_verified_at)->toDateTimeString(),
            'created_at' => Carbon::parse($this->created_at)->toDateTimeString(),
            'updated_at' => Carbon::parse($this->updated_at)->toDateTimeString(),
            $this->mergeWhen($this->tokenResult, function () {
                return [
                    'access_token' => $this->tokenResult->accessToken,
                    'token_type' => 'Bearer',
                    'expires_at' => Carbon::parse(
                        $this->tokenResult->token->expires_at
                    )->toDateTimeString()
                ];
            })
        ];
    }
}
