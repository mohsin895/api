<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartProductStockInfo extends Model
{
    use HasFactory;

    public function stockInfo()
    {
        return $this->hasOne(StockInfo::class, 'id', 'stock_info_id');
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
}
