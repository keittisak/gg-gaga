<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $fillable = [
        'id', 'order_id', 'product_id', 'product_name', 'product_type', 'sku_id', 'name', 'full_name', 'call_unit', 'full_price', 'price', 'cost', 'type', 'quantity', 'discount_amount', 'total_amount', 'created_by', 'updated_by', 'created_at', 'updated_at'
    ];

    public function order()
    {
        return $this->belongsTo('App\Order');
    }

    public function sku()
    {
        return $this->belongsTo('App\Sku');
    }

    public function product()
    {
        return $this->belongsTo('App\Product');
    }
}
