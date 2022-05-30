<?php

namespace App\Exports;

use App\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OrdersExport implements FromCollection, WithHeadings
{
    public function __construct($orderIds)
    {
        $this->orderIds = $orderIds;
    }

    public function collection()
    {
        $results = collect([]);
        $orders = Order::with(['details'])->whereIn('id',$this->orderIds)->orderBy('id', 'ASC')->get();
        $i = 1;
        foreach( $orders as $order ){
            $data = [
                'no' => $i,
                'order_code' => strtoupper($order->code),
                'shipping_full_name' => $order->shipping_full_name,
                'shipping_full_address' => $order->shipping_full_address,
                'shipping_phone' => (string)$order->shipping_phone,
                'cod_amount' => (string)number_format(($order->is_cod == 'y') ? $order->net_total_amount : 0, 2, '.',','),
                'product_name' => '#####',
                'quantity' => '#####'
            ];
            $results->push($data);
            foreach( $order->details as $detail){
                $data = [
                    'no' => '',
                    'order_code' => '',
                    'shipping_full_name' => '',
                    'shipping_full_address' => '',
                    'shipping_phone' => '',
                    'cod_amount' => '',
                    'product_name' => $detail->full_name,
                    'quantity' => $detail->quantity
                ];
                $results->push($data);
            }
            $i++;
        }
        return $results;
    }

    public function headings(): array
    {
        return [
            'No',
            'เลขที่สั่งซื้อ',
            'ชื่อผู้รับ',
            'ที่อยู่ผู้รับ',
            'เบอร์โทรศัพท์ผู้รับ',
            'ยอดเก็บเงินปลายทาง',
            'ชื่อสินค้า',
            'จำนวน'
        ];
    }
}
