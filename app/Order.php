<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'id', 'code', 'customer_id', 'total_quantity', 'total_amount', 'discount_amount', 'shipping_fee', 'overpay', 'net_total_amount', 'shipping_full_name', 'shipping_address', 'shipping_full_address', 'shipping_subdistrict_id', 'shipping_phone', 'is_cod', 'status', 'sale_channel', 'tracking_code', 'payment_method_id', 'shipment_method_id', 'remark', 'link', 'created_by', 'updated_by', 'created_at', 'updated_at'
    ];

    public function details()
    {
        return $this->hasMany('App\OrderDetail');
    }

    public function customer()
    {
        return $this->belongsTo('App\Customer');
    }

    public function payments()
    {
        return $this->belongsToMany('App\Payment')->withTimestamps();
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
