<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css">
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
            columns: 1;
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
          .display-block {
              display:block;
              margin-bottom: 6px;
          }
          .btn{
            display: inline-block;
            font-weight: 400;
            color: #495057;
            text-align: center;
            vertical-align: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            background-color: transparent;
            border: 1px solid transparent;
            padding: 0.375rem 0.75rem;
            font-size: 0.9375rem;
            line-height: 1.84615385;
            border-radius: 3px;
            transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
          }
          .btn-primary {
            color: #fff;
            background-color: #467fcf;
            border-color: #467fcf;
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
                <div class="masonry-with-columns" id="">
                    @php
                        $text = "";
                    @endphp
                    @foreach($orders as $order)
                    @php
                        // $text .= strtoupper($order->code) ."</br>";
                        $text .= "ผู้ส่ง</br>";
                        $text .= "GG-GAGA<span class='display-block'></span>";
                        $text .= "ผู้รับ ";
                        if($order->is_cod == 'y'){
                            $text .= '(เก็บเงินปลายทาง)</br>';
                        }else{
                            $text .= '</br>';
                        }
                        $text .= "$order->shipping_full_name ($order->shipping_phone)</br>";
                        $text .= "$order->shipping_full_address<span class='display-block'></span>";
                    @endphp
                      @php
                      $text .= "</br>"
                  @endphp
                    @foreach ($order->details as $item)
                        @php
                            $text .= "$item->full_name = $item->quantity</br>";
                        @endphp
                    @endforeach
                    @php
                        $text .= "<br><br>"
                    @endphp
                    {{-- <div class="item" style="display:none">
                        <p class="mb-10"><span class="title">{!! strtoupper($order->code) !!}</span></p>
                        <div class="mb-10">
                            <p class="title">ผู้รับ</p>
                            <p>{!! $order->shipping_full_name.' ('.$order->shipping_phone.')' !!}</p>
                            <p>{!! $order->shipping_full_address !!}</p>
                        </div>
                        <div class="mb-10">
                            <p class="title">ผู้ส่ง</p>
                            <p>ตัวเล็ก SHOP (099-999-9999)</p>
                        </div>
                        <div>
                            <table>
                                @foreach ($order->details as $item)
                                @php
                                    $text .= "$item->full_name  $item->quantity</br>";
                                @endphp
                                <tr>
                                    <td>{{$item->full_name}}</td>
                                    <td class="text-center">{{$item->quantity}}</td>
                                </tr>
                                @endforeach
                            </table>
                        </div>
                    </div> --}}
                    @endforeach
                <div>
                    <button class="btn btn-primary" type="button" onclick="copy_data(shipping_text)" style="margin-top:20px; font-size:16px"><i class="far fa-copy"></i> คัดลอกข้อความ</button>
                </div>
                <div id="shipping_text">
                    <h4>จำนวนทั้งหมด {{count($orders)}} ออเดอร์ <p style="font-size:12px;font-style: normal;">{{DATE('d/m/Y H:i')}}</p></h4>
                    {!!$text!!}
                </div>
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
<script>
    function copy_data(containerid) {
        var range = document.createRange();
        range.selectNode(containerid); //changed here
        window.getSelection().removeAllRanges(); 
        window.getSelection().addRange(range); 
        document.execCommand("copy");
        window.getSelection().removeAllRanges();
        alert("คัดลอกข้อความเรียบร้อย");
    }
</script>
</html>