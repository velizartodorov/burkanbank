<?php

namespace App\Http\Controllers;
use App\Customer;
use App\Account;
use DB;
use Validator;
use Illuminate\Support\Facades\Input;


 class OrderBankController extends Controller
 {

 	public function addNewPaymentGet()
 	{
		return view('addnewpayment');	
 	}

 	public function addNewPaymentPost()
 	{
 		$paymentData =Input::all();

 		$rules = [
            'IBAN_orig' => 'required|different:IBAN_benef',
            'IBAN_benef' => 'required',
            'amount' => 'required',
            'reason' => 'required',
            'date' => 'required|date',
    ];

    $customMessages = [
        'IBAN_orig.required' => 'IBAN на наредителя не може да бъде празен.',
        'IBAN_benef.required' => 'IBAN на бенефициента не може да бъде празен.',
        'amount.required' => 'Сумата не е въведена.',
        'reason.required' => 'Причината за плащане не е въведена.',
        'date.required' => 'Датата на създаване не е въведена.',
        'IBAN_orig.different' => 'Сметката на наредителя и бенефициента съвпдат.',
    ];


    $validator = Validator::make($paymentData, $rules, $customMessages);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        else{
                if(Account::ifIBANandAmountExists($paymentData))
                {

                    unset($paymentData['_token']);
                       $paymentData = BankRegisterController::redirectBank(json_encode($paymentData));
                        if($paymentData)
                        {
                            $paymentData = BankRegisterController::redirectToPayment(json_encode($paymentData));
                            if($paymentData)
                            {
                                return back()
                                ->with('success', 'Транзакцията беше извършена успешно.') 
                                ->withInput();
                            }
                            else{
                                return back()
                                ->with('iban-error', 'Неуспешно транзакция.') 
                                ->withInput();
                            }
                        }
                        else{
                            return back()
                            ->with('iban-error', 'Невалиден IBAN на бенефициента.') 
                            ->withInput();
                        }
                }
                else
                     return back()
                            ->with('iban-error', 'Недостатъчна сума или невалиден IBAN.') 
                            ->withInput();
        }

 	}
}