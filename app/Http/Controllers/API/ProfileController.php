<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;
use Validator;
use App\Models\Profile;
use App\Models\User;
use App\Http\Resources\Profile as ProfileResource;
use Exception;
use File;
use Socialite;

class ProfileController extends BaseController
{
    protected $profileType = 'PERFORMER';

    /**
     * Display the specified resource.
     * 
     * @param \Illuminate\Http\Request  $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function getDetail(Request $request, $guid)
    {
        try {
            $profile = Profile::query()->whereGuid($guid)->firstOrFail();
        } catch (Exception $exception) {
            return $this->sendError($exception->getMessage());
        }

        return $this->sendResponse(new ProfileResource($profile), 'Successfully fetch profile details.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postStore(Request $request)
    {
        try {
            $input = $request->all();
            if ($input['type'] === $this->profileType) {    // PERFORMER
                $validator = Validator::make($input, [
                    'type' => ['required'],
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
                    'type' => ['required'],
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
            $input['interested_in'] = json_encode($input['interested_in']);

            $user = auth('api')->user();
            $profile = $user->profile()->updateOrCreate([
                'user_id' => $user->id
            ], $input);
        } catch (Exception $exception) {
            return $this->sendError($exception->getMessage());
        }
   
        return $this->sendResponse(new ProfileResource($profile), 'Profile saved successfully.');
    }

    /**
     * Upload cover photo.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postCoverPhoto(Request $request)
    {
        try {
            $input = $request->all();
            $validator = Validator::make($input, [
                'cover_photo' => ['required', 'mimes:jpeg,jpg,png'],
            ]);
            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors());       
            }

            $user = auth('api')->user();
            $cover_photo = null;
            if ($request->hasFile('cover_photo')) {
                $cover_photo = $this->fileStore($request->file('cover_photo'), 'profile');
            }
            $oldCoverPhotoPath = public_path("storage/".$user->profile->cover_photo);
            if (File::exists($oldCoverPhotoPath) && $user->profile->cover_photo !== null) { // unlink or remove previous image from folder
                $this->fileDestroy($oldCoverPhotoPath);
            }
            $profile = $user->profile()->updateOrCreate([
                'user_id' => $user->id
            ], ['cover_photo' => $cover_photo]);
        } catch (Exception $exception) {
            return $this->sendError($exception->getMessage());
        }

        return $this->sendResponse(new ProfileResource($profile), 'The cover photo saved successfully.');
    }

    /**
     * Upload profile photo.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postProfilePhoto(Request $request)
    {
        try {
            $input = $request->all();
            $validator = Validator::make($input, [
                'profile_photo' => ['required', 'mimes:jpeg,jpg,png'],
            ]);
            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors());       
            }

            $user = auth('api')->user();
            $profile_photo = null;
            if ($request->hasFile('profile_photo')) {
                $profile_photo = $this->fileStore($request->file('profile_photo'), 'profile');
            }
            
            if ($user->profile) {
                $oldProfilePhotoPath = public_path("storage/".$user->profile->profile_photo);
                if (File::exists($oldProfilePhotoPath) && $user->profile->profile_photo !== null) { // unlink or remove previous image from folder
                    $this->fileDestroy($oldProfilePhotoPath);
                }
            }
            $profile = $user->profile()->updateOrCreate([
                'user_id' => $user->id
            ], ['profile_photo' => $profile_photo]);
        } catch (Exception $exception) {
            return $this->sendError($exception->getMessage());
        }

        return $this->sendResponse(new ProfileResource($profile), 'The cover photo saved successfully.');
    }

    public function socialGoogleRedirect()
    {
        return Socialite::driver('google')->redirect();
    }
}
