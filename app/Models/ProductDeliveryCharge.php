<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductDeliveryCharge extends Model
{
    use HasFactory;
    public function normalCategory()
    {
    	return $this->hasOne('App\Models\Category','id','sub_sub_cat_id');
    }
    public function subCategory()
    {
    	return $this->hasOne('App\Models\Category','id','subCat_id');
    }
    // public function subCategoryInfo()
    // {
    // 	return $this->hasOne('App\Models\Category','id','subcategory_id ');
    // }
    public function megaCategory()
    {
    	return $this->hasOne('App\Models\Category','id','cat_id');
    }
}
