<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    protected $fillable = [
        "id", "variant_id", "name", "name_en", "created_by", "updated_by", "created_at", "updated_at"
    ];

    public function skus()
    {
        return $this->belongsToMany('App\Sku', 'option_sku', 'option_id', 'sku')->withTimestamps();
    }

    public function variant()
    {
        return $this->belongsTo('App\Variant');
    }
}
