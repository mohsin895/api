<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderShop extends Model
{
    use HasFactory;
    public function orderProduct()
    {
        return $this->hasMany('App\Models\OrderProduct','order_shop_id','id');
    }

    public function shopInfo()
    {
        return $this->hasMany('App\Models\OrderProduct','order_shop_id','id');
    }

    public function sellerInfo()
    {
        return $this->belongsTo(Seller::class, 'seller_id', 'id');
    }
    public function orderInfo()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

 
}
