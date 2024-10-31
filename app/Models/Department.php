<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
   public function designationInfo(){
    return $this->hasMany(Designation::class);
   }
}
