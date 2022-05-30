<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Variant;
use App\Sku;
use DB;
use App\Libraries\Counter;
use DataTables;
use App\Libraries\Image;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [
            'title_en' => 'Products',
            'title_th' => 'สินค้า'
        ];
        return view('products.index',$data);
    }

    public function data(Request $request)
    {
        $products = Product::with(['skus'])->get();
        return DataTables::of($products)
            ->addColumn('price',function($product){
                return $product->skus[0]->price;
            })
            ->addColumn('action', function($product) {
                return '<a href="'.route('products.edit', $product->id).'" class="btn btn-secondary btn-sm mr-2"><i class="far fa-edit"></i></a>
                        <button type="button" class="btn btn-secondary btn-sm btnDelete" data-id="'.$product->id.'"><i class="far fa-trash-alt"></i></button>';
            })
            ->rawColumns(['checkbox','action'])
        ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [
            'action' => 'create',
            'title_en' => 'Add Product',
            'title_th' => 'เพิ่มสินค้า',
            'product' => new Product
        ];
        return view('products.form',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (isset($request->user()->id)){
            $request->merge(array('created_by' => $request->user()->id));
            $request->merge(array('updated_by' => $request->user()->id));
        }
        $request->merge(array('slug' => str_slug($request->slug, '-')));
        if(isset($request->type)){
            $request->merge(array('type' => 'variable'));
        }else{
            $request->merge(array('type' => 'simple'));
        }

        $request->merge(array('status' => 'active'));

        $validate = [
            'slug' => [
                // 'required',
                // 'unique:products,slug',
                // 'max:255'
            ],
            'name' => [
                'required',
                'max:255'
            ],
            'name_en' => [
                'nullable',
                'max:255'
            ],
            'description' => [
                'nullable'
            ],
            'description_en' => [
                'nullable'
            ],
            'short_description' => [
                'nullable'
            ],
            'short_description_en' => [
                'nullable'
            ],
            'brand_id' => [
                'nullable',
                'integer',
                'exists:brands,id'
            ],
            'image' => [
                'nullable'
            ],
            'gallery_id' => [
                'nullable',
                'integer'
            ],
            'type' => [
                'in:simple,variable'
            ],
            'variants.*' => [
                
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
        $product = DB::transaction(function() use($request, $data) {
            if ($request->hasFile('image')){
                $image = new Image();
                $file = $request->file('image');
                $path = 'images/products/'.date('Ymd');
                $imageUrl = $image->upload($file, $path);
                $data['image'] = $imageUrl;
            }
            $counter = new Counter;
            $product = new Product();
            $product = $product->create($data);
            if ($request->type == "variable"){
                foreach($request->skus as $key => $data){
                    if(isset($data['active'])){
                        $_request = new Request();
                        $data['product_id'] = $product->id;
                        $data['full_name'] = $product->name.' | '.$data['name'];
                        $_request->merge($data);
                        if (isset($request->user()->id)){
                            $_request->merge(['created_by' => $request->user()->id, 'updated_by' => $request->user()->id]);
                        }
                        (new SkuController)->store($_request);
                    }
                    
                }
                // if (isset($request->variants)){
                //     foreach($request->variants as $key => $data){
                //         $_request = new Request();
                //         $_request->merge($data);
                //         if (isset($request->user()->id)){
                //             $_request->merge(['created_by' => $request->user()->id, 'updated_by' => $request->user()->id]);
                //         }
                //         $variant = (new VariantController)->store($_request, $product->id);
                //     }

                //     $variants = $variant->with('options')->where('product_id', $product->id)->get();
                //     $set_option_ids = [];
                //     if($variants){
                //         for($i = 0; $i < count($variants[0]['options']); $i++){
                //             if(isset($variants[1])){
                //                 for($j = 0; $j < count($variants[1]['options']); $j++){
                //                     $set_option_ids[] = [
                //                         $variants[0]['options'][$i]['id'],
                //                         $variants[1]['options'][$j]['id'],
                //                     ];
                //                 }
                //             }else{
                //                 $set_option_ids[] = [
                //                     $variants[0]['options'][$i]['id']
                //                 ];
                //             }
                //         }
                //     }

                //     foreach($request->skus as $key => $data){
                //         $_request = new Request();
                //         $sku = $counter->generateCode('sku',0,3);
                //         $data['sku'] = $sku;
                //         $data['product_id'] = $product->id;
                //         if(isset($set_option_ids[$key])){
                //             $data['option_ids'] = $set_option_ids[$key];
                //         }
                //         $_request->merge($data);
                //         if (isset($request->user()->id)){
                //             $_request->merge(['created_by' => $request->user()->id, 'updated_by' => $request->user()->id]);
                //         }
                //         (new SkuController)->store($_request);
                //     }
                // }
            }else if($request->type == 'simple'){
                $_request = new Request();
                $data['product_id'] = $product->id;
                $data['price'] = $request->price;
                $data['full_price'] = $request->full_price;
                $data['cost'] = $request->cost;
                $data['call_unit'] = $request->call_unit;
                $data['full_name'] = $data['name'];
                $_request->merge($data);
                (new SkuController)->store($_request);
            }
            return $product;
        });
        return response($product, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::findOrFail($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::with(['skus'])->findOrFail($id);
        // $variants = $product->variants()->with('options')->get();
        $data = [
            'action' => 'update',
            'title_en' => 'Update Product',
            'title_th' => 'แก้ไขสินค้า',
            'product' => $product,
            // 'variants' => $variants
        ];
        return view('products.form',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // dd($request->all());
        $product = Product::findOrFail($id);
        if (isset($request->user()->id)){
            $request->merge(array('updated_by' => $request->user()->id));
        }
        $request->merge(array('slug' => str_slug($request->slug, '-')));
        if(isset($request->type)){
            $request->merge(array('type' => 'variable'));
        }else{
            $request->merge(array('type' => 'simple'));
        }
        $validate = [
            'slug' => [
                // 'required',
                // 'unique:products,slug',
                // 'max:255'
            ],
            'name' => [
                'required',
                'max:255'
            ],
            'name_en' => [
                'nullable',
                'max:255'
            ],
            'description' => [
                'nullable'
            ],
            'description_en' => [
                'nullable'
            ],
            'short_description' => [
                'nullable'
            ],
            'short_description_en' => [
                'nullable'
            ],
            'brand_id' => [
                'nullable',
                'integer',
                'exists:brands,id'
            ],
            'image' => [
                'nullable'
            ],
            'gallery_id' => [
                'nullable',
                'integer'
            ],
            'type' => [
                'in:simple,variable'
            ],
            'variants.*' => [
                
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
        $product = DB::transaction(function() use($request, $product, $data) {
            if ($request->hasFile('image')){
                $image = new Image();
                $file = $request->file('image');
                $path = 'images/products/'.date('Ymd');
                $imageUrl = $image->upload($file, $path);
                $data['image'] = $imageUrl;
            }
            $counter = new Counter;
            $result = $product->update($data);
            if ($request->type == "variable"){
                $productSkus = [];
                foreach($request->skus as $key => $data){
                    $_request = new Request();
                    $data['product_id'] = $product->id;
                    $data['status'] = 'active';
                    $data['full_name'] = $product->name.' | '.$data['name'];
                    if(!isset($data['active'])){ $data['status'] = 'inactive'; }
                    if(empty($data['id'])){
                        if (isset($request->user()->id)){
                            $_request->merge(['created_by' => $request->user()->id, 'updated_by' => $request->user()->id]);
                        }
                        $_request->merge($data);
                        $sku = (new SkuController)->store($_request);
                        $productSkuIds[] = $sku->id;
                    }else{
                        if (isset($request->user()->id)){
                            $_request->merge(['updated_by' => $request->user()->id]);
                        }
                        $_request->merge($data);
                        $sku = (new SkuController)->update($_request, $data['id']);
                        $productSkuIds[] = $sku->id;
                    }
                    
                }
                $skus = $product->skus()->whereNotIn('id', $productSkuIds)->get();
                foreach ($skus as $item){
                    $_request = new Request();
                    (new SkuController)->destroy($item->id);
                }

            }else if($request->type == 'simple'){
                $_request = new Request();
                $data['product_id'] = $product->id;
                $data['price'] = $request->price;
                $data['full_price'] = $request->full_price;
                $data['cost'] = $request->cost;
                $data['call_unit'] = $request->call_unit;
                $data['full_name'] = $data['name'];
                $_request->merge($data);
                (new SkuController)->update($_request, $request->sku_id);
                $skus = $product->skus()->whereNotIn('id', [$request->sku_id])->get();
                foreach ($skus as $item){
                    $_request = new Request();
                    (new SkuController)->destroy($item->id);
                }
            }
            return $result;
        });
        return response()->json($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        DB::transaction(function() use($product) {
            $skus = $product->skus()->get();
            foreach($skus as $item)
            {
                (new SkuController)->destroy($item->id);
            }
            $product->delete();
            
        });
        return response('','204');
    }

    public function changeStatus (Request $request, int $id)
    {
        $product = Product::findOrFail($id);
        if (isset($request->user()->id)){
            if (empty($request->updated_by)) { $request->merge(array('updated_by' => $request->user()->id)); }
        }
        $validate = [
            'status' => [
                'required',
                'in:active,inactive'
            ],
            'updated_by' => [
                'integer'
            ]
        ];
        $request->validate($validate);
        $data = array_only($request->all(), array_keys($validate));
        try{
            $product = DB::transaction(function() use($request, $product, $data) {
                $currentStatus = $product->status;
                $product->update($data);
                return $product;
            });
            return response()->json(["message" => "Product is updated success."], 200);
        } catch (\Exception $e) {   
            return response()->json(["message" => $e->getMessage()], 500);
        }
    }
}
