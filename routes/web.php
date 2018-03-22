<?php

use App\Http\Controllers\Controller;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
	return redirect()->route('add-new-payment-get');    
});

Route::get('/api/newpayment', [
    'as' => 'add-new-payment-get', 
    'uses' => 'OrderBankController@addNewPaymentGet']);


Route::post('/api/payment', [
    'as' => 'add-payment-post', 
    'uses' => 'OrderBankController@addNewPaymentPost']);

Route::get('/api/payments', [
    'as' => 'get-all-payments', 
    'uses' => 'BankRegisterController@gettAllPayments']);


Route::get('/api/payments/{search}', ['uses' =>'BankRegisterController@getPaymentsSearch']);

Route::get('/api/accounts/{IBAN}', ['uses' =>'BankRegisterController@getIBANBenificient']);