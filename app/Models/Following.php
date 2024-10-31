<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Following extends Model
{
    use HasFactory;
    public function shopInfo()
    {
    	return $this->hasOne('App\Models\Shop','id','shop_id');
    }
}
