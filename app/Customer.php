<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class Customer extends Model
{
    public static function validateCustomer()
    {
    	return DB::table('customers')->get();
    }
}
