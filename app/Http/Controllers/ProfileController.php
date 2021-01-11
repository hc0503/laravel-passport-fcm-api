<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Socialite;
use Exception;
use App\Models\Social;
use App\Http\Controllers\API\BaseController as BaseController;

class ProfileController extends BaseController
{
    public function getSocialRedirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function getSocialCallback($provider)
    {
        try {
            $userData = Socialite::driver($provider)->user();

            

        } catch (Exception $exception) {
            return $this->sendError($exception->getMessage());
        }
    }
}
