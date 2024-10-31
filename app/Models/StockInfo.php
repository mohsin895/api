<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockInfo extends Model
{
    public function shopInfo()
	{
		return $this->hasOne('App\Models\Shop','id','shop_id');
	}
	public function productInfo()
	{
		return $this->hasOne('App\Models\Product','id','product_id');
	}
    public function colorInfo()
    {
    	return $this->hasOne('App\Models\Color','id','color_id');
    }
    public function sizeInfo()
    {
    	return $this->hasOne('App\Models\Size','id','size_id');
    }
	public function sizeVariantInfo()
    {
    	return $this->hasOne('App\Models\SizeAttribute','id','size_attribute_id');
    }

	public function orderItems(){
		return $this->hasMany('App\Models\OrderItem','stock_id','id')->where('item_status',7);
	}
}
