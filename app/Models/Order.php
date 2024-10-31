<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public function orderItems()
    {
        return $this->hasMany('App\Models\OrderItem','order_id','id');
    }

    public function OrderhopInfo()
    {
        return $this->hasMany('App\Models\OrderShop','order_id','id');
    }
    public function orderItemsDashboard()
    {
        return $this->hasMany('App\Models\OrderItem','order_id','id');
    }
    public function statusInfo()
    {
    	return $this->hasOne('App\Models\OrderStatus','id','status');
    }
    public function promoCode()
    {
    	return $this->hasOne('App\Models\VoucherDiscount','id','promo_id');
    }
    public function customerInfo()
    {
    	return $this->hasOne('App\Models\Customer','id','customer_id');
    }
    public function addressInfo()
    {
    	return $this->hasOne('App\Models\CustomerAddress','id','address_id');
    }
    public function customerAddress()
    {
        return $this->hasOne('App\Models\CustomerAddress','id','address_id');
    }
    public function paymentInfo()
    {
        return $this->hasOne('App\Models\OrderPayment','order_id','id');
    }

   

    // public function productInfo()
    // {
    //     return $this->hasOne('App\Models\Product','product_id','id');
    // }

    

  
}
