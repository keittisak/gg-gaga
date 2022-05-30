<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Variant;
use App\Product;

class VariantController extends Controller
{
    public function store(Request $request, int $product_id)
    {
        $request->merge(array('product_id' => $product_id));
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
            'name' => [
                'required',
                'max:255'
            ],
            'name_en' => [
                // 'required',
                'max:255'
            ],
            'options.*' => [
                
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
        $variant = DB::transaction(function () use($request,$product_id, $data) {
            $product = Product::findOrFail($product_id);
            $variant = new Variant();
            $variant = $variant->create($data);
            if (isset($request->options)){
                $optionIDs = [];
                foreach($request->options as $data)
                {
                    $data = [
                        'name' => $data['name'],
                    ];
                    $_request = new Request();
                    $_request->merge($data);
                    if (isset($request->user()->id)){
                        $_request->merge(['created_by' => $request->user()->id, 'updated_by' => $request->user()->id]);
                    }

                    if (empty($data['id'])){
                        $option = (new OptionController)->store($_request, $product_id, $variant->id);
                        $optionIds[] = $option->id;
                    }else{
                        $option = (new OptionController)->update($_request, $product_id, $variant->id, $data['id']);
                        $optionIds[] = $option->id;
                    }
                }

                $options = $variant->options()->whereNotIn('id', $optionIds)->get();
                foreach ($options as $option){
                    $_request = new Request();
                    (new OptionController)->destroy($_request, $product_id, $variant->id, $option->id);
                }
            }
            return $variant;
        });
        return $variant;
    }

    public function update (Request $request, int $product_id, int $id)
    {
        $variant = Variant::findOrFail($id);
        $request->merge(array('product_id' => $product_id));
        if (isset($request->user()->id)){
            $request->merge(array('updated_by' => $request->user()->id));
        }
        $validate = [
            'product_id' => [
                'required',
                'integer',
                'exists:products,id'
            ],
            'name' => [
                'required',
                'max:255'
            ],
            'name_en' => [
                // 'required',
                'max:255'
            ],
            'options.*' => [
                
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
        $variant = DB::transaction(function () use($request,$product_id, $data, $variant) {
            $product = Product::findOrFail($product_id);
            $result = $variant->update($data);
            if (isset($request->options)){
                $optionIDs = [];
                foreach($request->options as $data)
                {
                    $data = [
                        'name' => $data['name'],
                    ];
                    $_request = new Request();
                    $_request->merge($data);

                    if (empty($data['id'])){
                        if (isset($request->user()->id)){
                            $_request->merge(['created_by' => $request->user()->id, 'updated_by' => $request->user()->id]);
                        }
                        $option = (new OptionController)->store($_request, $product_id, $variant->id);
                        $optionIds[] = $option->id;
                    }else{
                        if (isset($request->user()->id)){
                            $_request->merge(['updated_by' => $request->user()->id]);
                        }
                        $option = (new OptionController)->update($_request, $product_id, $variant->id, $data['id']);
                        $optionIds[] = $option->id;
                    }
                }

                $options = $variant->options()->whereNotIn('id', $optionIds)->get();
                foreach ($options as $option){
                    $_request = new Request();
                    (new OptionController)->destroy($product_id, $variant->id, $option->id);
                }
            }
            return $result;
        });
        return $variant;
    }

    public function destroy(int $product_id, int $id)
    {
        $variant = Product::findOrFail($product_id)
                          ->variants()->findOrFail($id);

        DB::transaction(function() use($variant) {
            $variant->options()->delete();
            $variant->delete();
        });
        return response('','204');
    }
}
