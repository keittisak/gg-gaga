<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Variant extends Model
{
    protected $fillable = [
        "id", "product_id", "name", "name_en", "created_by", "updated_by", "created_at", "updated_at"
    ];

    public function options()
    {
        return $this->hasMany('App\Option');
    }

    public function product()
    {
        return $this->belongsTo('App\Product');
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
