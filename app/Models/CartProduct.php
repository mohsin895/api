<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class CartProduct extends Model
{
    public function productInfo()
    {
    	return $this->hasOne('App\Models\Product','id','product_id');
    }
    public function cartInfo()
    {
    	return $this->hasMany('App\Models\CartProductStockInfo','cart_id','id');
    }
    public function stockInfo()
    {
        return $this->belongsTo(StockInfo::class, 'stock_info_id', 'id');
    }

    public function sellerInfo()
    {
        return $this->belongsTo(Seller::class, 'seller_id', 'id');
    }
    public function ruleInfoForAddToCart()
    {
    	return $this->hasOne('App\Models\CartRule','id','rule_id')
    					->where('status',1)	
    						->whereDate('start_at','<=',Carbon::today())
    							->whereDate('end_at','>=',Carbon::today());
    			
    }
    public function cartShop()
    {
    	return $this->belongsTo('App\Models\CartShop','id','cart_shop_id');
    }
    
}
