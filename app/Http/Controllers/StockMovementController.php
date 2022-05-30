<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sku;
use App\StockMovement;
use DB;
use DataTables;
use Carbon\Carbon;

class StockMovementController extends Controller
{
    public function index (Request $request)
    {
        $data = [
            'title_eng' => 'Stock Movement',
            'title_th' => 'การเคลื่อนไหวสินค้า',
        ];
        return view('stocks.movement',$data);
    }

    public function data (Request $request)
    {
        $result = StockMovement::select([
            'sku_id',
            'type',
            DB::raw('SUM(quantity) as quantity'),
            'skus.full_name as full_name'
        ])
        ->leftJoin('skus','sku_id','=','skus.id')
        ->when( !empty($request->start_date) && !empty($request->end_date), function($q) use ($request){
            $start_date = Carbon::createFromFormat('d/m/Y',$request->start_date)->format('Y-m-d');
            $end_date = Carbon::createFromFormat('d/m/Y',$request->end_date)->format('Y-m-d');

            return $q->whereBetween(DB::raw('DATE(stock_movements.created_at)'), [$start_date, $end_date]);
        })
        ->groupBy(['sku_id','type'])
        ->get();

        $skus = Sku::with('stock')->get();
        $stockMovement = array();
        foreach($skus as $item){
            $stockMovement[$item->id] = [
                'sku_id' => $item->id,
                'full_name' => $item->full_name,
                'fill_in' => 0,
                'take_out' => 0,
                'release' => 0,
                'get_back' => 0,
            ];
        }

 
        foreach($result as $item)
        {
            $stockMovement[$item->sku_id][$item->type] += $item->quantity;
        }
        return DataTables::of($stockMovement)->make(true);
    }
}
