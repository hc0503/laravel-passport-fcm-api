<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Socialite;
use Exception;
use App\Http\Controllers\API\BaseController as BaseController;

class ProfileController extends BaseController
{
    public function googleRedirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function googleCallback()
    {
        try {
            $user = Socialite::driver('google')->user();
        } catch (Exception $exception) {
            return $this->sendError($exception->getMessage());
        }

        dd($user);
    }
}
