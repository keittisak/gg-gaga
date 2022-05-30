<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{    
    protected $fillable = [
        'sku_id' ,'available', 'draft', 'onhand'
    ];

    public function sku()
    {
        return $this->belongsTo('App\Sku');
    }

    public function cutting($quantity, $option=array()){
        //cut stock for available
        if ($quantity < 0){ throw new \Exception('Require quantity must be at least 0.'); }
        if ($quantity > $this->available){ throw new \Exception('Stock is not enough.'); }

        $data = array(
            'available' => $this->available - $quantity,
            'draft' => $this->draft + $quantity
        );
        $this->update($data);
        return array('success' => true);
    }

    public function restore($quantity, $option=array()){
        // restock for available
        if ($quantity < 0){ throw new \Exception('Require quantity must be at least 0.'); }

        $data = array(
            'available' => $this->available + $quantity,
            'draft' => $this->draft - $quantity
        );
        $this->update($data);
        return array('success' => true);
    }

    public function release ($quantity, $option=array()){
        // cut stock for onhand
        if ($quantity < 0){ throw new \Exception('Require quantity must be at least 0.'); }
        if ($quantity > $this->onhand){ throw new \Exception('Stock is not enough.'); }
        
        $data = array(
            'onhand' => $this->onhand - $quantity,
            'draft' => $this->draft - $quantity
        );
        $this->update($data);
        StockMovement::create(['sku_id' => $this->sku()->first()->id, 'quantity' => $quantity, 'type' => 'release', 'remark' => !empty($option['remark'])?$option['remark']:null, 'reference_code' => !empty($option['reference_code'])?$option['reference_code']:null]);
        return array('success' => true);
    }

    public function getBack ($quantity, $option=array()){
        // restock for onhand
        if ($quantity < 0){ throw new \Exception('Require quantity must be at least 0.'); }

        $data = array(
            'onhand' => $this->onhand + $quantity,
            'draft' => $this->draft + $quantity
        );
        $this->update($data);
        StockMovement::create(['sku_id' => $this->sku()->first()->id, 'quantity' => $quantity, 'type' => 'get_back', 'remark' => !empty($option['remark'])?$option['remark']:null, 'reference_code' => !empty($option['reference_code'])?$option['reference_code']:null]);
        return array('success' => true);
    }

    public function fillIn ($quantity, $option=array()){
        // transfer in
        if ($quantity < 0){ throw new \Exception('Require quantity must be at least 0.'); }

        $data = array(
            'onhand' => $this->onhand + $quantity,
            'available' => $this->available + $quantity
        );
        $this->update($data);
        StockMovement::create(['sku_id' => $this->sku()->first()->id, 'quantity' => $quantity, 'type' => 'fill_in', 'remark' => !empty($option['remark'])?$option['remark']:null, 'reference_code' => !empty($option['reference_code'])?$option['reference_code']:null, 'created_by' => !empty($option['created_by'])?$option['created_by']:null, 'updated_by' => !empty($option['updated_by'])?$option['updated_by']:null]);
        return array('success' => true);
    }

    public function takeOut ($quantity, $option=array()){
        // transfer out
        if ($quantity < 0){ throw new \Exception('Require quantity must be at least 0.'); }
        if ($quantity > $this->onhand){ throw new \Exception('Stock is not enough.'); }

        $data = array(
            'onhand' => $this->onhand - $quantity,
            'available' => $this->available - $quantity
        );
        $this->update($data);
        StockMovement::create(['sku_id' => $this->sku()->first()->id, 'quantity' => $quantity, 'type' => 'take_out', 'remark' => !empty($option['remark'])?$option['remark']:null, 'reference_code' => !empty($option['reference_code'])?$option['reference_code']:null, 'created_by' => !empty($option['created_by'])?$option['created_by']:null, 'updated_by' => !empty($option['updated_by'])?$option['updated_by']:null]);
        return array('success' => true);
    }
}
