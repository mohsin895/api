<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Employee extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function attendance()
    {
        return $this->hasMany(Attendence::class);
    }

    public function getDesignation()
    {
        return $this->belongsTo('App\Models\Designation', 'designations', 'id');
    }

    public function documents()
    {
        return $this->hasMany('App\Models\EmployeeDocument');
    }

    public function salaries()
    {
        return $this->hasMany('App\Models\Salary');
    }

    public function awards()
    {
        return $this->hasMany('App\Models\Award');
    }

    public function bankDetails()
    {
        return $this->hasOne('App\Models\EmployeeBankDetails');
    }

    public function company_details()
    {
        return $this->hasOne('App\Models\EmployeeCompanyDetails');
    }
    
    

    // get attendances
   
}
