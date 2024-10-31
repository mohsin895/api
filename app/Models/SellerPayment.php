<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SellerPayment extends Model
{
    public function sellerInfo()
    {
       return $this->belongsTo('App\Models\Seller','seller_id','id');
    }
}
