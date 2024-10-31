<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartShop extends Model
{
    use HasFactory;

    public function cartProductInfo()
    {
    	
        return $this->hasMany('App\Models\CartProduct','cart_shop_id','id');
    }
    public function cartStockInfo()
    {
    	
        return $this->hasMany('App\Models\CartProductStockInfo','cart_shop_id','id');
    }
    public function sellerInfo()
    {
        return $this->belongsTo(Seller::class, 'seller_id', 'id');
    }
}
