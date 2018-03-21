<?php

namespace App\Http\Controllers;
use App\Customer;
use App\Account;
use DB;
use Validator;
use Redirect;
use Illuminate\Support\Facades\Input;


 class BankRegisterController extends Controller
 {
    public static function redirectBank($paymentData)
    {

        $JSONdecodedPaymentData = json_decode($paymentData);
        $ibanExists = Account::ifIBANExists($JSONdecodedPaymentData->IBAN_benef);
        if($ibanExists)
        {
            return $JSONdecodedPaymentData;
        }
        else{ 
            return false;
        }
    }

    public static function redirectToPayment($paymentData)
    {

        $JSONdecodedPaymentData = json_decode($paymentData);
        $updateBeneficientAccount = Account::makeTransaction($JSONdecodedPaymentData);
        if($updateBeneficientAccount)
        {
            return $JSONdecodedPaymentData;
        }
        else{ 
            return false;
        }
    }
}