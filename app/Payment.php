<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class payment extends Model
{
    protected $dates = ['transfered_at'];

    protected $fillable = [
        "id", 
        "amount", 
        "method_id",
        "bank_id",
        "statement_id",
        "channel", 
        "transaction_code", 
        "transfered_at", 
        "image", 
        "status", 
        "created_by", 
        "updated_by", 
        "created_at", 
        "updated_at"
    ];

    public function order()
    {
        return $this->belongsTo('App\Order');
    }

    public function payment_method()
    {
        return $this->belongsTo('App\PaymentMethod', 'method_id');
    }

    public function bank()
    {
        return $this->belongsTo('App\Bank', 'bank_id');
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
