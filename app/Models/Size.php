<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    public function attributeInfo(){
        return $this->hasMany(SizeAttribute::class);
    }

    public function orderItemsQty(){
		return $this->hasMany('App\Models\OrderItem','size_id','id')->where('item_status',7);
	}
}
