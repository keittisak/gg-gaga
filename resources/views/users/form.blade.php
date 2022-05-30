@extends('layouts.main')
@section('title',$title_en)
@section('css')
    {{--  Css  --}}
@endsection
@section('content')
<div class="page-header">
    <h1 class="page-title prompt-front">
        {{$title_th}}
    </h1>
</div>
<div class="row">
    <div class="col-md-8 col-12">
        <form class="card prompt-front" action="{{ ($action == 'create')?route('users.store'):route('users.update',$user->id)}}" method="POST">
            @csrf
            @if($action == 'update')<input type="hidden" name="_method" value="PUT">@endif
            <div class="card-body">
                <div class="dimmer">
                <div class="loader"></div>
                <div class="dimmer-content">
                    <div class="form-group">
                        <label class="form-label">Username <span class="form-required">*</span></label>
                        <input type="text" class="form-control" name="username" id="username" value="{{$user->username}}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">รหัสผ่านใหม่ <span class="form-required">*</span></label>
                        <input type="password" class="form-control" name="password" id="password" value="">
                    </div>
                    <div class="form-group">
                        <label class="form-label">ยืนรหัสผ่านใหม่ <span class="form-required">*</span></label>
                        <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" value="">
                    </div>
                    <div class="form-group">
                        <label class="form-label">ชื่อ <span class="form-required">*</span></label>
                        <input type="text" class="form-control" name="name" id="name" value="{{$user->name}}">
                    </div>
                
                </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('users.index') }}" class="btn btn-pill btn-danger float-left">ยกเลิก</a>
                <button type="submit" class="btn btn-pill btn-primary float-right">บันทึก</button>
            </div>
        </form>
    </div>
</div>

@endsection
@section('js')
<script>
    require(['sweetAlert','jqueryForm','jquery'], function(Swal,form,$) {
        $('form').ajaxForm({
            dataType: 'json',
            beforeSubmit: function (arr, $form, options) {
                loader.init();
            },
            success: function (res) {
                Swal.fire({
                    type: "success",
                    title: "บันทึกข้อมูลเรียบร้อย", 
                }).then(function(){
                    @if($action != "update")
                    window.location.replace('{{ route('users.index') }}');
                    @else
                    loader.close();
                    @endif
                });
                
            },
            error: function (jqXHR, status, options, $form) {
                // $('.card-body').find('.dimmer').removeClass('active');
                // $('button[type=submit]').prop('disabled',false);
                loader.close();
                if(jqXHR.status === 422){
                    var errorMessage = ``;
                        var i=0;
                        $.map(jqXHR.responseJSON.errors,function(v,k){
                            if (i === 1) { return; }
                            errorMessage = v[0];
                            i++;
                        });
                    Swal.fire({
                        type: 'error',
                        title: 'ข้อมูลไม่ถูกต้อง',
                        text: errorMessage
                    });
                }else{
                    Swal.fire({
                        type: 'error',
                        title: jqXHR.responseJSON.message
                    });
                }
                
            }
        });
    });
</script>
@endsection