<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Validation\ValidationException;
use Exception;
use App\Models\GigItem;
use App\Http\Resources\Transaction as TransactionResource;
use App\Models\User;

class WalletController extends BaseController
{
    protected $user;

    public function __construct(Request $request)
    {
        $this->user = auth('api')->user();
    }

    /**
     * 
     */
    public function getTransactions(Request $request)
    {
        // dd($this->user->balance);
        return $this->sendResponse(TransactionResource::collection($this->user->transactions), 'Your balance: $'.$this->user->balance
        );
    }

    /**
     * 
     */
    public function postWithdraw(Request $request)
    {
        try {
            $validated = $this->validate($request, [
                'amount' => ['required', 'integer'],
            ]);
        } catch (ValidationException $validationException) {
            return $this->sendError($validationException->getMessage(), $validationException->errors());  
        }

        try {
            $this->user->withdraw($validated['amount']); 
        } catch (Exception $exception) {
            return $this->sendError($exception->getMessage());
        }
        
        return $this->sendResponse([
            'balance' => $this->user->balance
        ], '$'.$validated['amount'].' is withdrawn successfully.');
    }

    /**
     * 
     */
    public function postDeposit(Request $request)
    {
        try {
            $validated = $this->validate($request, [
                'amount' => ['required', 'integer'],
            ]);
        } catch (ValidationException $validationException) {
            return $this->sendError($validationException->getMessage(), $validationException->errors());  
        }

        try {
            $this->user->deposit($validated['amount'], [
                'type' => 'Deposit',
                'description' => 'You charged $'. $validated['amount'] . '.'
            ]);
        } catch (Exception $exception) {
            return $this->sendError($exception->getMessage());
        }

        return $this->sendResponse([
            'balance' => $this->user->balance
        ], '$'.$validated['amount'].' is deposited successfully.');
    }

    /**
     * 
     */
    public function postBuy(Request $request)
    {
        try {
            $validated = $this->validate($request, [
                'gig_id' => ['required'],
                'amount' => ['required', 'integer'],
                'type' => ['required', 'in:TIP,TICKET']
            ]);
        } catch (ValidationException $validationException) {
            return $this->sendError($validationException->getMessage(), $validationException->errors());  
        }

        try {
            $gigItem = GigItem::query()
                        ->whereGuid($validated['gig_id'])
                        ->firstOrFail();

            // // set dynamic amount of product.
            // $this->user->amount = $validated['amount'];
            // $gigItem->getAmountProduct($this->user);

            // // set meta value of gigitem.
            // $gigItem->meta_type = $validated['type'];
            // $gigItem->getMetaProduct();

            $this->user->withdraw($validated['amount'], [
                'type' => $validated['type'],
                'description' => 'Sent $'. $validated['amount'] .' to gig #'. $gigItem->id .'.'
            ]);
            User::findOrFail($gigItem->user_id)->deposit($validated['amount'], [
                'type' => $validated['type'],
                'description' => 'Received $'.$validated['amount'] .' as '. $validated['type'] .' in gig #'. $gigItem->id .'.'
            ]);
        } catch (Exception $exception) {
            return $this->sendError($exception->getMessage());
        }

        return $this->sendResponse([
            'balance' => $this->user->balance
        ], '$'.$validated['amount'].' is processed successfully.');
    }
}
