<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    public function sellerInfo()
    {
        return $this->belongsTo('App\Models\Seller','seller_id','id');
    }
    public function shopUnionInfo()
    {
    	return $this->hasOne('App\Models\Union','id','shopUnionId');
    }
    public function shopThanaInfo()
    {
    	return $this->hasOne('App\Models\Thana','id','shopUpazalaId');
    }
    public function shopDistrictInfo()
    {
    	return $this->hasOne('App\Models\District','id','shopDistrictId');
    }

    public function warehouseUnionInfo()
    {
    	return $this->hasOne('App\Models\Union','id','warehouseUnionId');
    }
    public function warehouseThanaInfo()
    {
    	return $this->hasOne('App\Models\Thana','id','warehouseUpazalaId');
    }
    public function warehouseDistrictInfo()
    {
    	return $this->hasOne('App\Models\District','id','warehouseDistrictId');
    }
    public function returnUnionInfo()
    {
    	return $this->hasOne('App\Models\Union','id','returnUnionId');
    }
    public function reeturnThanaInfo()
    {
    	return $this->hasOne('App\Models\Thana','id','returnUpazalaId');
    }
    public function returnDistrictInfo()
    {
    	return $this->hasOne('App\Models\District','id','returnDistrictId');
    }

    public function followingInfo()
    {
    	return $this->hasOne('App\Models\Following','shop_id','id');
    }
}
