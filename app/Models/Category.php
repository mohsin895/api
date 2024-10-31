<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Category extends Model
{
    public function normalCategoryProducts()
    {
    	return $this->hasMany('App\Models\Product','sub_subcategory_id ','id');
    }
    public function subCategoryProducts()
    {
        return $this->hasMany('App\Models\Product','subcategory_id','id');
    }
    public function categoryProducts()
    {
        return $this->hasMany('App\Models\Product','category_id','id');
    }
    public function categoryImages()
    {
        return $this->hasOne('App\Models\CategoryImage','category_id','id')
                        ->where('status','!=',0)->orderBy('id','DESC');
    }
    public function categoryImage()
    {
        return $this->hasOne('App\Models\CategoryImage','category_id','id');
    }
    public function parentInfo()
    {
        return $this->hasOne('App\Models\Category','id','parent_id');
    }
    public function megaInfo()
    {
    	return $this->hasOne('App\Models\Category','id','parent_id');
    }
    public function subInfo()
    {
    	return $this->hasOne('App\Models\Category','id','parent_id');
    }
    public function subCategory()
    {
        return $this->hasMany('App\Models\Category','parent_id','id');
    }

    public function subcatInfo()
    {
    	return $this->hasOne('App\Models\Category','id','sub_cat_id');
    }
    public function singleSubCategory()
    {
        return $this->hasOne('App\Models\Category','parent_id','id');
    }
    public function bannerCategory()
    {
    	return $this->hasOne('App\Models\Banner','id','parent_id');
    }

    public function products()
      {
    return $this->hasMany('App\Models\Product','category_id','id');
    }

    public function subCategoryCount()
    {
        return $this->hasMany('App\Models\Category','parent_id','id')->where('look_type',2);
    }

    public function parentInfoCount()
    {
        return $this->hasMany('App\Models\Category','catId','id')->where('look_type',3);
    }

    public function subSubCategoryCount()
    {
        return $this->hasMany('App\Models\Category','parent_id','id')->where('look_type',3);
    }

    public function normalCategoryProductsCount()
    {
        return $this->hasMany('App\Models\Product','sub_subcategory_id','id');
    }

    public function subCategoryProductsCount()
    {
        return $this->hasMany('App\Models\Product','subcategory_id','id');
    }
    public function categoryProductsCount()
    {
        return $this->hasMany('App\Models\Product','category_id','id');
    }
    
}