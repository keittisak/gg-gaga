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
            <div class="col-10 col-md-4">
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
                        <th>ชื่อสินค้า</th>
                        <th class="w-15">นำเข้า</th>
                        <th class="w-15">นำออก</th>
                        <th class="w-15">คืน</th>
                        <th class="w-15">ส่งออก</th>
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
    require(['jquery', 'datatables','moment'], function($, datatable,moment) {
        $dt = $('.table');
        table = $dt.DataTable({
            processing: true,
            serverSide: true,
            ajax:{
                url:"{!! route('stock.movement.data') !!}",
                data:function(d){
                    var startDate = $('#start-date').val();
                    var endDate = $('#end-date').val();
                    if(startDate != "" && endDate != ""){
                        d.start_date = startDate;
                        d.end_date = endDate;
                    }
                }
            },
            columns: [
                { data: 'full_name', name: 'full_name' },
                { data: 'fill_in', name: 'fill_in', render: $.fn.dataTable.render.number(',', '.', 0, '') },
                { data: 'take_out', name: 'take_out', render: $.fn.dataTable.render.number(',', '.', 0, '') },
                { data: 'get_back', name: 'get_back', render: $.fn.dataTable.render.number(',', '.', 0, '') },
                { data: 'release', name: 'release', render: $.fn.dataTable.render.number(',', '.', 0, '') },
            ],
            paging:false,
            columnDefs : [
                {
                    targets:[1,2,3,4],
                    class:'text-right'
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
        })


    });

</script>
@endsection