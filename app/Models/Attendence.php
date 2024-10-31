<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendence extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'employee_id',
        'leaveType',
        'halfDayType',
        'reason',
        'status',
        'applied_on',
        'application_status',
        
    ];
    public function employee()
    {

        return $this->belongsTo(Employee::class);
    }
}
