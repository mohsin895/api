<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    public function categoryInfo()
    {
    	return $this->hasOne('App\Models\Category','id','category_id');
    }
}
