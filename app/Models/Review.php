<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    public function images()
    {
    	return $this->hasMany('App\Models\ReviewImage','review_id','id');
    }

    public function customerInfo()
    {
    	return $this->hasOne('App\Models\Customer','id','customer_id');
    }
    public function stockInfo()
	{
		return $this->hasOne('App\Models\StockInfo','id','stock_info_id');
	}

    public function productInfo()
    {
    	return $this->hasOne('App\Models\Product','id','product_id');
    }

    public function sellerInfo()
    {
    	return $this->hasOne('App\Models\Product','id','seller_id');
    }
    public function itemInfo()
    {
    	return $this->hasOne('App\Models\OrderItem','id','item_id');
    }
}
