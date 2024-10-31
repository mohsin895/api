<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerRefundable extends Model
{
    public function curiorType()
    {
    	return $this->hasOne('App\Models\Curior','id','curior_id');
    }

    public function financialAccountType()
    {
    	return $this->hasOne('App\Models\FinancialAccount','id','financial_account_id');
    }
    public function returnCauseType()
    {
    	return $this->hasOne('App\Models\ReturnPolicy','id','returnCauseId');
    }

    public function orderItem()
    {
    	return $this->hasOne('App\Models\OrderItem','id','orderItem_id');
    }

   
}
