<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Stock;
use App\Sku;
use App\Product;
use DB;
use DataTables;


class StockController extends Controller
{

    public function index (Request $request)
    {
        return view('stocks.index');
    }

    public function data (Request $request)
    {
        $data = Stock::with(['sku'])
        ->when(isset($request['search']['value']),function($q) use ($request){
            $text = $request['search']['value'];
            $q->whereHas('sku',function($query) use ($text){
                $query->where('full_name', 'like', '%' . $text . '%');
            });
        })
        ->get();
        // dd($data->toArray());
        return DataTables::of($data->toArray())->make(true);
    }

    public function store(Request $request, int $sku_id)
    {
        $request->merge(array('sku_id' => $sku_id));
 
        $validate = [
            //stock table
            'sku_id' => [
                'required',
                'unique:stocks,sku_id',
                'exists:skus,id',
                'max:30'
            ],
            'available' => [
                'integer'
            ],
            'draft' => [
                'integer'
            ],
            'onhand' => [
                'integer'
            ]
        ];
        $request->validate($validate);
        $data = array_only($request->all(), array_keys($validate));
        $stock = new Stock();
        
        return $stock->create($data);
    }

    public function update(Request $request, string $id)
    {    
        $sku_id = $request->sku_id;
        $validate = [
            //stock table
            'sku_id' => [
                'required',
                function($attribute, $value, $fail) use($sku_id) {
                    $total = Stock::where('sku_id', '!=', $sku_id)
                                ->where('sku_id', $value)
                                ->count();
                    if ($total > 0){
                        return $fail($attribute.' is invalid.');
                    }
                },
                'exists:skus,id',
                'max:30'
            ],
            'action' => [
                'required',
                'in:set,add'
            ],
            'quantity' => [
                'integer',
                'min:0'
            ],
            'remark' => [
                
            ],
            'updated_by' => [
                'integer'
            ]
        ];
        $request->validate($validate);
        $data = array_only($request->all(), array_keys($validate));

        $stock = DB::transaction(function() use($request,$data,$id) {
            $stock = Stock::find($id);
            if($data['action'] == 'add'){
                $response = $stock->fillIn($data['quantity'], ['remark'=>$data['remark']]);
            }else{
                
                if($data['quantity'] > $stock->onhand){
                    $quantity = $data['quantity'] - $stock->onhand;
                    $response = $stock->fillIn($quantity, ['remark'=>$data['remark']]);
                }else{
                    $quantity = $stock->onhand - $data['quantity'];
                    $response = $stock->takeOut($quantity, ['remark'=>$data['remark']]);
                }
            }
            $stock->update($data);
            return $stock->first();
        });

        return $stock;
    }

}
