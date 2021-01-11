<?php

namespace App\Http\Controllers\API\Auth;
   
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Support\Facades\Password;
use Exception;
use App\Http\Resources\Auth as AuthResource;
   
class AuthController extends BaseController
{
    /**
     * Sign Up api
     *
     * @return \Illuminate\Http\Response
     */
    public function postSignup(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                // 'name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required',
                // 'c_password' => 'required|same:password',
            ]);
       
            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors());       
            }
       
            $input = $request->all();
            $input['password'] = bcrypt($input['password']);
            $input['name'] = $input['email'];
            // $user = User::query()->create($input)->sendEmailVerificationNotification();
            $user = User::query()->create($input);
            $success['email'] =  $input['email'];
        } catch (Exception $exception) {
            return $this->sendError($exception->getMessage());
        }
        
        return $this->sendResponse($success, 'User signup successfully.');
    }
   
    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function postLogin(Request $request)
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
            $user = Auth::user();

            // if ($user->email_verified_at === null)
            //     return $this->sendError('Email no verificated.');

            $user->tokenResult = $user->createToken($user->email);
            return $this->sendResponse(new AuthResource($user), 'User login successfully.');
        } 
        else{ 
            return $this->sendError('Invalid Credentials.', ['email' => __('auth.failed')]);
        } 
    }

    /**
     * Create token password reset
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function postForgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $status = Password::sendResetLink(
            $request->only('email')
        );
        
        return $status === Password::RESET_LINK_SENT
            ? $this->sendResponse([], __($status))
            : $this->sendError('Email sending error.', ['email' => __($status)]);
    }

    /**
     * Fetch auth data
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function getUser(Request $request)
    {
        $user = auth('api')->user();

        return $this->sendResponse(new AuthResource($user), 'User fetch successfully.');
    }

    /**
     * Logout
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function getLogout(Request $request)
    {
        $request->user()->token()->revoke();

        return $this->sendResponse([], 'Successfully logged out.');
    }
}