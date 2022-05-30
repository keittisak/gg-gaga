<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;
use App\OrderDetail;
use DB;
use DataTables;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function dailySales (Request $request)
    {
        $data = [
            'title_eng' => 'Daily Sales Report',
            'title_th' => 'รายงานการขาย',
            'statusInfo' => [
                'draft' => ['title' => 'ร่าง', 'icon' => 'fas fa-inbox', 'text_color' => 'text-blue'],
                'unpaid' => ['title' => 'ยังไม่จ่าย', 'icon' => 'far fa-times-circle', 'text_color' => 'text-red'],
                'transfered' => ['title' => 'โอนแล้ว', 'icon' => 'far fa-check-circle', 'text_color' => 'text-green'],
                'packing' => ['title' => 'กำลังแพ็ค', 'icon' => 'fas fa-tape', 'text_color' => 'text-info'],
                'paid' => ['title' => 'เตรียมส่ง', 'icon' => 'fas fa-archive', 'text_color' => 'text-muted'],
                'shipped' => ['title' => 'ส่งแล้ว', 'icon' => 'fas fa-shipping-fast', 'text_color' => 'text-success'],
                'voided' => ['title' => 'ยกเลิก', 'icon' => 'far fa-trash-alt', 'text_color' => 'text-red'],
            ]
        ];
        return view('reports.daily_sales',$data);
    }

    public function dailySalesData (Request $request)
    {
        $result = Order::select([
            DB::raw('COUNT(*) as order_quantity'),
            DB::raw('SUM(total_amount) as total_amount'),
            DB::raw('SUM(discount_amount) as discount_amount'),
            DB::raw('SUM(shipping_fee) as shipping_fee'),
            DB::raw('SUM(overpay) as overpay'),
            DB::raw('SUM(net_total_amount) as net_total_amount'),
            'created_at'
        ])
        ->when( empty($request->status),function($q) use ($request){
            $q->where('status', '<>', 'voided');
        })
        ->when(!empty($request->status), function($q) use ($request){
            $q->where('status', $request->status);
        })
        ->when( !empty($request->start_date) && !empty($request->end_date), function($q) use ($request){
            $start_date = Carbon::createFromFormat('d/m/Y',$request->start_date)->format('Y-m-d');
            $end_date = Carbon::createFromFormat('d/m/Y',$request->end_date)->format('Y-m-d');
            return $q->whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date]);
        })
        ->when(!empty($request->is_cod), function($q) use ($request){
            $q->where('is_cod', $request->is_cod);
        })
        ->groupBy(DB::raw('DATE(created_at)'))
        ->orderBy('created_at', 'asc')
        ->get();
        return DataTables::of($result)->make(true);
    }

    public function salesByProduct (Request $request)
    {
        $data = [
            'title_eng' => 'Daily Sales Report',
            'title_th' => 'รายงานการขายตามสินค้า',
            'statusInfo' => [
                'draft' => ['title' => 'ร่าง', 'icon' => 'fas fa-inbox', 'text_color' => 'text-blue'],
                'unpaid' => ['title' => 'ยังไม่จ่าย', 'icon' => 'far fa-times-circle', 'text_color' => 'text-red'],
                'transfered' => ['title' => 'โอนแล้ว', 'icon' => 'far fa-check-circle', 'text_color' => 'text-green'],
                'packing' => ['title' => 'กำลังแพ็ค', 'icon' => 'fas fa-tape', 'text_color' => 'text-info'],
                'paid' => ['title' => 'เตรียมส่ง', 'icon' => 'fas fa-archive', 'text_color' => 'text-muted'],
                'shipped' => ['title' => 'ส่งแล้ว', 'icon' => 'fas fa-shipping-fast', 'text_color' => 'text-success'],
                'voided' => ['title' => 'ยกเลิก', 'icon' => 'fas fa-shipping-fast', 'text_color' => 'text-red'],
            ]
        ];
        return view('reports.sales_by_product',$data);
    }

    public function salesByProductData (Request $request)
    {
        $orders = Order::select([
            'id'
        ])
        ->when( empty($request->status),function($q) use ($request){
            $q->where('status', '<>', 'voided');
        })
        ->when(!empty($request->status), function($q) use ($request){
            $q->where('status', $request->status);
        })
        ->when( !empty($request->start_date) && !empty($request->end_date), function($q) use ($request){
            $start_date = Carbon::createFromFormat('d/m/Y',$request->start_date)->format('Y-m-d');
            $end_date = Carbon::createFromFormat('d/m/Y',$request->end_date)->format('Y-m-d');
            return $q->whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date]);
        })
        ->orderBy('created_at', 'asc')
        ->get();
        $orderIds = $orders->toArray();
        $result = OrderDetail::select([
            'sku_id',
            'full_name',
            'price',
            DB::raw('SUM(quantity) as quantity'),
            DB::raw('SUM(discount_amount) as discount_amount'),
            DB::raw('SUM(total_amount) as total_amount')
        ])
        ->whereIn('order_id', $orderIds)
        ->groupBy('sku_id')
        ->orderBy('total_amount', 'desc')
        ->get();
        return DataTables::of($result)->make(true);
    }
}
