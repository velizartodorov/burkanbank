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

    public static function getAllPayments()
    {
       return DB::table('payments')->select("OrdererIBAN as IBAN_orig", 
                                            "PayeeIBAN as IBAN_benef",
                                            "Amount as amount",
                                            "PaymentDate as date", 
                                            "PaymentReason as reason")->get(); 
    }

    public static function getAllPaymentsbyIBANOrUser($searchString)
    {
        $userFound = DB::table('customers')
                ->where('FirstName', 'like', '%' .  $searchString . '%')
                ->orWhere('LastName', 'like', '%' .  $searchString . '%')
                ->get();


        if(count($userFound))
        {
            $userIDs = [];
            
            foreach($userFound as $user)
            {
                $userIDs[] = $user->CustomerID;
            }

            $accountsFound = DB::table('accounts')
                ->whereIn('CustomerID', $userIDs)
                ->get();

            $IBANs = [];

            foreach($accountsFound as $acc)
            {
                $IBANs[] = $acc->IBAN;
            }
                $payment = DB::table('payments')
                ->whereIn('OrdererIBAN', $IBANs)
                ->orWhereIn('PayeeIBAN', $IBANs)
               ->select("OrdererIBAN as IBAN_orig", 
                    "PayeeIBAN as IBAN_benef",
                    "Amount as amount",
                    "PaymentDate as date", 
                    "PaymentReason as reason")
                ->get(); 
                return $payment;
        }
        else{
            $payment = DB::table('payments')
                ->where('OrdererIBAN', $searchString)
                ->orWhere('PayeeIBAN', $searchString)
               ->select("OrdererIBAN as IBAN_orig", 
                    "PayeeIBAN as IBAN_benef",
                    "Amount as amount",
                    "PaymentDate as date", 
                    "PaymentReason as reason")
                ->get();
                return $payment;
        }
    
    }

    public static function getIBANBenificientExists($searchString)
    {
        $accountsFound = DB::table('accounts')
                ->leftJoin('customers', 'accounts.CustomerID', '=', 'customers.CustomerID')
                ->where('IBAN', $searchString)
                ->select('IBAN as iban',
                         'FirstName as first_name',
                         'LastName as last_name',
                         'Email as email')
                ->get();
        return $accountsFound;
    }
}
