<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;
    protected $fillable = [
        'employee_id',
        'month',
        'year',
        'basic',
        'overtime_hours',
        'overtime_pay',
        'allowances',
        'deductions',
        'total_deduction',
        'total_additional',
        'total_allowance',
        'net_salary',
        'status'
       
    ];

    public function employeeInfo(){
        return $this->hasOne('App\Models\Employee','id','employee_id');
    }
}
