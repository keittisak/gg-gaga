<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'id', 'name', 'name_en', 'description', 'short_description', 'description_en', 'short_description_en', 'brand_id', 'image', 'gallery_id','type', 'status', 'tags', 'created_by', 'updated_by', 'created_at', 'updated_at'
    ];

    public function skus()
    {
        return $this->hasMany('App\Sku');
    }

    public function variants($id=null)
    {
        return $this->hasMany('App\Variant');
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
