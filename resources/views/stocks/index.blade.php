@extends('layouts.main')
@section('title','Stocks')
@section('css')
    {{--  Css  --}}
@endsection
@section('content')
<div class="page-header">
    <h1 class="page-title prompt-front">
        คลังสินค้า
    </h1>
</div>
<div class="row prompt-front">
    <div class="col-12 mb-3">
        <div class="row gutters-xs">
            <div class="col-6">
                <input type="text" class="form-control text-search" placeholder="ค้นหา ...">
            </div>
            <span class="col-auto">
                <button class="btn btn-secondary btn-search" type="button"><i class="fe fe-search"></i></button>
            </span>
        </div>
    </div>
</div>
<div class="row prompt-front">
    <div class="col-12 ">
        <div class="card">
            <div class="table-responsive">
                <table class="table card-table table-vcenter text-nowrap" id="table">
                    <thead>
                        <tr>
                            <th class="w-40">สินค้า</th>
                            <th class="w-5 text-right">ราคา</th>
                            <th class="w-5 text-right">สต็อก</th>
                            <th class="w-5 text-right">จอง</th>
                            <th class="w-5 text-right">คงเหลือ</th>
                            <th class="w-1"></th>
                        </tr>
                    </thead>
                    {{-- <tbody>
                        @foreach($products as $key => $product)
                            @foreach($product->skus as $index => $sku)
                            <tr>
                                <td>{{$product->name.' '.$sku->name}}</td>
                                <td class="text-right">{{number_format($sku->price,2)}}</td>
                                <td class="text-right">{{number_format($sku->stock->available)}}</td>
                                <td class="text-right">{{number_format($sku->stock->draft)}}</td>
                                <td class="text-right">{{number_format($sku->stock->onhand)}}</td>
                                <td class="text-center"><button type="button" class="btn btn-link btn-sm btn-edit" data-sku-name="{{$product->name.' '.$sku->name}}" data-stock="{{json_encode($sku->stock)}}"><i class="far fa-edit"></i></button></td>
                            </tr>
                            @endforeach
                        @endforeach
                    </tbody> --}}
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal fade prompt-front" id="editModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">#</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="zmdi zmdi-close"></span>
                </button>
            </div>
            <div class="modal-body" style="height:400px">
                <div class="row">
                    <div class="col-12">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>สต๊อค</th>
                                    <th>จอง</th>
                                    <th>คงเหลือ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td id="text-move-available">1</td>
                                    <td id="text-move-draft">1</td>
                                    <td id="text-move-onhand">1</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-12 mt-2">
                        <div class="row">
                            <div class="col-6">
                                <button type="button" class="btn btn-outline-primary btn-block btn-action" data-type="add" data-available="1" data-id=""><i class="fas fa-plus mr-2"></i> เพิ่มจำนวน</button>
                            </div>
                            <div class="col-6">
                                <button type="button" class="btn btn-outline-primary btn-block btn-action" data-type="set" data-available="1" data-id=""><i class="far fa-edit mr-2"></i> ปรับจำนวน</button>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="col-12">
                    <form id="form-stock-update" style="display: none">
                        <h5 class="text-cyan text-small" id="form-stock-update-title">ปรับจำนวน</h5>
                        <input type="hidden" name="id" readonly value="">
                        <input type="hidden" name="sku_id" readonly value="">
                        <input type="hidden" name="action" readonly value="set">
                        <input type="hidden" name="available" readonly value="0">
                        <div class="row mb-2">
                            <label class="col-3 col-form-label">จำนวน (ชิ้น)</label>
                            <div class="col-5 pl-0">
                                <input type="text" class="form-control" name="quantity" value="">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-3 col-form-label">หมายเหตุ</label>
                            <div class="col-9 pl-0">
                                <input type="text" class="form-control" name="remark" value="">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 text-right">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
                                <button type="button" class="btn btn-primary btn-submit">บันทึก</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script>
    var table;
    require(['datatables', 'jquery'], function(datatable, $) {
            function pricceFormat(text) {
                return text.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
            }
            $dt = $('#table');
            table = $dt.DataTable({
                processing: true,
                serverSide: true,
                ajax:{
                    url:"{!! route('stocks.data') !!}",
                },
                columns: [
                    { data: 'sku.full_name', name: 'sku.full_name' },
                    { data: 'sku.price', name: 'sku.price',className:'text-right',},
                    { data: 'available', name: 'available',className:'text-right', },
                    { data: 'draft', name: 'draft',className:'text-right', },
                    { data: 'onhand', name: 'onhand',className:'text-right', },
                    { data: 'id', name: 'id',className:'text-center', },
                ],
                // paging:false,
                // searching:false,
                columnDefs : [
                    {
                        targets:1,
                        render: function (data, type, full, meta){
                            return pricceFormat(data)
                        }
                    },
                    {
                        targets:2,
                        render: function (data, type, full, meta){
                            return pricceFormat(data)
                        }
                    },
                    {
                        targets:3,
                        render: function (data, type, full, meta){
                            return pricceFormat(data)
                        }
                    },
                    {
                        targets:4,
                        render: function (data, type, full, meta){
                            return pricceFormat(data)
                        }
                    },
                    {
                        targets:1,
                        render: function (data, type, full, meta){
                            return pricceFormat(data)
                        }
                    },
                    {
                        targets:5,
                        render: function (data, type, full, meta){
                            return `<button type="button" class="btn btn-secondary btn-sm btn-edit" data-stock='${JSON.stringify(full)}'><i class="far fa-edit"></i></button>`;
                        }
                    }
                ],
                initComplete: function(){
                    $('.dataTables_filter').remove();
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

            $(document).on('click','.btn-edit',function(e){
                var stock = $(this).data('stock');
                $('.modal-title').html('#'+stock.sku.full_name);
                $(this).prop('disabled',false);
                $('.btn-action').attr('data-available', stock.available);
                $('.btn-action').attr('data-id', stock.id);
                $('.btn-action').attr('data-sku-id', stock.sku_id);
                $('#text-move-available').text(stock.available);
                $('#text-move-draft').text(stock.draft);
                $('#text-move-onhand').text(stock.onhand);
                $('#editModal').modal('show');

            });

            $('.btn-action').on('click',function(e){
                console.log()
                var id = $(this).attr('data-id');
                var sku_id = $(this).attr('data-sku-id');
                var type = $(this).attr('data-type');
                var available = $(this).attr('data-available');
                var element = ``;
                if(type == 'set'){
                    element = `${available} <i class="fas fa-angle-right"></i> <span class="tag tag-blue">${available}</span>`;
                }else{
                    element = `${available} + 0 <i class="fas fa-angle-right"></i> <span class="tag tag-blue">${available}</span>`;
                }
                console.log(id);
                $('#form-stock-update-title').text($(this).text());
                $('#form-stock-update').find('input[name=id]').val(id);
                $('#form-stock-update').find('input[name=sku_id]').val(sku_id);
                $('#form-stock-update').find('input[name=action]').val(type);
                $('#form-stock-update').find('input[name=quantity]').val(0);
                $('#form-stock-update').find('input[name=available]').val(available);
                $('#text-move-available').html(element);
                $('#form-stock-update').show();
            });

            $('#form-stock-update input[name=quantity]').on('keyup', function(e){
                var type = $('#form-stock-update').find('input[name=action]').val();
                var available = $('#form-stock-update').find('input[name=available]').val();
                var quantity = $(this).val();
                
                var element = ``;
                if(type == 'set'){
                    element = `${available} <i class="fas fa-angle-right"></i> <span class="tag tag-blue">${parseInt(quantity)}</span>`;
                }else{
                    element = `${available} + ${quantity} <i class="fas fa-angle-right"></i> <span class="tag tag-blue">${parseInt(available)+parseInt(quantity)}</span>`;
                }
                $('#text-move-available').html(element);
            });

            $('.btn-submit').on('click',function(e){
                // $(this).prop('disabled',true);
                var id = $('#form-stock-update').find('input[name=id]').val();
                var sku_id = $('#form-stock-update').find('input[name=sku_id]').val();
                var action = $('#form-stock-update').find('input[name=action]').val();
                var quantity = $('#form-stock-update').find('input[name=quantity]').val();
                var remark = $('#form-stock-update').find('input[name=remark]').val();
                var url = "{{ route('stocks.update','_id') }}";
                url =url.replace('_id', id);
                $.ajax({
                    url: url,
                    method:'post',
                    dataType:'json',
                    data: {_token:'{{ csrf_token() }}', _method:'put', id:id, sku_id:sku_id, action:action, quantity:quantity, remark:remark },
                    beforeSend: function() {
                        loader.init();
                        $('#editModal').modal('hide');
                    }
                }).done(function(data, textStatus, jqXHR) {
                    $('#form-stock-update').hide();
                    // location.reload();
                    table.draw();
                    loader.close();
                }).fail(function(jqXHR, textStatus) {
                    // $(this).prop('disabled',false);
                    loader.close();
                    Swal.fire({
                        type: 'error',
                        title: jqXHR.responseJSON.message
                    });
                })
            });
    });
  </script>
@endsection