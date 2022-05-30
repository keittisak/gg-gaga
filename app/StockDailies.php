<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StockDailies extends Model
{
    protected $fillable = [
        'sku_id' ,'quantity', 'date'
    ];
}
