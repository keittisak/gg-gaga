@extends('layouts.main')
@section('title',$title_eng)
@section('css')
    {{--  Css  --}}
    <style>
        .dataTables_wrapper .dataTables_filter {
            display: none;
        }
        .custom-switch-input:checked ~ .custom-switch-indicator {
            background: #5eba00;
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
    <div class="col-12  mb-3">
        <div class="row gutters-xs">
            <div class="col-12">
                <button class="btn btn-secondary btn-express-date w-9 mb-4" data-type="today">วันนี้</button>
                <button class="btn btn-secondary btn-express-date w-9 mb-4" data-type="yesterday">เมื่อวาน</button>
                <button class="btn btn-secondary btn-express-date w-9 mb-4" data-type="seven_day">7 วัน</button>
                <button class="btn btn-secondary btn-express-date w-9 mb-4" data-type="this_month">เดือนนี้</button>
                <button class="btn btn-secondary btn-express-date w-9 mb-4" data-type="last_mouth">เดือนที่แล้ว</button>
            </div>
        </div>
        <div class="row gutters-xs">
            <div class="col-12 col-md-5">
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-prepend" id="basic-addon1">
                            <span class="input-group-text">วันที่</span>
                        </span>
                        <input type="text" class="form-control datepicker" id="start-date" value="{{date('d/m/Y')}}">
                        <span class="input-group-prepend" id="basic-addon1">
                            <span class="input-group-text">ถึง</span>
                        </span>
                        <input type="text" class="form-control datepicker" id="end-date" value="{{date('d/m/Y')}}">
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-2">
                <div class="form-group">
                    <select class="form-control status select2" name="status">
                        <option value="">สถานะ : ทั้งหมด</option>
                        @foreach ($statusInfo as $status => $item)
                        <option value="{{$status}}">{{__('สถานะ :')}} {{$item['title']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-12 col-md-2">
                <div class="form-group">
                    <select class="form-control status select2" name="is_cod">
                        <option value="">วิธีชำระเงิน : ทั้งหมด</option>
                        <option value="n">{{__('วิธีชำระเงิน : โอน')}}</option>
                        <option value="y">{{__('วิธีชำระเงิน : เก็บเงินปลายทาง')}}</option>
                    </select>
                </div>
            </div>
            <div class="col-auto">
                <input type="text" class="form-control text-search" placeholder="ค้นหา ...">
            </div>
            <span class="col-auto">
                <button class="btn btn-primary btn-search" type="button"><i class="fe fe-search"></i></button>
            </span>
        </div>
    </div>
</div>
<div class="row prompt-front">
    <div class="col-12 ">
        <div class="card">
            <div class="table-responsive">
                <table class="table card-table table-vcenter table-striped text-nowrap">
                    <thead>
                        <th>วันที่</th>
                        <th class="w-15">ออเดอร์</th>
                        <th class="w-15">ยอดรวม</th>
                        <th class="w-15">ส่วนลด</th>
                        <th class="w-15">ค่าจัดส่ง</th>
                        <th class="w-15">สุทธิ</th>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
@section('js')
<script>
    var table;
    require(['jquery', 'datatables','moment','select2'], function($, datatable,moment,select2) {
        $('.select2').select2();
        $dt = $('.table');
        table = $dt.DataTable({
            processing: true,
            serverSide: true,
            ajax:{
                url:"{!! route('reports.daily-sales.data') !!}",
                data:function(d){
                    var startDate = $('#start-date').val();
                    var endDate = $('#end-date').val();
                    var status = $('select[name=status]').val();
                    var is_cod = $('select[name=is_cod]').val();
                    if(startDate != "" && endDate != ""){
                        d.start_date = startDate;
                        d.end_date = endDate;
                    }
                    if(status !== ""){
                        d.status = status;
                    }
                    if(is_cod !== ""){
                        d.is_cod = is_cod;
                    }
                }
            },
            columns: [
                { data: 'created_at', name: 'created_at' },
                { data: 'order_quantity', name: 'order_quantity', render: $.fn.dataTable.render.number(',', '.', 0, '') },
                { data: 'total_amount', name: 'total_amount', render: $.fn.dataTable.render.number(',', '.', 2, '') },
                { data: 'discount_amount', name: 'discount_amount', render: $.fn.dataTable.render.number(',', '.', 2, '') },
                { data: 'shipping_fee', name: 'shipping_fee', render: $.fn.dataTable.render.number(',', '.', 2, '') },
                { data: 'net_total_amount', name: 'net_total_amount', render: $.fn.dataTable.render.number(',', '.', 2, '') },
            ],
            paging:false,
            columnDefs : [
                {
                    targets:[1,2,3,4,5],
                    class:'text-right'
                },
                {
                    targets:0,
                    render: function (data, type, full, meta){
                        return moment(data).format('DD/MM/YYYY');
                    }
                }
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
        });
        $('.btn-search').on('click',function(e){
            loader.init();
            var text = $('.text-search').val();
            table.search(text).draw();
        });

        $('.btn-express-date').on('click',function(e){
            var type = $(this).data('type');
            var startDate;
            var endDate;
            if(type == 'today'){
                startDate = moment();
                endDate = moment();
            }else if(type == 'yesterday'){
                startDate = moment().subtract(1, 'days');
                endDate = moment().subtract(1, 'days');
            }else if(type == 'this_month'){
                startDate = moment().startOf('month');
                endDate = moment().endOf("month");
            }else if(type == 'last_mouth'){
                startDate = moment().subtract(1, 'months').startOf('month');
                endDate = moment().subtract(1, 'months').endOf("month");
            }else if(type == 'seven_day'){
                startDate = moment().subtract(7, 'days');
                endDate = moment();
            }
            $('#start-date').val(startDate.format('DD/MM/YYYY'));
            $('#end-date').val(endDate.format('DD/MM/YYYY'));
            // $('.btn-search').click();
        });

        $('#start-date').datepicker({
            autoclose:true,
            format:'dd/mm/yyyy',
            language:'th',
            setDate: new Date()
        });

        $('#end-date').datepicker({
            autoclose:true,
            format:'dd/mm/yyyy',
            language:'th',
            setDate: new Date()
        });


    });

</script>
@endsection