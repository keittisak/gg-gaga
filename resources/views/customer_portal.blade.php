<!DOCTYPE html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ __('Customer Portal') }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,300i,400,400i,500,500i,600,600i,700,700i&amp;subset=latin-ext">
    <link href="https://fonts.googleapis.com/css?family=Prompt&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Kanit&display=swap" rel="stylesheet">
    <script src="{{ asset('assets/js/require.min.js') }}"></script>
    <script>
      requirejs.config({
          baseUrl: '{{ asset("") }}'
      });
    </script>
    <!-- Dashboard Core -->
    <link href="{{ asset('assets/css/dashboard.css') }}?v=5" rel="stylesheet" />
    <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet" />
    <script src="{{ asset('assets/js/dashboard.js') }}"></script>
</head>
<style>
    .loader-page{
        position: fixed;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgb(255, 255, 255);
        opacity: 0.5;
        z-index:99998;
    }
    .loader-center{
        position: fixed;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        z-index:99999;
        
    }
</style>
<body class="prompt-front">
    <div class="page d-block">
        <div class="page-main">
            <div class="my-3 my-md-5">
                <div class="container">
                    <div class="page-heade text-center">
                        <h1 class="h1">
                          GG-GAGA
                        </h1>
                    </div>
                    <div class="row">
                        <div class="col-md-6 offset-md-3 col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">{{ __('เลขที่ออเดอร์') }} {{ strtoupper($order->code) }}</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <p class="h3">{{ __('สถานะ') }}</p>
                                        </div>
                                        <div class="col text-right">
                                            <p class="h4 text-warning">{{ $statusInfo[$order->status]['title'] }}</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <p>{{__('รหัสพัสดุ')}}</p>
                                        </div>
                                        <div class="col text-right">
                                         <p class="h5">{{ ($order->tracking_code)?$tracking_code:'-' }}</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <p>{{__('ชื่อลูกค้า')}}</p>
                                        </div>
                                        <div class="col text-right">
                                            <p>{{ $order->shipping_full_name }}</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <p>เบอร์โทรศัพท์</p>
                                        </div>
                                        <div class="col text-right">
                                            <p>{{ $order->shipping_phone }}</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <p>ที่อยู่จัดส่ง</p>
                                        </div>
                                        <div class="col text-right">
                                            <p>{{ $order->shipping_full_address }}</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <p class="h5">รายละเอียดสินค้า</p>
                                        </div>
                                    </div>
                                    <ul class="list-unstyled list-separated mb-0">
                                        @foreach($order->details as $item)
                                        <li class="list-separated-item">
                                            <div class="row align-items-center">
                                                <div class="col">
                                                    <div>
                                                        <a class="text-inherit">{{ $item->full_name }}</a>
                                                    </div>
                                                    <small class="d-block item-except h-1x">จำนวน {{ $item->quantity }}</small>
                                                </div>
                                                <div class="col-auto">
                                                    {{ number_format($item->total_amount,2,'.',',') }}
                                                </div>
                                            </div>
                                        </li>
                                        @endforeach
                                        <li class="list-separated-item pb-0"></li>
                                    </ul>
                                    <div class="row">
                                        <div class="col">
                                            <p>มูลค่าสินค้า</p>
                                        </div>
                                        <div class="col text-right">
                                            <p>{{ number_format($order->total_amount,2,'.',',') }}</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <p>ค่าจัดส่ง</p>
                                        </div>
                                        <div class="col text-right">
                                            <p>{{ number_format($order->shipping_fee,2,'.',',') }}</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <p>ส่วนลด</p>
                                        </div>
                                        <div class="col text-right">
                                            <p>{{ number_format($order->discount_amount,2,'.',',') }}</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <hr class="mt-0 mb-5" style="border-top: 1px solid rgba(0, 40, 100, 0.12);">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <p class="h4">ยอดสุทธิ</p>
                                        </div>
                                        <div class="col text-right">
                                            <p class="h3">{{ number_format($order->net_total_amount,2,'.',',') }}</p>
                                        </div>
                                    </div>
                                </div>
  
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col text-center">
                            <p class="my-0">หากมีข้อสงสัย หรือต้องการเปลี่ยนแปลงข้อมูลใดๆ</p>
                            <p class="">ติดต่อเราโดยตรง</p>
                            <a href="#" class="btn btn-outline-primary mb-5">ติดต่อเรา</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- @include('layouts.footer') --}}
    </div>
</body>
<script>
</script>

</html>