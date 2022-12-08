@extends('layouts.main')
@section('title',$title_en)
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
            <div class="col-6">
                <input type="text" class="form-control text-search" placeholder="ค้นหา ...">
            </div>
            <span class="col-auto">
                <button class="btn btn-secondary btn-search" type="button"><i class="fe fe-search"></i></button>
            </span>
            <span class="col">
                <a href="{{ route('products.create') }}" class="btn btn-primary float-right"><i class="fe fe-plus mr-2"></i> เพิ่มสินค้า</a>
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
                        <th>ภาพ</th>
                        <th>รายละเอียด</th>
                        <th>ราคา</th>
                        <th>ปรเภท</th>
                        <th class="w-20">สถานะ</th>
                        <th></th>
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
    require(['jquery', 'datatables'], function($, datatable) {
        $dt = $('.table');
        table = $dt.DataTable({
            processing: true,
            serverSide: true,
            ajax: "{!! route('products.data') !!}",
            columns: [
                { data: 'name', name: 'name' },
                { data: 'image', name: 'image' },
                { data: 'description', name: 'description' },
                { data: 'price', name: 'price' },
                { data: 'type', name: 'type' },
                { data: 'status', name: 'status' },
                { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center'}
            ],
            // paging:false,
            columnDefs : [
                {
                    targets:1,
                    render: function (data, type, full, meta){
                        if(data){
                            return `<a href="${data}" target="_blank">
                                <span class="avatar" style="background-image: url(${data})"></span>
                            </a>`;
                        }
                        return "-";
                    }
                },
                {
                    targets:5,
                    render: function (data, type, full, meta){
                        var element = `<label class="custom-switch">
                          <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input switch-status" data-id="${full.id}" ${(data=='active')?'checked':''}>
                          <span class="custom-switch-indicator"></span>
                          <span class="custom-switch-description">${data}</span>
                        </label>`;
                        return element;
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
            },
        });
        $('.btn-search').on('click',function(e){
            var text = $('.text-search').val();
            table.search(text).draw();
        });

        $(document).on('click', '.btnDelete', function (e) {
            var id =$(this).data('id')
            var url = "{{ route('products.delete','_id') }}";
            url = url.replace('_id', id);
            $.ajax({
                url: url,
                type: 'DELETE',
                dataType: "JSON",
                data: {_token : '{{ csrf_token() }}', _method:'DELETE'},
                beforeSend: function( xhr ) {
                    
                },
                success: function (res) {
                },
                error: function (request, status, error) {
                }
            });
        });

        $(document).on('change','.switch-status',function(e){
            var elementDescription = $(this).parent().find('.custom-switch-description');
            var id = $(this).data('id');
            var status = 'inactive';
            if($(this).is(':checked')){
                status = 'active';
            }
            var url = "{!! route('products.status','__id') !!}";
            url = url.replace('__id',id);
            $.ajax({
                url:url,
                type: 'POST',
                dataType: "JSON",
                data: {_token : '{{ csrf_token() }}', _method:'PATCH', status:status},
                beforeSend: function( xhr ) {
                    loader.init();
                },
            }).done(function(data){
                elementDescription.html(status)
                loader.close();
            }).fail(function( jqxhr, textStatus ) {
                loader.close();
                Swal.fire({
                    type: 'error',
                    title: jqXHR.responseJSON.message
                });
            });
        })

    });

</script>
@endsection