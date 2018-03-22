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

    public static function gettAllPayments()
    {
        $allPayments = Account::getAllPayments();

        if(!$allPayments->isEmpty())
        {
            self::prettyJSON($allPayments);
        }
        else{
            $message = array("result"=>"fail", "error_code"=>"500", "error_message"=>"No payments found.");
            print_r(json_encode($message));
        }
        
    }

    public static function getPaymentsSearch($search)
    {
        $allPayments = Account::getAllPaymentsbyIBANOrUser($search);


        if(!$allPayments->isEmpty())
        {
            self::prettyJSON($allPayments);
        }
        else{
            $message = array("result"=>"fail", "error_code"=>"500", "error_message"=>"IBAN or user not found.");
            print_r(json_encode($message));
        }
    }

    public static function getIBANBenificient($IBAN)
    {
        $allPayments = Account::getIBANBenificientExists($IBAN);


        if(!$allPayments->isEmpty())
        {
            print_r('valid: "true" ');
            self::prettyJSON($allPayments);
        }
        else{
            print_r('valid: "false" ');
        }
    }

    private static function prettyJSON($array)
    {
        foreach($array as $arr)
        {
            echo '<pre>';
            foreach($arr as $aK => $aV)
            {
            print_r(json_encode($aK, JSON_UNESCAPED_UNICODE) .":".json_encode($aV, JSON_UNESCAPED_UNICODE).'<br>');
            }
            echo '</pre>';
        }
    }
}