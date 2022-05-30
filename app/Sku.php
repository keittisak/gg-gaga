<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sku extends Model
{
    use SoftDeletes;
    
    protected $dates = ['created_at', 'updated_at'];
    
    protected $fillable = [
        'id', 'name', 'name_en', 'full_name', 'shortname', 'product_id', 'barcode', 'image', 'call_unit', 'full_price', 'price', 'cost', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at' 
    ];

    public function options()
    {
        return $this->belongsToMany('App\Option', 'option_sku', 'sku', 'option_id')->withTimestamps();
    }

    public function product()
    {
        return $this->belongsTo('App\Product');
    }

    public function stock()
    {
        return $this->hasOne('App\Stock');
    }

    public function created_by_user()
    {
        return $this->belongsTo('App\User', 'created_by');
    }

    public function updated_by_user()
    {
        return $this->belongsTo('App\User', 'updated_by');
    }

    public function stockMovement()
    {
        return $this->hasOne('App\StockMovement');
    }
}
