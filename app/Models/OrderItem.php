<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    public function productInfo()
    {
    	return $this->hasOne('App\Models\Product','id','product_id');
    }

    public function shopInfo()
	{
		return $this->hasOne('App\Models\Shop','id','shop_id');
	}
    public function sellerInfo()
	{
		return $this->belongsTo('App\Models\Seller','seller_id','id');
	}

    public function stockInfo()
	{
		return $this->hasOne('App\Models\StockInfo','id','stock_id');
	}

	public function orderInfo()
    {
    	return $this->hasOne('App\Models\Order','id','order_id');
    }
	public function customerInfo()
    {
    	return $this->hasOne('App\Models\Customer','id');
    }
	public function addressInfo()
    {
    	return $this->hasOne('App\Models\CustomerAddress','id');
    }

	public function refundItem()
    {
    	return $this->hasOne('App\Models\CustomerRefundable','orderItem_id','id');
    }
	public function sellerOrderStatus()
    {
    	return $this->hasOne('App\Models\OrderStatus','item_id','id');
    }

	
	public function orderReviewInfo()
    {
        return $this->hasOne('App\Models\Review','order_item_id','id');
    }

	public function orderItemReviewInfo()
    {
        return $this->hasOne(Review::class);
    }

	public function orderItemStatus()
    {
    	return $this->hasOne('App\Models\OrderStatus','item_id','id');
    }
	
}
