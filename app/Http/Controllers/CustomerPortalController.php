<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;

class CustomerPortalController extends Controller
{
    public function index (Request $request, string $string)
    {
        $id = base64_decode($string);
        $order = Order::with(['details'])->find($id);
        if(!$order){
            return abort(404);
        }
        $data = [
            'statusInfo' => [
                'draft' => ['title' => 'ร่าง', 'icon' => 'fas fa-inbox', 'text_color' => 'text-blue'],
                'unpaid' => ['title' => 'ยังไม่จ่าย', 'icon' => 'far fa-times-circle', 'text_color' => 'text-red'],
                'transfered' => ['title' => 'โอนแล้ว', 'icon' => 'far fa-check-circle', 'text_color' => 'text-green'],
                'packing' => ['title' => 'กำลังแพ็ค', 'icon' => 'fas fa-tape', 'text_color' => 'text-info'],
                'paid' => ['title' => 'เตรียมส่ง', 'icon' => 'fas fa-archive', 'text_color' => 'text-muted'],
                'shipped' => ['title' => 'ส่งแล้ว', 'icon' => 'fas fa-shipping-fast', 'text_color' => 'text-success'],
                'voided' => ['title' => 'ยกเลิก', 'icon' => 'far fa-trash-alt', 'text_color' => 'text-red'],
            ],
            'order' => $order
        ];
        return view('customer_portal', $data);
    }
}
