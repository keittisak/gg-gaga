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
                <a href="{{ route('users.create') }}" class="btn btn-primary float-right"><i class="fe fe-plus mr-2"></i> เพิ่มผู้ใช้งาน</a>
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
                        <th>Username</th>
                        <th>ชื่อ</th>
                        <th>วันที่สร้าง</th>
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
    require(['jquery', 'datatables','sweetAlert'], function($, datatable,Swal) {
        $dt = $('.table');
        table = $dt.DataTable({
            processing: true,
            serverSide: true,
            ajax: "{!! route('users.data') !!}",
            columns: [
                { data: 'username', name: 'username' },
                { data: 'name', name: 'name' },
                { data: 'created_at', name: 'created_at' },
                { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center'}
            ],
            // paging:false,
            columnDefs : [

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
            var url = "{{ route('users.delete','_id') }}";
            url = url.replace('_id', id);
            Swal.fire({
                title: 'ลบผู้ใช้งาน?',
                text: "คุณจะไม่สามารถเปลี่ยนกลับได้!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ยืนยัน',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        dataType: "JSON",
                        data: {_token : '{{ csrf_token() }}', _method:'DELETE'},
                        beforeSend: function( xhr ) {
                            loader.init();
                        },
                        success: function (res) {
                            Swal.fire(
                            'Deleted!',
                            'ผู้ใช้งานของคุณถูกลบ',
                            'success'
                            );
                            loader.close();
                            table.draw();
                        },
                        error: function (jqxhr, status, error) {
                            Swal.fire({
                                type: 'error',
                                title: jqXHR.responseJSON.message
                            });
                            loader.close();
                        }
                    });
                }
            });

        });

    });

</script>
@endsection