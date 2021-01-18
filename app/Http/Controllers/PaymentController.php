<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Cashier\PaymentMethod;

class PaymentController extends Controller
{
    /**
     * 
     */
    public function getPayments(Request $request)
    {
        return view('payment');
    }

    /**
     * 
     */
    public function postCharge(Request $request)
    {

    }

    /**
     * 
     */
    public function getTest(Request $request)
    {
        $paymentMethod = $request->user()->findPaymentMethod('pm_1I9x37Ec7paZpVKqNwc9wIMK');
        $request->user()->updateDefaultPaymentMethod($paymentMethod);
        $paymentMethods = $request->user()->defaultPaymentMethod();
        dd($paymentMethods);
    }
}
