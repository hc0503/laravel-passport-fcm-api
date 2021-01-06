<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;
use Validator;
use App\Models\Profile;
use App\Http\Resources\Profile as ProfileResource;

class ProfileController extends BaseController
{
    protected $profileType = 'PERFORMER';

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postStore(Request $request)
    {
        $input = $request->all();

        if ($input['type'] === $this->profileType) {    // PERFORMER
            $validator = Validator::make($input, [
                'user_id' => ['required'],
                'type' => ['required'],
                'cover_photo' => [],
                'profile_photo' => [],
                'stage_name' => ['required'],
                'about_you' => ['required'],
                'categories' => ['required', 'array'],
                'tags' => ['required', 'array'],
                'facebook' => [],
                'twitter' => [],
                'linkedin' => [],
                'instagram' => []
            ]);
        } else {                                        // AUDIENCE
            $validator = Validator::make($input, [
                'user_id' => ['required'],
                'type' => ['required'],
                'profile_photo' => [],
                'name' => ['required'],
                'interested_in' => ['required', 'array'],
                'organization_type' => ['required'],
                'facebook' => [],
                'twitter' => [],
                'linkedin' => [],
                'instagram' => []
            ]);
        }
        
   
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $input['categories'] = json_encode($input['categories']);
        $input['tags'] = json_encode($input['tags']);
        $profile = Profile::updateOrCreate([
            'user_id' => $input['user_id']
        ], $input);
   
        return $this->sendResponse(new ProfileResource($profile), 'Profile saved successfully.');
    }
}
