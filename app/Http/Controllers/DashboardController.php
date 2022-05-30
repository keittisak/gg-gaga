<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Order;
use App\OrderDetail;
use App\Product;
use App\Sku;
use App\Customer;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index (Request $request)
    {
        // dd((200/5)*2); หาส่วนลด
        $monthShortTh = ["ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค." , "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค."];
        $latestDate = Carbon::now()->format('d').' '.$monthShortTh[(Carbon::now()->format('m')-1)].' '.Carbon::now()->format('Y');
        $latestTime = Carbon::now()->format('H:i');
        $_request = new Request;
        $_request->merge([
            'start_date' => Carbon::now()->format('Y-m-d'),
            'end_date' => Carbon::now()->format('Y-m-d')
        ]);
        $salesByProduct = $this->SalesByProduct($_request);
        $overviewTotal = $this->overviewTotal($_request);
        $saleChannel = $this->saleByChannel($_request);
        $overviewTotalCod = $this->overviewTotalCod($_request);

        if(in_array($request->user()->id, [1,4])){
            $orderByStatusTotal = $this->orderByStatusTotal(new Request);
        }else{
            $orderByStatusTotal = $this->orderByStatusTotal($_request);
        }
        
        
        $data = [
            'title_eng' => 'Dashboard',
            'title_th' => 'Dashboard',
            'latestDate' => $latestDate,
            'latestTime' => $latestTime,
            'overviewTotal' => $overviewTotal,
            'overviewTotalCod' => $overviewTotalCod,
            'salesByProduct' => $salesByProduct,
            'orderByStatusTotal' => $orderByStatusTotal,
            'saleChannel' => $saleChannel
        ];
        return view('dashboard',$data);
    }

    public function overviewTotal (Request $request)
    {
        $dates = ['today','yesterday', 'this_month', 'last_mouth'];
        $response = [];
        foreach($dates as $date){
            $result = Order::select([
                DB::raw('COUNT(*) as total_order'),
                DB::raw('SUM(net_total_amount) as net_total_amount'),
            ])
            ->when($date, function($q) use ($date){
                if($date == 'today'){
                    $q->whereDate('created_at', Carbon::now()->format('Y-m-d'));
                }else if($date == 'yesterday'){
                    $q->whereDate('created_at', Carbon::now()->subDays(1)->format('Y-m-d'));
                }else if($date == 'this_month'){
                    $q->whereMonth('created_at',Carbon::now()->format('m'))->whereYear('created_at',Carbon::now()->format('Y'));
                }else if($date == 'last_mouth'){
                    $q->whereMonth('created_at', Carbon::now()->subMonth()->format('m'))->whereYear('created_at',Carbon::now()->subMonth()->format('Y'));
                }
                return $q;
            })
            ->where('status', '!=', 'voided')
            ->first();
            $shortTotalAmount = 0;
            if($result->net_total_amount){
                $shortTotalAmount = number_format($result->net_total_amount/1000,2,'.','').'K';
                if($result->net_total_amount >= 1000000){
                    $shortTotalAmount = number_format($result->net_total_amount/1000000,2,'.','').'M';
                }
            }
            $response[$date] = [
                'total_order' => $result->total_order,
                'total_amount' => ($result->net_total_amount)?$result->net_total_amount:0,
                'short_total_amount' => $shortTotalAmount
            ];
        }
        return $response;
    }

    public function SalesByProduct (Request $request)
    {
        $orderIds = Order::select('id')
                ->when( !empty($request->start_date) && !empty($request->end_date), function($q) use ($request){
                    return $q->whereBetween(DB::raw('DATE(created_at)'), [$request->start_date, $request->end_date]);
                })
                ->get();
        $result = OrderDetail::select([
            'sku_id',
            'full_name',
            DB::raw('SUM(quantity) as quantity'),
            DB::raw('SUM(total_amount) as total_amount')
        ])
        ->whereIn('order_id', $orderIds)
        ->whereHas('order', function($q) use ($request){
            $q->where('status', '!=', 'voided');
        })
        ->groupBy('sku_id')
        ->orderBy('total_amount', 'desc')
        ->get();
        return $result;
    }

    public function orderByStatusTotal (Request $request)
    {
        $orders = [
            'draft' => ['title' => 'ร่าง', 'icon' => 'fas fa-inbox', 'text_color' => 'text-blue', 'quantity' => 0, 'net_total_amount' => 0],
            'unpaid' => ['title' => 'ยังไม่จ่าย', 'icon' => 'far fa-times-circle', 'text_color' => 'text-red', 'quantity' => 0, 'net_total_amount' => 0],
            'transfered' => ['title' => 'โอนแล้ว', 'icon' => 'far fa-check-circle', 'text_color' => 'text-green', 'quantity' => 0, 'net_total_amount' => 0],
            'packing' => ['title' => 'กำลังแพ็ค', 'icon' => 'fas fa-tape', 'text_color' => 'text-info', 'quantity' => 0, 'net_total_amount' => 0],
            'paid' => ['title' => 'เตรียมส่ง', 'icon' => 'fas fa-archive', 'text_color' => 'text-muted', 'quantity' => 0, 'net_total_amount' => 0],
            'shipped' => ['title' => 'ส่งแล้ว', 'icon' => 'fas fa-shipping-fast', 'text_color' => 'text-success', 'quantity' => 0, 'net_total_amount' => 0],
            'voided' => ['title' => 'ยกเลิก', 'icon' => 'far fa-trash-alt', 'text_color' => 'text-danger', 'quantity' => 0, 'net_total_amount' => 0],
            'total' => ['title' => 'จำนวนทั้งหมด', 'quantity' => 0, 'net_total_amount' => 0],
        ];
        $result = Order::select([
                        'status',
                        DB::raw('SUM(net_total_amount) as net_total_amount'),
                        DB::raw('COUNT(*) as quantity'),
                    ])
                    ->when( !empty($request->start_date) && !empty($request->end_date), function($q) use ($request){
                        return $q->whereBetween(DB::raw('DATE(created_at)'), [$request->start_date, $request->end_date]);
                    })
                    ->groupBy('status')
                    ->get();
        foreach($result as $item)
        {
            $orders[$item->status]['quantity'] += $item->quantity;
            $orders[$item->status]['net_total_amount'] += $item->net_total_amount;
            $orders['total']['quantity'] += $item->quantity;
            $orders['total']['net_total_amount'] += $item->net_total_amount;
        }
        return $orders;
    }

    public function saleByChannel (Request $request)
    {
        $saleChannel = [
            'line' => ['icon' => 'fab fa-line', 'text_color' => 'text-green', 'quantity' => 0, 'net_total_amount' =>0, 'per' => 0],
            'facebook' => ['icon' => 'fab fa-facebook-square', 'text_color' => 'text-blue', 'quantity' => 0, 'net_total_amount' =>0, 'per' => 0],
            'instagram' => ['icon' => 'fab fa-instagram-square', 'text_color' => 'text-muten', 'quantity' => 0, 'net_total_amount' =>0, 'per' => 0],
            'other' => ['icon' => 'fas fa-ellipsis-h', 'text_color' => 'text-muten', 'quantity' => 0, 'net_total_amount' =>0, 'per' => 0],
        ];
        $result = Order::select([
            'sale_channel',
            DB::raw('COUNT(*) as quantity'),
            // DB::raw('SUM(net_total_amount) as net_total_amount'),
        ])
        ->when( !empty($request->start_date) && !empty($request->end_date), function($q) use ($request){
            return $q->whereBetween(DB::raw('DATE(created_at)'), [$request->start_date, $request->end_date]);
        })
        ->groupBy('sale_channel')
        ->get();
        $total = 0;
        $totalPer =0;
        
        foreach($result as $item)
        {
            $total += $item->quantity;
            $saleChannel[$item->sale_channel]['quantity'] += $item->quantity;
            $saleChannel[$item->sale_channel]['net_total_amount'] += $item->quantity;
        }
        if(count($result)){
            foreach($saleChannel as $key => $item)
            {
                $pre = ($item['quantity']/$total)*100;
                $saleChannel[$key]['per'] = round($pre,2);
            }
        }
        return $saleChannel;
    }

    public function overviewTotalCod (Request $request)
    {
        $dates = ['today','yesterday', 'this_month', 'last_mouth'];
        $response = [];
        foreach($dates as $date){
            $result = Order::select([
                DB::raw('COUNT(*) as total_order'),
                DB::raw('SUM(net_total_amount) as net_total_amount'),
            ])
            ->when($date, function($q) use ($date){
                if($date == 'today'){
                    $q->whereDate('created_at', Carbon::now()->format('Y-m-d'));
                }else if($date == 'yesterday'){
                    $q->whereDate('created_at', Carbon::now()->subDays(1)->format('Y-m-d'));
                }else if($date == 'this_month'){
                    $q->whereMonth('created_at',Carbon::now()->format('m'))->whereYear('created_at',Carbon::now()->format('Y'));
                }else if($date == 'last_mouth'){
                    $q->whereMonth('created_at', Carbon::now()->subMonth()->format('m'))->whereYear('created_at',Carbon::now()->subMonth()->format('Y'));
                }
                return $q;
            })
            ->where('is_cod', 'y')
            ->where('status', '!=', 'voided')
            ->first();
            $shortTotalAmount = 0;
            if($result->net_total_amount){
                $shortTotalAmount = number_format($result->net_total_amount/1000,2,'.','').'K';
                if($result->net_total_amount >= 1000000){
                    $shortTotalAmount = number_format($result->net_total_amount/1000000,2,'.','').'M';
                }
            }
            $response[$date] = [
                'total_order' => $result->total_order,
                'total_amount' => ($result->net_total_amount)?$result->net_total_amount:0,
                'short_total_amount' => $shortTotalAmount
            ];
        }
        return $response;
    }


}
