<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveApplication extends Model
{
    use HasFactory;
    public function leaveType(){
        return $this->hasOne('App\Models\LeaveType','id','leaveType');
    }

    public function employee(){
        return $this->hasOne('App\Models\Employee','id','employee_id');
    }
}
