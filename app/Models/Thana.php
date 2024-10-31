<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Thana extends Model
{
    public function districtInfo()
    {
    	return $this->hasOne('App\Models\District','id','district_id');
    }
}
