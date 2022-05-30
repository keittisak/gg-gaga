@extends('layouts.main')
@section('title',$title_eng)
@section('css')
    {{--  Css  --}}
    <style>
        .dataTables_wrapper .dataTables_filter {
            display: none;
        }
        .select2-selection__rendered{
            text-align: center;
        }
        .overview-card{
            cursor: pointer;
        }
        .corsor-pointer{
            cursor: pointer;
        }
    </style>
@endsection
@section('content')
<div class="page-header">
<h1 class="page-title prompt-front">
        {{$title_th}}
    </h1>
</div>
<div class="row row-cards prompt-front" id="overview">
    @foreach ($statusInfo as $status => $item)
    @if($status != 'voided')
        <div class="col-6 col-sm-4 col-lg-2">
            <div class="card overview-card" data-status="{{$status}}" id="overview-card-{{$status}}">
                <div class="card-body p-3 text-center">
                    @if($status == 'draft')
                    <div class="text-right text-blue">
                    <i class="fas fa-inbox"></i>
                    @elseif($status == 'unpaid')
                    <div class="text-right text-red">
                    <i class="far fa-times-circle"></i>
                    @elseif($status == 'transfered')
                    <div class="text-right text-green">
                    <i class="far fa-check-circle"></i>
                    @elseif($status == 'packing')
                    <div class="text-right text-info">
                    <i class="fas fa-tape"></i>
                    @elseif($status == 'paid')
                    <div class="text-right text-muted">
                    <i class="fas fa-archive"></i>
                    @elseif($status == 'shipped')
                    <div class="text-right text-success">
                    <i class="fas fa-shipping-fast"></i>
                    @endif
                    </div>
                <div class="h1 m-0 overview-text-{{$status}}">0</div>
                <div class="text-muted">{{ $item['title'] }}</div>
                </div>
            </div>
        </div>
    @endif
    @endforeach
</div>

<div class="row-main row d-none d-md-flex prompt-front">
    <div class="col-12 col-md-12 col-lg-8 mb-5">
        <div class="row gutters-xs table-search">
            <div class="col">
                <select class="form-control status select2 prompt-front" name="status" style="text-align-last: center">
                    @foreach ($statusInfo as $status => $item)
                    <option value="{{$status}}" @if(Request::get('scope') == $status) checked @endif>{{$item['title']}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col">
                <input type="text" class="form-control text-search" placeholder="ค้นหา ...">
            </div>
            <span class="col-auto">
                <button class="btn btn-secondary btn-search" type="button"><i class="fe fe-search"></i></button>
            </span>
            <span class="col-auto">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#advancedSearchModal"><i class="fe fe-sliders"></i> ค้นหาขั้นสูง</button>
            </span>
        </div>
    </div>
    <div class="col-12 col-md-12 col-lg-4 text-right mb-5  prompt-front">
        <div class="dropdown">
            <button data-toggle="dropdown" type="button" class="btn btn-primary dropdown-toggle btn-change-status" aria-expanded="true">เปลี่ยนสถานะ</button>
            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow" x-placement="bottom-end" style="position: absolute; transform: translate3d(-56px, 32px, 0px); top: 0px; left: 0px; will-change: transform;">
                @foreach ($statusInfo as $status => $item)
                <a class="dropdown-item btn-change-status-items corsor-pointer" data-status="{{$status}}"><span class="{{$item['text_color']}} mr-3"><i class="{{$item['icon']}}"></i></span>{{$item['title']}}</a>
                @endforeach
            </div>
        </div>
        <div class="dropdown">
            <button data-toggle="dropdown" type="button" class="btn btn-primary dropdown-toggle btn-print" aria-expanded="true">พิมพ์</button>
            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow" x-placement="bottom-end" style="position: absolute; transform: translate3d(-56px, 32px, 0px); top: 0px; left: 0px; will-change: transform;">
                <a class="dropdown-item btn-print-items corsor-pointer" data-type="label"><span class="text-primary mr-3"><i class="fas fa-tag"></i></span>{{ __('ใบปะหน้ากล่อง 1') }}</a>
                <a class="dropdown-item btn-print-items corsor-pointer" data-type="label-to-text"><span class="text-primary mr-3"><i class="fas fa-tag"></i></span>{{ __('ใบปะหน้ากล่อง 2') }}</a>
                <a class="dropdown-item btn-print-items corsor-pointer" data-type="list"><span class="text-info mr-3"><i class="far fa-list-alt"></i></span>{{ __('รายการแพ็คของ') }}</a>
                <a class="dropdown-item btn-print-items corsor-pointer" data-type="label-large"><span class="text-primary mr-3"><i class="fas fa-tag"></i></span>{{ __('ใบปะหน้ากล่อง 100x70') }}</a>
                <a class="dropdown-item btn-print-items corsor-pointer" data-type="label-small"><span class="text-primary mr-3"><i class="fas fa-tag"></i></span>{{ __('ใบปะหน้ากล่อง 60x40') }}</a>
                <a class="dropdown-item btn-export-execl corsor-pointer"><span class="text-primary mr-3"><i class="far fa-file-excel"></i></span>{{ __('Export Excel') }}</a>
                <a class="dropdown-item btn-export-flash-order corsor-pointer"><span class="text-primary mr-3"><i class="far fa-file-excel"></i></span>{{ __('Flash Orders Export') }}</a>
            </div>
        </div>
    </div>
</div>

<div class="row-main">
    <div class="row d-md-none prompt-front">
        <div class="col-12 ">
            <div class="form-group">
                <select class="form-control status select2" name="status" style="text-align-last: center">
                    @foreach ($statusInfo as $status => $item)
                    <option value="{{$status}}" @if(Request::get('scope') == $status) checked @endif>{{$item['title']}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group table-search">
                <div class="input-group mb-3">
                    <input type="text" class="form-control text-search" placeholder="ค้นหา ...">
                    <span class="input-group-append">
                        <button class="btn btn-secondary pl-3 pr-3 btn-search" type="button"><i class="fe fe-search"></i></button>
                    </span>
                    <span class="input-group-append">
                        <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#advancedSearchModal">ค้นหาขั้นสูง</button>
                    </span>
                </div>
            </div>
            
        </div>
    </div>

    <div class="row d-md-none mb-5 prompt-front">
        <div class="col-6">
            <div class="dropdown w-100">
                <button data-toggle="dropdown" type="button" class="btn btn-primary btn-block dropdown-toggle btn-change-status" aria-expanded="true">เปลี่ยนสถานะ</button>
                <div class="dropdown-menu dropdown-menu-left dropdown-menu-arrow" x-placement="bottom-end" style="position: absolute; transform: translate3d(-56px, 32px, 0px); top: 0px; left: 0px; will-change: transform;">
                    @foreach ($statusInfo as $status => $item)
                    <a class="dropdown-item btn-change-status-items" data-status="{{$status}}"><span class="{{$item['text_color']}} mr-3"><i class="{{$item['icon']}}"></i></span>{{$item['title']}}</a>
                    @endforeach
                    
                </div>
            </div>
        </div>
        <div class="col-6 ">
            <div class="dropdown w-100">
                <button data-toggle="dropdown" type="button" class="btn btn-primary btn-block dropdown-toggle btn-print" aria-expanded="true">พิมพ์</button>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow" x-placement="bottom-end" style="position: absolute; transform: translate3d(-56px, 32px, 0px); top: 0px; left: 0px; will-change: transform;">
                    <a class="dropdown-item btn-print-items" data-type="label"><span class="text-primary mr-3"><i class="fas fa-tag"></i></span>{{ __('ใบปะหน้ากล่อง 1') }}</a>
                    <a class="dropdown-item btn-print-items" data-type="label-to-text"><span class="text-primary mr-3"><i class="fas fa-tag"></i></span>{{ __('ใบปะหน้ากล่อง 2') }}</a>
                    <a class="dropdown-item btn-print-items" data-type="list"><span class="text-info mr-3"><i class="far fa-list-alt"></i></span>{{ __('รายการแพ็คของ') }}</a>
                    <a class="dropdown-item btn-print-items" data-type="label-large"><span class="text-primary mr-3"><i class="fas fa-tag"></i></span>{{ __('ใบปะหน้ากล่อง 100x70') }}</a>
                    <a class="dropdown-item btn-print-items" data-type="label-small"><span class="text-primary mr-3"><i class="fas fa-tag"></i></span>{{ __('ใบปะหน้ากล่อง 60x40') }}</a>
                </div>
            </div>
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
                        <th>ผู้สั่งสินค้า</th>
                        <th>ยอดสุทธิ</th>
                        <th>สถานะ</th>
                        <th>เวลาโอน</th>
                        <th>สร้างโดย</th>
                    </tr>
                    </thead>
                </table>
            </div>

        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade prompt-front" id="advancedSearchModal" tabindex="-1" role="dialog" aria-labelledby="advancedSearchModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="advancedSearchModalLabel"><i class="fe fe-sliders"></i> ค้นหาขั้นสูง</h5>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label class="form-label">เลขที่สั่งซื้อ</label>
                        <input type="text" class="form-control" name="code-input">
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label class="form-label">ลูกค้า</label>
                        <input type="text" class="form-control" name="customer-input">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label class="form-label">วันที่สั่งซื้อ</label>
                        <input type="text" class="form-control datepicker" name="create-at-input" placeholder="{{DATE('d/m/Y')}}">
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label class="form-label">เวลาโอน</label>
                        <input type="text" class="form-control datepicker" name="transfered-at-input" placeholder="{{DATE('d/m/Y')}}">
                    </div>
                </div>
            </div>
            
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
            <button type="button" class="btn btn-primary btn-advanced-search"><i class="fe fe-search"></i> ค้นหา</button>
        </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade prompt-front" id="printModal" tabindex="-1" role="dialog" aria-labelledby="printModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="printModalLabel"><i class="fas fa-tag mr-2"></i> ใบแปะหน้ากล่อง 100x70</h5>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label">ขนาดตัวอักษร (Pixel)</label>
                        <input type="number" class="form-control" name="font_size" value="14">
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary float-left" data-dismiss="modal">ยกเลิก</button>
            <button type="button" class="btn btn-primary print-version-2" data-type="large"><i class="fas fa-print"></i> พิมพ์</button>
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
    require(['jquery', 'datatables','datepicker','sweetAlert','selectize','select2', 'moment','clipboard'], function($, datatable, datepicker, Swal,selectize,select2, moment,clipboard) {
        $.fn.modal.Constructor.prototype._enforceFocus = function() {};
        var clipboard = new clipboard('.clipboard');
        clipboard.on('success', function(e) {
            // console.info('Action:', e.action);
            // console.info('Text:', e.text);
            // console.info('Trigger:', e.trigger);
            Swal.fire({
                type: 'success',
                title: 'คัดลอกลิ้งเรียบร้อย'
            });
        });
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
                    if(code){d.code=code}
                    if(customer_name){d.customer_name=customer_name}
                    if(created_at){d.created_at=created_at}
                    if(transfered_at){d.transfered_at=transfered_at}
                    d.scope = $_status;
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
                { data: 'payments', name: 'payments', orderable: false  },
                { data: 'created_by', name: 'created_by' },
            ],
            order:[[1,"desc"]],
            paging:$_paging,
            columnDefs : [
                {
                    targets: 0,
                    visible: $_showCheckbox,
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
                        return moment(data).format('DD/MM/YYYY H:mm');
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
                        if(full.payments.length){
                            return moment(full.payments[0].transfered_at).format('DD/MM/YYYY H:mm');
                        }
                        if(full.is_cod == 'y'){
                            return "เก็บเงินปลายทาง";
                        }
                        return "-";
                    }
                },
                {
                    targets: 8,
                    render: function (data, type, full, meta){
                        return full.created_by_user.name;
                    }
                },
            ],
            initComplete: function(){
                // $('.dataTables_filter').remove();
                $('.card-status').remove();
                $('#overview-card-'+$_status).append(`<div class="card-status bg-blue"></div>`);
                console.log($_status);
            },
            drawCallback: function (settings) {
                if (!$dt.parent().hasClass("table-responsive")) {
                    $dt.wrap("<div class='table-responsive text-nowrap'></div>");
                }
                loader.close();
            },
        };
        table = $dt.DataTable(tableSetting);

        $('.status').on('change',function(e){
            $_status = $(this).val();
            if($_status == 'shipped'){
                $_paging =true;
                $_showCheckbox = false;
            }else{
                $_paging =false;
                $_showCheckbox = true;
            }
            loader.init();
            $('#checkbox-all').prop('checked',false);
            $('.btn-change-status').text(`เปลี่ยนสถานะ`);
            $('.btn-print').text(`พิมพ์`);
            $dt.DataTable().destroy()
            tableSetting.paging = $_paging;
            // tableSetting.columnDefs[0].visible = $_showCheckbox;
            tableSetting.columnDefs[0].bVisible = $_showCheckbox;
            table = $dt.DataTable(tableSetting);
        });
        $('.overview-card').on('click',function(e){
            $_status = $(this).data('status');
            $('.status').val($_status).change();
        });

        $(document).on('click', '.show-details', function(e){
            var orderId = $(this).data('id');
            var _url = "{{ route('orders.by.id','__id') }}"
            _url = _url.replace('__id', orderId)
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

                $('.customer-portal-link').attr('data-clipboard-text',data.link);
                $('.customer-portal-link').addClass('d-none');
                if(data.link){
                    $('.customer-portal-link').removeClass('d-none');
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
            $(".modal").on("shown.bs.modal", function() {
            });
        })

        $('.btn-search').on('click',function(e){
            var text = $(this).parent().closest('.table-search').find('.text-search');
            loader.init();
            table.search(text.val()).draw();
        });
        $('.btn-advanced-search').on('click',function(e){
            table.draw();
            $('input[name=code-input]').val('');
            $('input[name=customer-input]').val('');
            $('input[name=create-at-input]').val('');
            $('input[name=transfered-at-input]').val('');
            $('#advancedSearchModal').modal('hide');
        });

        $('#checkbox-all').on('change',function(e){
            if($(this).prop('checked')){
                $('input[name=checkbox]').prop('checked',true);
            }else{
                $('input[name=checkbox]').prop('checked',false);
            }
            var quantity = $('input[name=checkbox]:checked').length;
            if(quantity > 0){
                $('.btn-change-status').text(`เปลี่ยนสถานะ (${quantity})`);
                $('.btn-print').text(`พิมพ์ (${quantity})`);
            }else{
                $('.btn-change-status').text(`เปลี่ยนสถานะ`);
                $('.btn-print').text(`พิมพ์`);
            }
        })

        $(document).on('change', '.checkbox-order',function(e){
            var quantity = $('input[name=checkbox]:checked').length;
            if(quantity > 0){
                $('.btn-change-status').text(`เปลี่ยนสถานะ (${quantity})`);
                $('.btn-print').text(`พิมพ์ (${quantity})`);
            }else{
                $('.btn-change-status').text(`เปลี่ยนสถานะ`);
                $('.btn-print').text(`พิมพ์`);
            }
        });

        $('.btn-change-status-items').on('click',function(e){
            let statusInfo = {
                            'draft':'ร่าง',
                            'unpaid':'ยังไม่จ่าย',
                            'transfered':'โอนแล้ว',
                            'packing':'กำลังแพ็ค',
                            'paid':'เตรียมส่ง',
                            'shipped':'ส่งแล้ว',
                            'voided':'ยกเลิก'
                            
                        };

            let currentStatus = $(this).parent().closest('.row-main').find('.status').val();
            let status = $(this).data('status');
            var orderIds = [];
            $('input[name=checkbox]').each(function(i,element){
                if($(element).prop('checked')){
                    orderIds.push($(element).val())
                }
            });
            Swal.fire({
                title: 'คุณแน่ใจใช่ไหม?',
                text:`เปลี่ยนสถานะออเดอร์จาก "${statusInfo[currentStatus]}" เป็นสถานะ "${statusInfo[status]}" จำนวน ${orderIds.length} ออเดอร์`,
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ยืนยัน!',
                cancelButtonText: 'ยกเลิก'
              }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url:"{{ route('orders.status') }}",
                        method:"POST",
                        dataType: "JSON",
                        data:{
                            _method:"PATCH",
                            _token: "{{ csrf_token() }}",
                            ids: orderIds,
                            status: status
                        },
                        beforeSend: function( xhr ) {
                            loader.init();
                        },
                    })
                    .done(function(data){
                        loader.close();
                        Swal.fire({
                            type: "success",
                            title: "บันทึกข้อมูลเรียบร้อย", 
                        });
                        loadOverview();
                        table.draw();
                        $('.btn-change-status').text(`เปลี่ยนสถานะ`);
                        $('.btn-print').text(`พิมพ์`);
                    })
                    .fail(function(jqXHR, textStatus, $form) {
                        loader.close();
                        Swal.fire({
                            type: 'error',
                            title: jqXHR.responseJSON.message
                        });
                    });
                }
            });
        });

        $('.btn-print-items').on('click',function(e){
            let _this = $(this);
            let type = _this.data('type');
            var orderIds = [];
            $('input[name=checkbox]').each(function(i,element){
                if($(element).prop('checked')){
                    orderIds.push($(element).val())
                }
            });
            if(orderIds.length === 0){
                Swal.fire({
                    type: 'warning',
                    title: 'กรุณาเลือกเลขที่สั่งซื้อ'
                });
                return false;
            }
            if(type == 'label'){
                window.open("{!! route('orders.print.label') !!}"+"?"+jQuery.param({orderIds}), '_newtab');
            }else if(type == 'label-to-text'){
                window.open("{!! route('orders.print.label.to_text') !!}"+"?"+jQuery.param({orderIds}), '_newtab');
            }else if(type == 'label-large'){
                $('#printModal').find('.modal-title').html(`<i class="fas fa-tag mr-2"></i> `+_this.text());
                $('#printModal').find('.print-version-2').attr('data-type',type);
                $('#printModal').find('input[name="font_size"]').val(14);
                $('#printModal').modal('show');
                // window.open("{!! route('orders.print.label.large') !!}"+"?"+jQuery.param({orderIds}), '_newtab');
            }else if(type == 'label-small'){
                $('#printModal').find('.modal-title').html(`<i class="fas fa-tag mr-2"></i> `+_this.text());
                $('#printModal').find('.print-version-2').attr('data-type',type);
                $('#printModal').find('input[name="font_size"]').val(8);
                $('#printModal').modal('show');
                // window.open("{!! route('orders.print.label.small') !!}"+"?"+jQuery.param({orderIds}), '_newtab');
            }
            else{
                window.open("{!! route('orders.print.list') !!}"+"?"+jQuery.param({orderIds}), '_newtab');
            }
            
        });

        $('.print-version-2').on('click',function(e){
            let _this = $(this);
            let type = _this.data('type');
            var orderIds = [];
            $('input[name=checkbox]').each(function(i,element){
                if($(element).prop('checked')){
                    orderIds.push($(element).val())
                }
            });
            if(orderIds.length === 0){
                Swal.fire({
                    type: 'warning',
                    title: 'กรุณาเลือกเลขที่สั่งซื้อ'
                });
                return false;
            }

            var fontSize = $('input[name="font_size"]').val();
            if(fontSize == ""){
                Swal.fire({
                    type: 'warning',
                    title: 'กรุณาระบุขนาดตัวอักษร'
                });
                return false;
            }
            if(type == 'label-large'){
                window.open("{!! route('orders.print.label.large') !!}"+"?"+jQuery.param({orderIds})+"&fontSize="+fontSize, '_newtab');
            }else if(type == 'label-small'){
                window.open("{!! route('orders.print.label.small') !!}"+"?"+jQuery.param({orderIds})+"&fontSize="+fontSize, '_newtab');
            }
        });

        $('.btn-export-execl').on('click',function(e){
            var orderIds = [];
            $('input[name=checkbox]').each(function(i,element){
                if($(element).prop('checked')){
                    orderIds.push($(element).val())
                }
            });
            if(orderIds.length === 0){
                Swal.fire({
                    type: 'warning',
                    title: 'กรุณาเลือกเลขที่สั่งซื้อ'
                });
                return false;
            }
            window.open("{!! route('orders.export.excel') !!}"+"?"+jQuery.param({orderIds}), '_newtab');
        })

        $('.btn-export-flash-order').on('click',function(e){
            var orderIds = [];
            $('input[name=checkbox]').each(function(i,element){
                if($(element).prop('checked')){
                    orderIds.push($(element).val())
                }
            });
            if(orderIds.length === 0){
                Swal.fire({
                    type: 'warning',
                    title: 'กรุณาเลือกเลขที่สั่งซื้อ'
                });
                return false;
            }
            window.open("{!! route('orders.flash-export') !!}"+"?"+jQuery.param({orderIds}), '_newtab');
        })

    });

    require(['jquery', 'selectize'], function ($, selectize) {
        $('#select-beast').selectize({});
    });
  </script>
@endsection