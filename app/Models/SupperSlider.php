<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupperSlider extends Model
{
   public function supperProduct(){
    return $this->hasOne('App\Models\RightBanner','id','supper_id');
    
   }
}
