<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartRule extends Model
{
    public function cartProductInfos()
    {
    	return $this->hasMany('App\Models\CartProduct','rule_id','id');
    }
    public function cartProductInfosForAddToCart()
    {
    	return $this->hasMany('App\Models\CartProduct','rule_id','id')
    					->where('status',1);
    }
    public function restrictedRules()
    {
    	return $this->hasMany('App\Models\IgnoreCartRule','rule_id','id');
    }
}
