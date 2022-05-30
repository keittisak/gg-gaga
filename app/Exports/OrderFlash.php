<?php

namespace App\Exports;

use App\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OrderFlash implements FromCollection, WithHeadings
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
            $postalCode = preg_replace('/[^A-Za-z0-9]/', '', $order->shipping_full_address);
            $postalCode = substr($postalCode,-5,5);
            $data = [
                'order_code' => strtoupper($order->code),
                'shipping_full_name' => $order->shipping_full_name,
                'shipping_full_address' => $order->shipping_full_address,
                'postal_code' => (string)$postalCode,
                'shipping_phone' => (string)$order->shipping_phone,
                'shipping_phone_2' => '',
                'cod_amount' => ($order->is_cod == 'y') ? $order->net_total_amount : '',
                'item_type' => '',
                'width_kg' => 1,
                'length' => '0.00',
                'width' => '0.00',
                'height' => '0.00',
                'freight_insurance' => '',
                'value_insurance' => '',
                'declared_value' => '',
                'speed_service' => '',
                'packaging_damage_insurance' => '',
                'remark1' => '',
                'remark2' => '',
                'remark3' => ''
            ];
            $results->push($data);
            $i++;
        }
        return $results;
    }

    public function headings(): array
    {
        return [
            'Customer_order_number',
            'Consignee_name',
            'Address',
            'Postal_code',
            'Phone_number',
            'Phone_number2',
            'COD',
            'Item_type',
            'Weight_kg',
            'Length',
            'Width',
            'Height',
            'Freight_insurance',
            'Value_insurance',
            'Declared_value',
            'Speed_service',
            'Packaging_damage_insurance',
            'Remark1',
            'Remark2',
            'Remark3'
        ];
    }
}
