<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerBrand extends Model
{
    use HasFactory;
    public function brands(){
        return $this->hasOne('App\Models\Brand','id','brand_id');
        
    }
    public function seller(){
        return $this->hasOne('App\Models\Seller','id','seller_id');
    }

    public function BrandDocuments(){
        return $this->hasMany('App\Models\BrandDocument','document_id','id');
    }
    public function BrandRejections(){
        return $this->hasMany('App\Models\BrandRejection','document_id','id');
    }
    public function SellerBrandRejections(){
        return $this->hasOne('App\Models\BrandRejection','document_id','id')->orderBy('id','desc');
    }
    public function category()
    {
    	return $this->hasOne('App\Models\Category','id','category_id');
    }
}
