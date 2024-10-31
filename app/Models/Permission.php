<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    public function roleInfo()
    {
        return $this->hasOne('App\Models\Role','id','roleId');
    }
}
