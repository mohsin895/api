<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    public function divisionInfo()
    {
    	return $this->hasOne('App\Models\Division','id','division_id');
    }
}
