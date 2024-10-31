<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Union extends Model
{
    public function thanaInfo()
    {
    	return $this->hasOne('App\Models\Thana','id','thana_id');
    }
}
