<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    use HasFactory;
    public function orderStockInfo()
    {
        return $this->hasMany('App\Models\OrderItem','order_product_id','id');
    }
    public function productInfo()
    {
    	return $this->hasOne('App\Models\Product','id','product_id');
    }
}
