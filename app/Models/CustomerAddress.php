<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerAddress extends Model
{
    public function unionInfo()
    {
    	return $this->hasOne('App\Models\Union','id','union_id');
    }
    public function thanaInfo()
    {
    	return $this->hasOne('App\Models\Thana','id','thana_id');
    }
    public function districtInfo()
    {
    	return $this->hasOne('App\Models\District','id','district_id');
    }
}
