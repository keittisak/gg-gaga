<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Print</title>
    <style>
        body {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
            background-color: #FAFAFA;
            /* font-family: 'THSarabunPSK', 'Tahoma'; */
            font-family:'Tahoma';
            font-size: {!!request()->get("fontSize")!!}px;
        }
        * {
            box-sizing: border-box;
            -moz-box-sizing: border-box;
        }
        .page {
            width: 60mm;
            height: 39mm;
            padding: 2mm 3mm;
            margin: 1mm auto;
            /* border: 1px #D3D3D3 solid; */
            border-radius: 5px;
            background: white;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        @page {
            size: 60mm 40mm;
            margin: 0;
        }

        @media print {
            html,
            body {
                width: 60mm;
                height: 100%;
                font-size: {!!request()->get("fontSize")!!}px;
            }
            .page {
                padding: 2mm 3mm;
                margin: 0;
                border: initial;
                border-radius: initial;
                width: initial;
                min-height: initial;
                box-shadow: initial;
                background: initial;
                page-break-after: always;
                overflow: hidden;
            }
        }
        p {
            margin:0;
            padding:0;
        }
        .label{
            width: 100mm; 
            height: 70mm; 
            padding: 1mm 1mm;
            margin-bottom:2mm;
            overflow: hidden;
            /* border: #000000 solid 1px;  */
        }
        .text-bold{
            font-weight: bold; 
        }
    </style>
</head>
<body>
    
        @foreach($orders as $order)
            <div class="page">
                <p>Order: <span class="">#{{strtoupper($order->code)}}</span></p>
                <p>ผู้ส่ง: <span class="">Gaga Brand</span></p></p>
                <p style="margin-bottom: 5px">บริษัทเมดวิทเลิฟ 422/95 ถนนปัญญาอินทรา แขวงสามวาตะวันตก เขตคลองสามวา กรุงเทพๆ 10510</p>
                <p>ผู้รับ: {{$order->shipping_full_name.' ('.$order->shipping_phone.')'}}</p>
                <p>{{$order->shipping_full_address}}</p>
                @if($order->is_cod == 'y')
                <p class="text-bold">เก็บเงินปลายทาง: {{number_format($order->net_total_amount,2,'.',',')}}</p>
                @endif
                <span style="margin-top:5px; display:block"></span>
                @foreach($details[$order->id]['products'] as $detail)
                <p>
                    @if($detail['product_type'] == 'variable')
                    <span>{{$detail['product_name']}} | </span>
                    @endif
                    @foreach($detail['skus'] as $sku)
                        <span>{{$sku['name'].' = '.$sku['quantity']}}, </span>
                    @endforeach
                </p>
                @endforeach
            </div>
        @endforeach

</body>
</html>