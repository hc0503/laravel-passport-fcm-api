<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\FcmDeviceToken;
use App\Http\Controllers\API\BaseController as BaseController;
use Exception;
use Validator;
use Illuminate\Validation\ValidationException;
use App\Http\Resources\FcmDeviceToken as FcmDeviceTokenResource;

class NotificationController extends BaseController
{
    protected $SERVER_API_KEY;

    public function __construct()
    {
        $this->SERVER_API_KEY = config('services.firebase.server_api_key');
    }

    /**
     * Save the device token into user table.
     * 
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postSaveFcmDeviceToken(Request $request)
    {
        try {
            $validated = $this->validate($request, [
                'token' => ['required'],
                'type' => []
            ]);
        } catch (ValidationException $validationException) {
            return $this->sendError($validationException->getMessage(), $validationException->errors());  
        }

        try {
            $deviceToken = auth('api')->user()->fcmDeviceTokens()->updateOrCreate([
                'user_id' => auth('api')->user()->id,
                'token' => $validated['token']
            ]);
        } catch (Exception $exception) {
            return $this->sendError($exception->getMessage());
        }

        return $this->sendResponse(new FcmDeviceTokenResource($deviceToken), 'The device token is stored successfully.');
    }

    /**
     * Send firebase notification by device token.
     * 
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postSendFcmNotification(Request $request)
    {
        try {
            $validated = $this->validate($request, [
                'title' => ['required'],
                'body' => ['required']
            ]);
        } catch (ValidationException $validationException) {
            return $this->sendError($validationException->getMessage(), $validationException->errors());  
        }
        try {
            $firebaseTokens = auth('api')->user()->fcmDeviceTokens()->pluck('token')->all();
            $data = [
                "registration_ids" => $firebaseTokens,
                "notification" => $validated
            ];
            $dataString = json_encode($data);
            $headers = [
                'Authorization: key=' . $this->SERVER_API_KEY,
                'Content-Type: application/json',
            ];
        
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
                
            if (curl_exec($ch)) {
                return $this->sendResponse([], 'The notification is sent successfully.');
            } else {
                return $this->sendError(curl_error($ch));
            }
            curl_close($ch);
        } catch (Exception $exception) {
            return $this->sendError($exception->getMessage());
        }
    }
}
