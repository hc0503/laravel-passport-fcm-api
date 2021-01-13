<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($result = [], $message, $code = 200)
    {
    	$response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];

        return response()->json($response, $code);
    }

    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($error, $errorMessages = [], $code = 404)
    {
    	$response = [
            'success' => false,
            'data' => [],
            'message' => $error,
        ];

        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }

    /**
     * Upload file.
     * 
     * @param mixed $file
     * @param string $storePath
     * @return bool
     */
    public function fileStore($file, $storePath)
    {
        return $file->store($storePath, 'public');
    }

    /**
     * Destroy file.
     * 
     * @param string $filePath
     * @return bool
     */
    public function fileDestroy($filePath)
    {
        return unlink($filePath);
    }
}
