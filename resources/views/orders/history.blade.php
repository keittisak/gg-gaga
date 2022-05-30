@extends('layouts.main')
@section('title',$title_eng)
@section('css')
    {{--  Css  --}}
    <style>
        .dataTables_wrapper .dataTables_filter {
            display: none;
        }
    </style>
@endsection
@section('content')
<div class="page-header">
    <h1 class="page-title prompt-front">
        {{$title_th}}
    </h1>
</div>

<div class="row prompt-front">
    <div class="col-6 col-md-3">
        <div class="form-group">
            <label class="form-label">เลขที่สั่งซื้อ</label>
            <input type="text" class="form-control" name="code-input">
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="form-group">
            <label class="form-label">ลูกค้า</label>
            <input type="text" class="form-control" name="customer-input">
        </div>
    </div>
    <div class="col-12 col-md-3">
        <div class="form-group">
            <label class="form-label">สถานะ</label>
            <select class="form-control status select2 prompt-front" name="status" id="status" style="width:100%">
                <option value="">ทั้งหมด</option>
                @foreach ($statusInfo as $status => $item)
                <option value="{{$status}}" @if(Request::get('scope') == $status) checked @endif>{{$item['title']}}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
<div class="row prompt-front">
    <div class="col-6 col-md-3">
        <div class="form-group">
            <label class="form-label">วันที่สั่งซื้อ</label>
            <input type="text" class="form-control datepicker" name="create-at-input" value="">
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="form-group">
            <label class="form-label">เวลาโอน</label>
            <input type="text" class="form-control datepicker" name="transfered-at-input" value="">
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="form-group">
            <button type="button" class="btn btn-primary mt-md-5 btn-search"><i class="fe fe-search"></i> ค้นหา</button>
        </div>
    </div>
</div>

<div class="row row-cards row-deck">
    <div class="col-12">
        <div class="card">
            <div class="table-responsive">
                <table class="table card-table table-vcenter text-nowrap" id="table-order">
                    <thead>
                    <tr>
                        <th class="w-1">
                            <label class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="checkbox-all">
                                <span class="custom-control-label"></span>
                        </label>
                        </th>
                        <th>เลขที่สั่งซื้อ</th>
                        <th>วันที่สั่งซื้อ</th>
                        <th>ช่องทาง</th>
                        <th>ลูกค้า</th>
                        <th>ยอดสุทธิ</th>
                        <th>สถานะ</th>
                        <th>เวลาโอน</th>
                    </tr>
                    </thead>
                </table>
            </div>

        </div>
    </div>
</div>
@include('orders.include.detail_modal')
@endsection
@section('js')
<script>
    var table;
    var $_status = 'draft';
    var $_paging = false;
    var $_showCheckbox = true;
    require(['jquery', 'datatables','datepicker','sweetAlert','selectize','select2','moment'], function($, datatable, datepicker, Swal,selectize,select2,moment) {
        $('.select2').select2();
        $('.selectize').selectize();
        $('.datepicker').datepicker({
            autoclose:true,
            format:'dd/mm/yyyy',
            language:'th',
            setDate: new Date()
        });

        $(function(){
            loadOverview();
        });
        function loadOverview () {
            $.ajax({
                url:"{{route('orders.overview')}}",
                method: "GET",
            }).done(function(data){
                $.each(data,function(status,val){
                    $(`.overview-text-${status}`).html(val);
                })
            }).fail(function( jqxhr, textStatus ) {
                Swal.fire({
                    type: 'error',
                    title: jqXHR.responseJSON.message
                });
            });
        }

        $dt = $('#table-order');
        tableSetting = {
            processing: true,
            serverSide: true,
            ajax:{
                url:"{!! route('orders.data') !!}",
                data: function (d) {
                    var code = $('input[name=code-input]').val();
                    var customer_name = $('input[name=customer-input]').val();
                    var created_at = $('input[name=create-at-input]').val();
                    var transfered_at = $('input[name=transfered-at-input]').val();
                    var status = $('#status').val();
                    if(code){d.code=code}
                    if(customer_name){d.customer_name=customer_name}
                    if(created_at){d.created_at=created_at}
                    if(transfered_at){d.transfered_at=transfered_at}
                    if(status){d.scope=status}
                }
            },
            columns: [
                { data: 'checkbox', name: 'checkbox', orderable: false },
                { data: 'code', name: 'code' },
                { data: 'created_at', name: 'created_at' },
                { data: 'sale_channel', name: 'sale_channel' },
                { data: 'shipping_full_name', name: 'shipping_full_name' },
                { data: 'net_total_amount', name: 'net_total_amount' },
                { data: 'status', name: 'status' },
                { data: 'payments', name: 'payments' },
            ],
            order:[[1,"desc"]],
            paging:true,
            columnDefs : [
                {
                    targets: 0,
                    visible: false,
                },
                {
                    targets:1,
                    render: function (data, type, full, meta){
                        return `<button class="btn btn-link pl-0 show-details" data-id='${full.id}'>${data}</button>`;
                    }
                },
                {
                    targets:2,
                    render: function (data, type, full, meta){
                        if(data){
                            return moment(data).format('DD/MM/YYYY H:mm');
                        }
                        return '';
                    }
                },
                {
                    targets: 3,
                    render: function (data, type, full, meta){
                        var icon = ``;
                        if(data == 'line'){
                            icon = `<span class="h1 text-green"><i class="fab fa-line"></i></span>`;
                        }else if(data == 'facebook'){
                            icon = `<span class="h1 text-blue"><i class="fab fa-facebook-square"></i></span>`;
                        }else if(data == 'instagram'){
                            icon = `<span class="h1"><i class="fab fa-instagram-square"></i></span>`;
                        }else{
                            icon = `<span class="h1 text-info"><i class="fas fa-ellipsis-h"></i></span>`;
                        }
                        return icon;
                    }
                },
                {
                    targets: 5,
                    // className:'text-right',
                    render: function (data, type, full, meta){
                        return utilities.numberFormat(data);
                    }
                },
                {
                    targets: 6,
                    render: function (data, type, full, meta){
                        let status = {
                            'draft':'ร่าง',
                            'unpaid':'ยังไม่จ่าย',
                            'transfered':'โอนแล้ว',
                            'packing':'กำลังแพ็ค',
                            'paid':'เตรียมส่ง',
                            'shipped':'ส่งแล้ว',
                            'voided':'ยกเลิก'
                            
                        };
                        return status[data];
                    }
                },
                {
                    targets:7,
                    render: function (data, type, full, meta){
                        if(data[0] != undefined){
                            return moment(data[0].transfered_at).format('DD/MM/YYYY H:mm');
                        }
                        if(full.is_cod == 'y'){
                            return "เก็บเงินปลายทาง";
                        }
                        return '';
                    }
                },
            ],
            initComplete: function(){
                // $('.dataTables_filter').remove();
            },
            drawCallback: function (settings) {
                if (!$dt.parent().hasClass("table-responsive")) {
                    $dt.wrap("<div class='table-responsive text-nowrap'></div>");
                }
                loader.close();
            },
        };
        table = $dt.DataTable(tableSetting);

        $(document).on('click', '.show-details', function(e){
            var orderId = $(this).data('id');
            var _url = "{{ route('orders.by.id','__id') }}"
            _url = _url.replace('__id', orderId)
            $('.btn-edit-order').hide();
            $.ajax({
                url: _url,
                beforeSend: function( xhr ) {
                        loader.init();
                    },
            }).done(function(data){
                var code =data.code;
                $('#detailsModalLabel').text(code.toUpperCase());
                var $tableDetails = $('#table-details');
                var elementItem = ``;
                $.each(data.details, function(key, item){
                    elementItem+= `<tr><td>${item.full_name}</td><td class="text-right">${utilities.numberFormat(item.quantity,0)}</td><td class='text-right'>${utilities.numberFormat(item.total_amount)}</td></tr>`;
                });
                $('#customer-fullname').html(data.shipping_full_name);
                $('#customer-address').html(data.shipping_full_address);
                $tableDetails.find('tbody').html(elementItem);
                $tableDetails.find('.total-amount').html(utilities.numberFormat(data.total_amount));
                $tableDetails.find('.shipping-fee').html(utilities.numberFormat(data.shipping_fee));
                $tableDetails.find('.discount-amount').html(utilities.numberFormat(data.discount_amount));
                $tableDetails.find('.net-total-amount strong').html(utilities.numberFormat(data.net_total_amount));
                if(data.is_cod == 'n'){
                    if(data.payments[0] != undefined){
                        var transfered_at = moment(data.payments[0].transfered_at).format('DD/MM/YYYY H:mm');
                        $('.detail-transfered-at strong').html(`เวลาโอนเงิน: ${transfered_at}`);
                        $('.detail-slip-image').attr('href',data.payments[0].image);
                        if(data.payments[0].image){
                            $('.detail-slip-image').removeClass('d-none');
                        }else{
                            $('.detail-slip-image').addClass('d-none');
                        }
                    }else{
                        $('.detail-transfered-at strong').addClass('d-none');
                        $('.detail-slip-image').addClass('d-none');
                    }
                }else{
                    $('.detail-transfered-at strong').html('เก็บเงินปลายทาง');
                    $('.detail-transfered-at strong').removeClass('d-none');
                    $('.detail-slip-image').addClass('d-none');
                }
                $('.detail-created-at').html(`${moment(data.created_at).format('DD/MM/YYYY H:mm')} ${(data.created_by_user)?data.created_by_user.name:""}`);
                var urlEditOrder = "{{ route('orders.edit', '__id') }}";
                urlEditOrder  = urlEditOrder.replace('__id',data.id);
                $('.btn-edit-order').attr('href',urlEditOrder);
                $('#detailsModal').modal('show');
                loader.close();
            }).fail(function(jqXHR, textStatus, $form) {
                loader.close();
                Swal.fire({
                    type: 'error',
                    title: jqXHR.responseJSON.message
                });
            });
        })

        $('.btn-search').on('click',function(e){
            var text = $(this).parent().closest('.table-search').find('.text-search');
            loader.init();
            table.draw();
        });

    });

    require(['jquery', 'selectize'], function ($, selectize) {
        $('#select-beast').selectize({});
    });
  </script>
@endsection