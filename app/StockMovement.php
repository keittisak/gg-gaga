<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    protected $fillable = [
        'sku_id' ,'quantity', 'type', 'reference_code', 'remark', 'created_by', 'updated_by'
    ];

    public function sku()
    {
        return $this->belongsTo('App\Sku');
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
