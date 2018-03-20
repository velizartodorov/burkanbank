<?php

namespace App\Http\Controllers;
use App\Customer;
use DB;
use Illuminate\Support\Facades\Input;


 class OrderBankController extends Controller
 {

 	public function addNewPaymentGet()
 	{
		return view('addnewpayment');	
 	}

 	public function addNewPaymentPost()
 	{
 		// if(Input::has('ordererIBAN'))
			// $ordererIBAN = Input::get('ordererIBAN') ;
			'<pre>'.print_r(Customer::validateCustomer()).'</pre>';	
 		// $customers = DB::select("SELECT * FROM customers");
 	}
}