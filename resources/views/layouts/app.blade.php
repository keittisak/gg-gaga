@extends('layouts.main')
@section('title','Layouts')
@section('css')
    {{--  Css  --}}
@endsection
@section('content')
<div class="page-header">
    <h1 class="page-title prompt-front">
        คำสั่งซื้อ
    </h1>
</div>
<div class="row">
    <div class="col-12 pb-5 d-none d-md-block prompt-front">
        <a href="#" class="btn btn-secondary btn-lg  pr-0 pl-0" style="width: 72px">
            <div class="h5 m-0 ">21</div>
            <small class="mb-1 " style="font-size:80%">ทั้งหมด</small>
        </a>
        <div class="btn-group mr-3 ml-3 " role="group" aria-label="Basic example">
            <a href="#?scope=draft" class="btn btn-secondary btn-lg  pr-0 pl-0 " style="width: 72px">
                <div class="h5 m-0 ">17</div>
                <small class="mb-1 ">ร่าง</small>
            </a>
            <a href="#?scope=unpaid" class="btn btn-secondary btn-lg  pr-0 pl-0 " style="width: 72px">
                <div class="h5 m-0 ">1</div>
                <small class="mb-1 ">ยังไม่จ่าย</small>
            </a>
            <a href="#?scope=transferred" class="btn btn-secondary btn-lg  pr-0 pl-0 " style="width: 72px">
                <div class="h5 m-0 ">3</div>
                <small class="mb-1 ">โอนแล้ว</small>
            </a>
            <a href="#?scope=packing" class="btn btn-secondary btn-lg  pr-0 pl-0 " style="width: 72px">
                <div class="h5 m-0 ">0</div>
                <small class="mb-1 ">กำลังแพ็ค</small>
            </a>
            <a href="#?scope=paid" class="btn btn-secondary btn-lg  pr-0 pl-0 " style="width: 72px">
                <div class="h5 m-0 ">0</div>
                <small class="mb-1 ">เตรียมส่ง</small>
            </a>
            <a href="#?scope=shipped" class="btn btn-secondary btn-lg  pr-0 pl-0 " style="width: 72px">
                <div class="h5 m-0 ">0</div>
                <small class="mb-1 ">ส่งแล้ว</small>
            </a>
        </div>
        <a href="#?scope=voided" class="btn btn-secondary btn-lg  pr-0 pl-0 " style="width: 72px">
            <div class="h5 m-0 ">0</div>
                <small class="mb-1 ">ยกเลิก</small>
        </a>
    </div>
</div>
<div class="row d-none d-md-flex prompt-front">
    <div class="col-12 col-md-12 col-lg-8 mb-5 px-0">
        <div class="row gutters-xs">
            <div class="col">
                <input type="text" class="form-control" placeholder="ค้นหา ...">
            </div>
            <span class="col-auto">
                <button class="btn btn-secondary" type="button"><i class="fe fe-search"></i></button>
            </span>
            <span class="col-auto">
                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#advancedSearchModal"><i class="fe fe-sliders"></i> ค้นหาขั้นสูง</button>
            </span>
        </div>
    </div>
    <div class="col-12 col-md-12 col-lg-4 text-right mb-5 prompt-front">
        <div class="dropdown">
            <button data-toggle="dropdown" type="button" class="btn btn-primary dropdown-toggle" aria-expanded="true">เปลี่ยนสถานะ (1)</button>
            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow" x-placement="bottom-end" style="position: absolute; transform: translate3d(-56px, 32px, 0px); top: 0px; left: 0px; will-change: transform;">
              <a class="dropdown-item" href="#">ร่าง</a>
              <a class="dropdown-item" href="#">ยังไม่จ่าย</a>
            </div>
        </div>
        <div class="dropdown">
            <button data-toggle="dropdown" type="button" class="btn btn-primary dropdown-toggle" aria-expanded="true">พิมพ์ (1)</button>
            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow" x-placement="bottom-end" style="position: absolute; transform: translate3d(-56px, 32px, 0px); top: 0px; left: 0px; will-change: transform;">
              <a class="dropdown-item" href="#">ใบปะหน้ากล่อง</a>
              <a class="dropdown-item" href="#">รายการ</a>
            </div>
        </div>
    </div>
</div>

<div class="row d-md-none prompt-front">
    <div class="col-12 px-0">
        {{-- <button type="button" href="#" class="btn btn-primary btn-block mb-3">สถานะ: โอนแล้ว <span class="tag tag-success">100</span></button> --}}
        <div class="dropdown w-100 mb-3">
            <button type="button" class="btn btn-outline-primary btn-block dropdown-toggle" data-toggle="dropdown">
                ทั้งหมด <span class="tag tag-success">100</span>
            </button>
            <div class="dropdown-menu dropdown-menu-left dropdown-menu-arrow" >
                <a class="dropdown-item">ร่าง <span class="tag tag-success">0</span></a>
                <a class="dropdown-item">ยังไม่จ่าย <span class="tag tag-success">0</span></a>
            </div>
        </div>
        <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="ค้นหา ...">
            <span class="input-group-append">
                <button class="btn btn-secondary" type="button"><i class="fe fe-search"></i></button>
            </span>
        </div>
        <button type="button" class="btn btn-outline-primary btn-block mb-3" data-toggle="modal" data-target="#advancedSearchModal"><i class="fe fe-sliders"></i> ค้นหาขั้นสูง</button>
        
    </div>
</div>

<div class="row d-md-none mb-5 prompt-front">
    <div class="col-6 pl-0">
        <div class="dropdown w-100">
            <button data-toggle="dropdown" type="button" class="btn btn-outline-primary btn-block dropdown-toggle" aria-expanded="true">เปลี่ยนสถานะ (1)</button>
            <div class="dropdown-menu dropdown-menu-left dropdown-menu-arrow" x-placement="bottom-end" style="position: absolute; transform: translate3d(-56px, 32px, 0px); top: 0px; left: 0px; will-change: transform;">
              <a class="dropdown-item" href="#">ร่าง</a>
              <a class="dropdown-item" href="#">ยังไม่จ่าย</a>
            </div>
        </div>
    </div>
    <div class="col-6 pr-0">
        <div class="dropdown w-100">
            <button data-toggle="dropdown" type="button" class="btn btn-outline-primary btn-block dropdown-toggle" aria-expanded="true">พิมพ์ (1)</button>
            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow" x-placement="bottom-end" style="position: absolute; transform: translate3d(-56px, 32px, 0px); top: 0px; left: 0px; will-change: transform;">
              <a class="dropdown-item" href="#">ใบปะหน้ากล่อง</a>
              <a class="dropdown-item" href="#">รายการ</a>
            </div>
        </div>
    </div>
</div>

<div class="row row-cards row-deck">
    <div class="col-12">
        <div class="card">
            <div class="table-responsive">
                <table class="table card-table table-vcenter text-nowrap datatable">
                    <thead>
                    <tr>
                        <th class="w-1">
                            <label class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="example-checkbox2" value="option2">
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
                    </tr>
                    </thead>
                    <tbody>
                    @for($i=1;$i <= 4;$i++)
                    <tr>
                        <td>
                            <label class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="example-checkbox2" value="option2">
                                <span class="custom-control-label"></span>
                        </label>
                        </td>
                        <td><a href="invoice.html" class="text-inherit">OD200217000{{$i}}</a></td>
                        <td><i class="fe fe-calendar"></i> 17-02-2020</td>
                        <td><span class="h1 text-green"><i class="fab fa-line"></i></span></td>
                        <td>
                            สมหมาย ป้องกันภัย
                        </td>
                        <td>
                            1,200.00
                        </td>
                        <td>
                        <span class="status-icon bg-success"></span> โอนแล้ว
                        </td>
                        <td><i class="fe fe-calendar"></i> 17-02-2020 17:53</td>
                    </tr>
                    @endfor
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="advancedSearchModal" tabindex="-1" role="dialog" aria-labelledby="advancedSearchModalLabel" aria-hidden="true">
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
                        <input type="text" class="form-control" name="example-text-input">
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label class="form-label">ผู้สั่งสินค้า</label>
                        <input type="text" class="form-control" name="example-text-input">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label class="form-label">วันที่สั่งซื้อ</label>
                        <input type="text" class="form-control" name="example-text-input">
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label class="form-label">เวลาโอน</label>
                        <input type="text" class="form-control" name="example-text-input">
                    </div>
                </div>
            </div>
            
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
            <button type="button" class="btn btn-primary">ค้นหา</button>
        </div>
        </div>
    </div>
</div>

@endsection
@section('js')
<script>
    require(['datatables', 'jquery'], function(datatable, $) {
            $('.datatable').DataTable({
                paging:false,
                searching:false,
            });
          });

    require(['jquery', 'selectize'], function ($, selectize) {
        $('#select-beast').selectize({});
    });
  </script>
@endsection