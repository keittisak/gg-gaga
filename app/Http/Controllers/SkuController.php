<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Option;
use App\Sku;
use App\Counter;
use DB;

class SkuController extends Controller
{
    public function store (Request $request)
    {
        if (isset($request->user()->id)){
            $request->merge(array('created_by' => $request->user()->id));
            $request->merge(array('updated_by' => $request->user()->id));
        }
        $validate = [
            // 'sku' => [
            //     'required',
            //     'unique:skus,sku',
            //     'max:30'
            // ],
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
            'barcode' => [
                'nullable',
                'digits:13'
            ],
            'image' => [
                'nullable'
            ],
            'full_price' => [
                // 'numeric',
                // 'min:0'
            ],
            'price' => [
                'numeric',
                'min:0'
            ],
            'cost' => [
                // 'numeric',
                // 'min:0'
            ],
            'option_ids.*' => [
                'exists:options,id'
            ],
            'status' => [
                'in:active,inactive'
            ],
            'created_by' => [
                'nullable',
                'integer'
            ],
            'updated_by' => [
                'nullable',
                'integer'
            ],
        ];
        $request->validate($validate);
        $data = array_only($request->all(), array_keys($validate));
        $sku = DB::transaction(function() use($request, $data) {
            $sku = new Sku();
            $sku = $sku->create($data);
            $_request = new Request();
            $data = [
                'available' => 0,
                'draft' => 0,
                'onhand' => 0
            ];
            $_request->merge($data);
            (new StockController)->store($_request, $sku->id);
            return $sku;
        });

        return $sku;
    }

    public function update (Request $request, string $id)
    {
        $sku = Sku::findOrFail($id);
        if (isset($request->user()->id)){
            $request->merge(array('created_by' => $request->user()->id));
            $request->merge(array('updated_by' => $request->user()->id));
        }
        $validate = [
            // 'sku' => [
            //     'required',
            //     'unique:skus,sku',
            //     'max:30'
            // ],
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
            'barcode' => [
                'nullable',
                'digits:13'
            ],
            'image' => [
                'nullable'
            ],
            'full_price' => [
                // 'numeric',
                // 'min:0'
            ],
            'price' => [
                'numeric',
                'min:0'
            ],
            'cost' => [
                // 'numeric',
                // 'min:0'
            ],
            'option_ids.*' => [
                'exists:options,id'
            ],
            'status' => [
                'in:active,inactive'
            ],
            'created_by' => [
                'nullable',
                'integer'
            ],
            'updated_by' => [
                'nullable',
                'integer'
            ],
        ];
        $request->validate($validate);
        $data = array_only($request->all(), array_keys($validate));
        $result = DB::transaction(function() use($request, $data, $sku) {
            $sku = $sku->update($data);
            // if (isset($request->option_ids)){
            //     $sku->options()->sync($request->option_ids);
            // }
            return $sku;
        });

        return $sku;
    }

    public function destroy(int $id)
    {
        $sku = Sku::findOrFail($id);
        DB::transaction(function() use($sku) {
            $sku->stock()->delete();
            $sku->delete();
        });
        
        return response('','204');
    }
}
