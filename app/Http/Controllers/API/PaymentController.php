<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\API\BaseController as BaseController;

class PaymentController extends BaseController
{
    protected $stripeSecret, $user;

    public function __construct(Request $request)
    {
        $this->stripeSecret = config('services.stripe.stripe_secret');
        $this->user = auth('api')->user();
    }
    /**
     * 
     */
    public function postCreatePaymentMethod(Request $request)
    {
        try {
            $validated = $this->validate($request, [
                'number' => ['required'],
                'exp_month' => ['required'],
                'exp_year' => ['required'],
                'cvc' => ['required']
            ]);
        } catch (ValidationException $validationException) {
            return $this->sendError($validationException->getMessage(), $validationException->errors());  
        }

        try {
            $stripe = new \Stripe\StripeClient(
                $this->stripeSecret
            );

            if ($this->user->stripe_id) {
                $customer = $stripe->customers->update($this->user->stripe_id, [
                    'email' => $this->user->email,
                    'name' => $this->user->name
                ]);
            } else {
                $customer = $stripe->customers->create([
                    'description' => 'Ugigs customer',
                    'email' => $this->user->email,
                    'name' => $this->user->name,
                ]);
                
                $paymentMethod = $stripe->paymentMethods->create([
                    'type' => 'card',
                    'card' => [
                        'number' => $validated['number'],
                        'exp_month' => $validated['exp_month'],
                        'exp_year' => $validated['exp_year'],
                        'cvc' => $validated['cvc']
                    ]
                ]);

                $stripe->paymentMethods->attach($paymentMethod->id, [
                    'customer' => $customer->id
                ]);

                $this->user->update([
                    'stripe_id' => $customer->id,
                    'card_last_four' => $paymentMethod->card->last4
                ]);
            }
            
        } catch (Exception $exception) {
            return $this->sendError($exception->getMessage());
        }

        return $this->sendResponse($customer, 'Created payment method successfully.');
    }

    /**
     * 
     */
    public function postTransfer(Request $request)
    {
        try {
            \Stripe\Stripe::setApiKey($this->stripeSecret);
            $stripe = new \Stripe\StripeClient(
                $this->stripeSecret
            );
            $account = $stripe->accounts->create([
                'type' => 'custom',
                'country' => 'US',
                'email' => 'jenny.rosen@example.com',
                'capabilities' => [
                    'card_payments' => ['requested' => true],
                    'transfers' => ['requested' => true],
                ],
            ]);
            
            return $this->sendResponse($account, 'successfully.');

            $transfer = \Stripe\Transfer::create([
                'amount' => 100,
                'currency' => 'usd',
                'destination' => $account->id,
                'transfer_group' => '{ORDER10}',
            ]);
        } catch (Exception $exception) {
            return $this->sendError($exception->getMessage());
        }
        
        return $this->sendResponse($transfer, 'successfully.');
    }
}
