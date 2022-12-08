@extends('layouts.main')
@section('title',$title_eng)
@section('css')
    {{--  Css  --}}
    <style>
        .dataTables_wrapper .dataTables_filter {
            display: none;
        }
        .display-5{
            font-size: 2rem;
            font-weight: 300;
            line-height: 1.1;
        }
    </style>
@endsection
@section('content')
<div class="prompt-front">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="mb-3">
                        <span class="h4 font-weight-normal">ข้อมูลการใช้งาน</span>
                        <button class="btn btn-primary btn-sm float-right"><i class="fas fa-sync-alt"></i> ปรับปรุงใหม่</button>
                    </div>
                    <span class="h5 font-weight-normal text-muted">แสดงข้อมูลวันที่ {{$latestTime}} / {{$latestDate}}</span>
                    
                </div>
            </div>
        </div>
    </div>
    @php
        $dates = [
            'today' => 'วันนี้',
            'yesterday' => 'เมื่อวาน',
            'seven_day' => '7 วัน',
            'this_month' => 'เดือนนี้',
            'last_mouth' => 'เดือนที่แล้ว'
        ];
    @endphp 
    <h1 class="page-title prompt-front">
        ยอดขาย
    </h1>
    <div class="row">
        @foreach ($overviewTotal as $key => $item)
        @php
            $date_title = 'วันนี้';    
            if($key == 'yesterday'){
                $date_title = 'เมื่อวาน';
            }else if($key == 'this_month'){
                $date_title = 'เดือนนี้';
            }else if($key == 'last_mouth'){
                $date_title = 'เดือนที่แล้ว';
            }
        @endphp
            <div class="col-sm-3 col-6">
                <div class="card">
                <div class="card-body text-center py-0 pt-1">
                    <div class="text-right text-muted">ทั้งหมด</div>
                    <div class="display-5 font-weight-bold mb-4">{{number_format($item['total_amount'])}}</div>
                    <div class="h5">{{$date_title}}</div>
                    <div class="text-muted mb-4">{{$item['total_order']}} Order</div>
                </div>
                </div>
            </div>
        @endforeach
    </div>
    {{-- <div class="row">
        @foreach ($overviewTotalCod as $key => $item)
        @php
            $date_title = 'วันนี้';    
            if($key == 'yesterday'){
                $date_title = 'เมื่อวาน';
            }else if($key == 'this_month'){
                $date_title = 'เดือนนี้';
            }else if($key == 'last_mouth'){
                $date_title = 'เดือนที่แล้ว';
            }
        @endphp
            <div class="col-sm-3 col-6">
                <div class="card">
                <div class="card-body text-center py-0 pt-1">
                    <div class="text-right text-muted">เก็บเงินปลายทาง</div>
                    <div class="display-5 font-weight-bold mb-4">{{number_format($item['total_amount'])}}</div>
                    <div class="h5">{{$date_title}}</div>
                    <div class="text-muted mb-4">{{$item['total_order']}} Order</div>
                </div>
                </div>
            </div>
        @endforeach
    </div> --}}
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="dimmer">
                    <div class="loader"></div>
                    <div class="dimmer-content">
                        <div class="card-header">
                            <h4 class="card-title">ออเดอร์</h4>
                            <div class="card-options">
                                <div class="dropdown">
                                    <button data-toggle="dropdown" type="button" class="btn btn-secondary dropdown-toggle btn-sm" aria-expanded="true"><span class="text-date">{{ (in_array(Auth::user()->id, [1,4])) ? 'ทั้งหมด':'วันนี้' }}</span> <span class="ml-2 text-muted"><i class="fas fa-caret-down"></i></span></button>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow" x-placement="bottom-end" style="position: absolute; transform: translate3d(-56px, 32px, 0px); top: 0px; left: 0px; will-change: transform;">
                                        @if(in_array(Auth::user()->id, [1,4]))
                                        <a class="dropdown-item btn-print-items btn-express-date" data-type="all" data-card="order">ทั้งหมด</a>
                                        @endif
                                        @foreach ($dates as $key => $date)
                                            <a class="dropdown-item btn-print-items btn-express-date" data-type="{{$key}}" data-card="order">{{$date}}</a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <table class="table card-table">
                            <thead>
                                <tr>
                                    <th>สถานะ</th>
                                    <th class="text-right">จำนวน</th>
                                    <th class="text-right">ยอดสุธิ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orderByStatusTotal as $status => $order)
                                @if($status != 'total')
                                    <tr>
                                        <td><span class="{{ $order['text_color'] }} mr-2"><i class="{{ $order['icon'] }}"></i></span> {{ $order['title'] }}</td>
                                        <td class="text-right">{{ number_format($order['quantity']) }}</td>
                                        <td class="text-right">{{ number_format($order['net_total_amount'],2,'.',',') }}</td>
                                    </tr>
                                @endif
                                @endforeach
                                <tr>
                                    <td>จำนวนรวม</td>
                                    <td class="text-right">{{ number_format($orderByStatusTotal['total']['quantity']) }}</td>
                                    <td class="text-right">{{ number_format($orderByStatusTotal['total']['net_total_amount'],2,'.',',') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card" style="height:470px; overflow: scroll;">
                <div class="dimmer">
                    <div class="loader"></div>
                    <div class="dimmer-content">
                        <div class="card-header">
                            <h3 class="card-title">ยอดขายตามสินค้า</h3>
                            <div class="card-options">
                                <div class="dropdown">
                                    <button data-toggle="dropdown" type="button" class="btn btn-secondary dropdown-toggle btn-sm" aria-expanded="true"><span class="text-date">วันนี้</span> <span class="ml-2 text-muted"><i class="fas fa-caret-down"></i></span></button>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow" x-placement="bottom-end" style="position: absolute; transform: translate3d(-56px, 32px, 0px); top: 0px; left: 0px; will-change: transform;">
                                        <a class="dropdown-item btn-print-items btn-express-date" data-type="all" data-card="sale-by-product">ทั้งหมด</a>
                                        @foreach ($dates as $key => $date)
                                            <a class="dropdown-item btn-print-items btn-express-date" data-type="{{$key}}" data-card="sale-by-product">{{$date}}</a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <table class="table table-hover table-outline table-vcenter text-nowrap card-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>ชื่อสินค้า</th>
                                    <th class="text-right">จำนวน</th>
                                    <th class="text-right">จำนวนเงิน</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($salesByProduct as $key => $item)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{$item->full_name}}</td>
                                        <td class="text-right">{{number_format($item->quantity)}}</td>
                                        <td class="text-right">{{number_format($item->total_amount,2,'.',',')}}</td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="dimmer">
                    <div class="loader"></div>
                    <div class="dimmer-content">
                    <div class="card-header">
                        <h4 class="card-title">ช่องทางสั่งซื้อ</h4>
                        <div class="card-options">
                            <div class="dropdown">
                                <button data-toggle="dropdown" type="button" class="btn btn-secondary dropdown-toggle btn-sm" aria-expanded="true"><span class="text-date">วันนี้</span> <span class="ml-2 text-muted"><i class="fas fa-caret-down"></i></span></button>
                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow" x-placement="bottom-end" style="position: absolute; transform: translate3d(-56px, 32px, 0px); top: 0px; left: 0px; will-change: transform;">
                                    <a class="dropdown-item btn-print-items btn-express-date" data-type="all" data-card="sale-by-channel">ทั้งหมด</a>
                                    @foreach ($dates as $key => $date)
                                        <a class="dropdown-item btn-print-items btn-express-date" data-type="{{$key}}" data-card="sale-by-channel">{{$date}}</a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <table class="table card-table">
                        <tbody>
                            @foreach ($saleChannel as $key => $item)
                            <tr>
                                <td width="1"><span class="{{$item['text_color']}}"><i class="{{ $item['icon'] }}"></i></span></td>
                                <td>{{ strtoupper($key) }}</td>
                                <td class="text-right"><span class="text-muted">{{ $item['per'] }}%</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('js')
<script>
    require(['jquery', 'datatables','moment'], function($, datatable,moment) {
        $('.btn-express-date').on('click',function(e){
            var type = $(this).data('type');
            var card = $(this).data('card');
            var url;
            var startDate = "";
            var endDate = "";
            var textDate = "";
            if(type == 'today'){
                startDate = moment();
                endDate = moment();
                textDate = "วันนี้";
            }else if(type == 'yesterday'){
                startDate = moment().subtract(1, 'days');
                endDate = moment().subtract(1, 'days');
                textDate = "เมื่อวาน";
            }else if(type == 'this_month'){
                startDate = moment().startOf('month');
                endDate = moment().endOf("month");
                textDate = "เดือนนี้";
            }else if(type == 'last_mouth'){
                startDate = moment().subtract(1, 'months').startOf('month');
                endDate = moment().subtract(1, 'months').endOf("month");
                textDate = "เดือนที่แล้ว";
            }else if(type == 'seven_day'){
                startDate = moment().subtract(7, 'days');
                endDate = moment();
                textDate = "7 วัน";
            }else if(type =='all'){
                textDate = "ทั้งหมด";
            }

            if(startDate != "" && endDate != ""){
                startDate = startDate.format('YYYY-MM-DD');
                endDate = endDate.format('YYYY-MM-DD');
            }
            if(card == 'order'){
                url = "{{ route('dashboard.data.order-by-status-total') }}";
            }else if(card == 'sale-by-product'){
                url = "{{ route('dashboard.data.sales-by-product') }}";
            }else if(card =='sale-by-channel'){
                url = "{{ route('dashboard.data.sale-by-channel') }}";
            }
            $(this).closest('.dropdown').removeClass('show');
            $(this).closest('.dropdown').find('.dropdown-menu').removeClass('show');
            $(this).closest('.dropdown').find('.dropdown-toggle .text-date').html(`${textDate}`);
            var button = $(this);
            $.ajax({
                url: url,
                method:"POST",
                dataType: "JSON",
                data:{
                    _token: "{{ csrf_token() }}",
                    start_date: startDate,
                    end_date: endDate
                },
                beforeSend: function( xhr ) {
                    $(button).closest('.card').find('.dimmer').addClass('active');
                },
            })
            .done(function(data){
                $(button).closest('.card').find('.dimmer').removeClass('active');
                var element = ``;
                $.map(data,function(item,i){
                    if(card =='sale-by-channel'){
                        element += `<tr>
                                    <td width="1"><span class="${item.text_color}"><i class="${item.icon }"></i></span></td>
                                    <td>${i.toUpperCase()}</td>
                                    <td class="text-right"><span class="text-muted">${item.per} %</span></td>
                                </tr>`;
                    }else if(card == 'order'){
                        element += `<tr>
                                        <td><span class="${item.text_color} mr-2"><i class="${item.icon}"></i></span> ${item.title}</td>
                                        <td class="text-right">${utilities.numberFormat(item.quantity,0)}</td>
                                        <td class="text-right">${utilities.numberFormat(item.net_total_amount)}</td>
                                    </tr>`;
                    }else if(card == 'sale-by-product'){
                        element +=`<tr>
                                        <td>${i+1}</td>
                                        <td>${item.full_name}</td>
                                        <td class="text-right">${utilities.numberFormat(item.quantity,0)}</td>
                                        <td class="text-right">${utilities.numberFormat(item.total_amount)}</td>
                                    </tr>`;
                    }
                });
                $(button).closest('.card').find('.card-table tbody').html(element)

            })
            .fail(function(jqXHR, textStatus, $form) {
                $(button).closest('.card').find('.dimmer').removeClass('active');
            });
            

        })
    });
</script>
@endsection