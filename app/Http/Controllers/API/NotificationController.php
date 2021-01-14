<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\FcmDeviceToken;
use App\Models\Notification;
use App\Http\Controllers\API\BaseController as BaseController;
use Exception;
use Validator;
use Illuminate\Validation\ValidationException;
use App\Http\Resources\FcmDeviceToken as FcmDeviceTokenResource;
use App\Http\Resources\Notification as NotificationResource;

class NotificationController extends BaseController
{
    protected $firebaseApiKey, $notificationImage, $user;

    public function __construct(Request $request)
    {
        $this->firebaseApiKey = config('services.firebase.server_api_key');
        $this->notificationImage = 'https://firebase.google.com/images/social.png';
        $this->user = auth('api')->user();
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
            $deviceToken = $this->user->fcmDeviceTokens()->updateOrCreate([
                'user_id' => $this->user->id,
                'token' => $validated['token']
            ], $validated);
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
                'body' => ['required'],
                'type' => ['required', 'in:PERFORMANCE,AUDIENCE'],
                'notification_type' => ['required', 'in:FOLLOW,CLAP,GIG,INVITE,MIC']
            ]);
        } catch (ValidationException $validationException) {
            return $this->sendError($validationException->getMessage(), $validationException->errors());  
        }
        
        try {
            $firebaseTokens = $this->user->fcmDeviceTokens()->pluck('token')->all();
            $data = [
                "registration_ids" => $firebaseTokens,
                "notification" => [
                    'title' => $validated['title'],
                    'body' => $validated['body'],
                    'image' => $this->notificationImage
                ]
            ];
            $dataString = json_encode($data);
            $headers = [
                'Authorization: key=' . $this->firebaseApiKey,
                'Content-Type: application/json',
            ];
        
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
                
            if (!curl_exec($ch)) {
                return $this->sendError(curl_error($ch));
            }
            curl_close($ch);

            // Store notification into database.
            $notification = $this->user->notifications()->create($validated);
        } catch (Exception $exception) {
            return $this->sendError($exception->getMessage());
        }
        
        return $this->sendResponse(new NotificationResource($notification), 'The notification is sent successfully.');
    }

    /**
     * Get audience notification list which is not archived by type.
     * The type = ['PERFORMANCE', 'AUDIENCE']
     * 
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNotifications(Request $request)
    {
        try {
            $validated = $this->validate($request, [
                'type' => ['required', 'in:PERFORMANCE,AUDIENCE'],
            ]);
        } catch (ValidationException $validationException) {
            return $this->sendError($validationException->getMessage(), $validationException->errors());  
        }

        try {
            $notifications = $this->user->notifications
                            ->where('type', $validated['type'])
                            ->where('is_archive', 0);
        } catch (Exception $exception) {
            return $this->sendError($exception->getMessage());
        }

        return $this->sendResponse(NotificationResource::collection($notifications), 'The notifications are gotten successfully.');
    }

    /**
     * Archive the specified notification.
     * 
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postArchive(Request $request)
    {
        try {
            $validated = $this->validate($request, [
                'notification_id' => ['required'],
            ]);
        } catch (ValidationException $validationException) {
            return $this->sendError($validationException->getMessage(), $validationException->errors());  
        }

        try {
            $notification = Notification::query()
                            ->whereGuid($validated['notification_id'])
                            ->firstOrFail();
            $notification->is_archive = 1;
            $notification->save();
        } catch (Exception $exception) {
            return $this->sendError($exception->getMessage());
        }
        
        return $this->sendResponse(new NotificationResource($notification), 'The notification is archived successfully.');
    }
}
