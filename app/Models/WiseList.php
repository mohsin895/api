<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WiseList extends Model
{
    public function productInfo()
    {
    	return $this->hasOne('App\Models\Product','id','product_id');
    }

    public function stockInfo()
    {
    	return $this->hasOne('App\Models\StockInfo','id','stock_info_id');
    }
}
