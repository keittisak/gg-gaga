<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Option;
use App\Variant;
use App\Product;

class OptionController extends Controller
{
    public function store(Request $request, int $product_id, int $variant_id)
    {
        $request->merge(array('product_id' => $product_id));
        $request->merge(array('variant_id' => $variant_id));
        if (isset($request->user()->id)){
            $request->merge(array('created_by' => $request->user()->id));
            $request->merge(array('updated_by' => $request->user()->id));
        }
        $validate = [
            'product_id' => [
                'required',
                'integer',
                'exists:products,id'
            ],
            'variant_id' => [
                'required',
                'integer',
                'exists:variants,id'
            ],
            'name' => [
                'required',
                'max:255'
            ],
            'name_en' => [
                // 'required',
                'max:255'
            ],
            'created_by' => [
                'integer'
            ],
            'updated_by' => [
                'integer'
            ],
        ];
        $request->validate($validate);
        $data = array_only($request->all(), array_keys($validate));
        $option = DB::transaction(function () use($request,$product_id, $data) {
            $option = new option();
            $option->create($data);
            return $option;
        });
        return $option;
    }

    public function update(Request $request, int $product_id, int $variant_id, int $id)
    {
        $option = Option::findOrFail($id);
        $request->merge(array('product_id' => $product_id));
        $request->merge(array('variant_id' => $variant_id));
        if (isset($request->user()->id)){
            $request->merge(array('updated_by' => $request->user()->id));
        }
        $validate = [
            'product_id' => [
                'required',
                'integer',
                'exists:products,id'
            ],
            'variant_id' => [
                'required',
                'integer',
                'exists:variants,id'
            ],
            'name' => [
                'required',
                'max:255'
            ],
            'name_en' => [
                // 'required',
                'max:255'
            ],
            'created_by' => [
                'integer'
            ],
            'updated_by' => [
                'integer'
            ],
        ];
        $request->validate($validate);
        $data = array_only($request->all(), array_keys($validate));
        $option = DB::transaction(function () use($request,$product_id, $data, $option) {
            $option->update($data);
            return $option;
        });
        return $option;
    }

    public function destroy(int $product_id, int $variant_id, int $id)
    {
        $option = Product::findOrFail($product_id)
                         ->variants()->findOrFail($variant_id)
                         ->options()->findOrFail($id)
                         ->delete();
        return response('','204');
    }
}
