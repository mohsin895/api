<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function stockInfo()
    {
        return $this->hasMany('App\Models\StockInfo','product_id','id');
    }

    public function stockSingleInfo()
    {
        return $this->hasOne('App\Models\StockInfo','product_id','id');
    }

    public function districtInfo()
    {
        return $this->hasMany('App\Models\District','id','districtId');
    }
    
    public function productImages()
    {
    	return $this->hasMany('App\Models\ProductImage','product_id','id');
    }
    public function unitInfo()
    {
    	return $this->hasOne('App\Models\Unit','id','unit_id');
    }
    public function normalCategory()
    {
    	return $this->hasOne('App\Models\Category','id','sub_subcategory_id');
    }
    public function subCategory()
    {
    	return $this->hasOne('App\Models\Category','id','subcategory_id');
    }
    // public function subCategoryInfo()
    // {
    // 	return $this->hasOne('App\Models\Category','id','subcategory_id ');
    // }
    public function megaCategory()
    {
    	return $this->hasOne('App\Models\Category','id','category_id');
    }
    public function brandInfo()
    {
    	return $this->hasOne('App\Models\Brand','id','brand_id');
    }
    public function shopInfo()
    {
    	return $this->hasOne('App\Models\Shop','id','shop_id');
    }

    public function deliveryCharge()
    {
    	return $this->hasOne('App\Models\DeliveryCharge','product_id','id');
    }

    public function recentView()
    {
    	return $this->hasOne('App\Models\ProductView','product_id','id')->orderBy('id','desc');
    }

    public function rejectedInfo(){
        return $this->hasMany('App\Models\ProductRejection','product_id','id');
    }
    public function suspendedInfo(){
        return $this->hasMany('App\Models\ProductSuspended','product_id','id');
    }
    public function reviewInfo(){
        return $this->hasMany('App\Models\Review','product_id','id');
    }
    public function category()
       {
    return $this->belongsTo(Category::class);
     }
    public function orderItems(){
    return $this->hasMany('App\Models\OrderItem','product_id','id')->where('item_status',7);
    }
     public function stockItems(){
    return $this->hasMany('App\Models\StockInfo','product_id','id');
        }
}
