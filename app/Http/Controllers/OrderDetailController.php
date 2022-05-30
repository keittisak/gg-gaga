<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;
use App\OrderDetail;
use App\Product;
use App\Sku;
use App\Stock;
use DB;

class OrderDetailController extends Controller
{
    public function store (Request $request, int $order_id)
    {
        $validate = [
            // 'order_id' => [
            //     'required',
            //     'integer',
            //     'exists:orders,id'
            // ],
            'product_id' => [
                'nullable',
                // 'required',
                'integer',
                'exists:products,id'
            ],
            'product_name' => [
                'nullable'
            ],
            'product_type' => [
                'in:simple,variable'
            ],
            'sku_id' => [
                'required',
                'exists:skus,id',
                'max:30'
            ],
            'name' => [
                'nullable'
            ],
            'full_name' => [
                'nullable'
            ],
            'product_id' => [
                'required',
                'integer',
                'exists:products,id'
            ],
            'call_unit' => [
                'nullable',
                'max:255'
            ],
            'image' => [
                'nullable'
            ],
            // 'full_price' => [
            //     'numeric',
            //     'min:0'
            // ],
            'price' => [
                'numeric',
                'min:0'
            ],
            'cost' => [
                // 'numeric',
                // 'min:0'
            ],
            'quantity' => [
                'required',
                'integer',
                // function($attribute, $value, $fail) use($request) {
                //     if (!in_array($request->sku , (new Sku)->serviceSkus)){
                //         if ($value < 0){ 
                //             return $fail($attribute.' must be at least 0.');
                //         }
                //     }
                // }
            ],
            'discount_amount' => [
                'numeric',
                'min:0'
            ],
            'total_amount' => [
                'numeric',
                'min:0'
            ],
        ];

        $request->validate($validate);
        $data = array_only($request->all(), array_keys($validate));
        $detail = DB::transaction(function() use($request, $order_id, $data) {
            $order = Order::findOrFail($order_id);
            $data['order_id'] = $order_id;
            $detail = new OrderDetail();
            $detail = $detail->create($data);
            $detail->sku->stock->cutting($detail->quantity);
            return $detail;
        });
        return $request->route() ? response($detail, 201) : $detail;
    }

    public function update (Request $request, int $order_id, int $id)
    {
        $detail = OrderDetail::findOrFail($id);
        $validate = [
            // 'order_id' => [
            //     'required',
            //     'integer',
            //     'exists:orders,id'
            // ],
            'product_id' => [
                'nullable',
                // 'required',
                'integer',
                'exists:products,id'
            ],
            'product_name' => [
                'nullable'
            ],
            'product_type' => [
                'in:simple,variable'
            ],
            'sku_id' => [
                'required',
                'exists:skus,id',
                'max:30'
            ],
            'name' => [
                'nullable'
            ],
            'full_name' => [
                'nullable'
            ],
            'product_id' => [
                'required',
                'integer',
                'exists:products,id'
            ],
            'call_unit' => [
                'nullable',
                'max:255'
            ],
            'image' => [
                'nullable'
            ],
            // 'full_price' => [
            //     'numeric',
            //     'min:0'
            // ],
            'price' => [
                'numeric',
                'min:0'
            ],
            'cost' => [
                // 'numeric',
                // 'min:0'
            ],
            'type' => [
                'in:simple,variable,group'
            ],
            'quantity' => [
                'required',
                'integer',
                // function($attribute, $value, $fail) use($request) {
                //     if (!in_array($request->sku , (new Sku)->serviceSkus)){
                //         if ($value < 0){ 
                //             return $fail($attribute.' must be at least 0.');
                //         }
                //     }
                // }
            ],
            'discount_amount' => [
                'numeric',
                'min:0'
            ],
            'total_amount' => [
                'numeric',
                'min:0'
            ],
        ];

        $request->validate($validate);
        $data = array_only($request->all(), array_keys($validate));
        $detail = DB::transaction(function() use($request, $detail, $order_id, $data, $id) {
            $order = Order::findOrFail($order_id);
            $detail->sku->stock->restore($detail->quantity);
            $detail = $detail->update($data);
            $detail = OrderDetail::findOrFail($id);
            $detail->sku->stock->cutting($detail->quantity);
            return $detail;
        });
        return $detail;
    }

    public function destroy (Request $request, int $order_id, int $id)
    {
        $detail = Order::findOrFail($order_id)->details()->findOrFail($id);
        DB::transaction(function () use ($order_id, $detail) {
            $order = Order::findOrFail($order_id);
            $detail->sku->stock->restore($detail->quantity);
            $detail->delete();
        });
        return response('','204');
    }
}
