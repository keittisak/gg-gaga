<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'id', 
        'full_name', 
        'address',
        'full_address', 
        'country_id', 
        'province_id', 
        'district_id',
        'subdistrict_id',
        'postalcode',
        'phone',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];

    public function orders()
    {
        return $this->hasMany('App\Order', 'customer_id');
    }

    public function created_by_user()
    {
        return $this->belongsTo('App\User', 'created_by');
    }

    public function updated_by_user()
    {
        return $this->belongsTo('App\User', 'updated_by');
    }
}
