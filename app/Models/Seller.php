<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;


class Seller extends Authenticatable
{
     use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
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

    public function shopInfo()
    {
        return $this->hasOne('App\Models\Shop','seller_id','id');
    }
    public function productInfo()
    {
        return $this->hasMany('App\Models\Product','seller_id','id');
    }
    public function reviewInfo()
    {
        return $this->hasMany('App\Models\Review','seller_id','id')->orderBy('id','desc');
    }
}