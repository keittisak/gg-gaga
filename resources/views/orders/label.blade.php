<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Label</title>
    <style>
        @font-face {
            font-family: 'THSarabunPSK';
             src: url('/assets/fonts/th_sarabun/THSarabunPSK.eot') format('embedded-opentype'),
                 url('/assets/fonts/th_sarabun/THSarabunPSK.svg') format('svg'),
                 url('/assets/fonts/th_sarabun/THSarabunPSK.ttf') format('truetype'),
                 url('/assets/fonts/th_sarabun/THSarabunPSK.woff') format('woff');
            font-weight: normal;
            font-style: normal;
        }

        body {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
            background-color: #FAFAFA;
            /* font-family: 'THSarabunPSK', 'Tahoma'; */
            font-family:'Tahoma';
            /* font-size: 20px; */
        }

        * {
            box-sizing: border-box;
            -moz-box-sizing: border-box;
        }

        p {
            margin-top: 0;
            margin-bottom: 0;
            font-size: 14px;
        }

        .page {
            width: 210mm;
            min-height: 297mm;
            padding: 0 14mm;
            margin: 10mm auto;
            border: 1px #D3D3D3 solid;
            border-radius: 5px;
            background: white;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        .subpage {
            /*border: 1px solid #000;*/
            display: grid;
        }

        .wrap-row {
            width: 100%;
            float: left;
        }

        .wrap-col-50 {
            width: 50%;
            float: left;
            padding: 8px;
        }

        .wrap-col-100 {
            width: 100%;
            float: left;
            padding: 8px;
        }

        .wrap-col-80 {
            width: 100%;
            float: left;
            padding: 8px;
        }

        .wrap-col-50>h2 {
            margin: 0;
        }

        .wrap-col-80>h2 {
            margin: 0;
        }

        .wrap-col-100>h2 {
            margin: 0;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .sku-id {
            padding-left: 10px;
        }

        table {
            margin-top: 15px;
            margin-bottom: 10px;
            width: 100%;
            font-size: 18px;
        }

        table {
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid black;
            padding: 3px;
        }

        .border1{
            
        }

        @page {
            size: A4;
            margin: 0;
        }

        @media print {
            html,
            body {
                width: 210mm;
                height: 297mm;
            }
            .page {
                margin: 0;
                border: initial;
                border-radius: initial;
                width: initial;
                min-height: initial;
                box-shadow: initial;
                background: initial;
                page-break-after: always;
            }
        }

        .masonry-with-columns {
            columns: 2;
            column-gap: 1rem;
          }
          .masonry-with-columns .item {
            position: relative;
            padding:5px 10px;
            /* margin: 0 1rem 1rem 0; */
            margin:0;
            display: inline-block;
            width: 100%;
            border: 1px dashed #adb5bd;
          }
          .masonry-with-columns .item .title {
            font-weight: bold;
          }
          .mb-5{
              margin-bottom:5px;
          }
          .mb-10{
              margin-bottom:10px;
          }
          table {
              padding: 0;
              margin:0;
              font-size: 12px;
          }
          table,td{
            border: 1px solid #adb5bd;
          }

    </style>
</head>
<body>
    <div class="pick-print">
        @php
        $counter = 1;
        @endphp
        <div class="page">
            <div class="subpage">
                <div class="masonry-with-columns">

                    @foreach($orders as $order)
                    <div class="item">
                        <p class="mb-10"><span class="title">{!! strtoupper($order->code) !!}</span></p>
                        <div class="mb-10">
                            <p class="title">ผู้ส่ง</p>
                            <p>GG-GAGA</p>
                        </div>
                        <div class="mb-10">
                            <p class="title">ผู้รับ {{ ($order->is_cod == 'y') ? '(เก็บเงินปลายทาง)': '' }}</p>
                            <p>{!! $order->shipping_full_name.' ('.$order->shipping_phone.')' !!}</p>
                            <p>{!! $order->shipping_full_address !!}</p>
                        </div>
                        <div>
                            <table>
                                @foreach ($order->details as $item)
                                <tr>
                                    <td>{{$item->full_name}}</td>
                                    <td class="text-center">{{$item->quantity}}</td>
                                </tr>
                                @endforeach
                            </table>
                        </div>
                        {{-- <div class="mb-10">
                            <p class="title">หมายเหตุ</p>
                            <p>{{ ($order->remark)?$order->remark:'-' }}</p>
                        </div> --}}
                    </div>
                    @endforeach

                {{-- @php
                $counter ++;
                @endphp
                @endforeach --}}
                </div>
            </div>
        </div> 
        <!--endpage-->


        

    </div>
</body>
</html>