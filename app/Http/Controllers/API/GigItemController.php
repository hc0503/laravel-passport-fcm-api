<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Exception;
use App\Models\GigItem;
use App\Http\Resources\GigItem as GigItemResource;

class GigItemController extends BaseController
{
    public function __construct()
    {

    }

    /**
     * 
     */
    public function getGigItems(Request $request)
    {
        try {
            $gigItems = GigItem::all();
        } catch (Exception $exception) {
            return $this->sendError($exception->getMessage());
        }
        return $this->sendResponse(GigItemResource::collection($gigItems), 'The gigs are fetched successfully.');
    }
}
