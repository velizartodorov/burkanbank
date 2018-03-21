<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class Account extends Model
{
    public static function ifIBANExists($paymentData)
    {
        $ibanExists = Account::where('IBAN', $paymentData)
                              ->exists();
        if($ibanExists){
            return true;
        }
        else{
            return false;
        }
    }

    public static function ifIBANandAmountExists($paymentData)
    {
    	$ibanExists = Account::where('IBAN', $paymentData['IBAN_orig'])
    						  ->where('Ballance', '>=',  $paymentData['amount'])
    						  ->exists();
    	
    	if($ibanExists){
    		return true;
    	}
    	else{
    		return false;
    	}
    }

    public static function makeTransaction($paymentData)
    {
        $AccoundBenneficient = self::getAccountbyIBAN($paymentData->IBAN_benef);

        if($AccoundBenneficient)
        {
            $transactionBeneficient = DB::table('accounts')
                ->where('IBAN', $paymentData->IBAN_benef)
                ->update(['Ballance' => $AccoundBenneficient->Ballance + $paymentData->amount]);

                if($transactionBeneficient)
                {
                    $AccoundOrderer = self::getAccountbyIBAN($paymentData->IBAN_orig);

                    $transactionOrderer = DB::table('accounts')
                                    ->where('IBAN', $paymentData->IBAN_orig)
                                    ->update(['Ballance' => $AccoundOrderer->Ballance - $paymentData->amount]);

                    if($transactionOrderer)
                    {
                        $paymentRecord = self::addPayment($paymentData);
                       
                        if($paymentRecord)
                        {
                            return true;
                        }
                        else{
                            return false;
                        }
                    }

                }
                else{
                    return false;
                }
        }
        else{
            return false;
        }
    }

    private static function addPayment($paymentData)
    {
     $insertedSuccesfully = DB::table('payments')->insert([
            ['OrdererIBAN' => $paymentData->IBAN_orig,
             'PayeeIBAN' => $paymentData->IBAN_benef,
             'Amount' => $paymentData->amount,
             'PaymentDate' => date('Y-m-d H:i:s', strtotime($paymentData->date)),
             'PaymentReason' =>  $paymentData->reason]
        ]);
     if($insertedSuccesfully)
     {
        return true;
     }
     else{
        return false;
     }
    }

    private static function getAccountbyIBAN($IBAN)
    {
       return DB::table('accounts')->where('IBAN', $IBAN)->first(); 
    }
}
