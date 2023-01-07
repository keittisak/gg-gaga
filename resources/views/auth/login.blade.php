<!DOCTYPE html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ __('Login') }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,300i,400,400i,500,500i,600,600i,700,700i&amp;subset=latin-ext">
    <link href="https://fonts.googleapis.com/css?family=Prompt&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Kanit&display=swap" rel="stylesheet">
    <script src="{{ asset('assets/js/require.min.js') }}"></script>
    <script>
      requirejs.config({
          baseUrl: '{{ asset("") }}'
      });
    </script>
    <!-- Dashboard Core -->
    <link href="{{ asset('assets/css/dashboard.css') }}?v=5" rel="stylesheet" />
    <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet" />
    <script src="{{ asset('assets/js/dashboard.js') }}"></script>
</head>
<style>
    .loader-page{
        position: fixed;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgb(255, 255, 255);
        opacity: 0.5;
        z-index:99998;
    }
    .loader-center{
        position: fixed;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        z-index:99999;
        
    }
</style>
<body class="prompt-front">
    <div class="page">
        <div class="page-single">
            <div class="container">
            <div class="row">
                <div class="col col-login mx-auto">
                <div class="text-center mb-6">
                    <h1 class="h1">Back Office</h1>
                </div>
                <form class="card" action="{{ route('auth.login') }}" method="post">
                    @csrf
                    <div class="card-body p-6">
                    <div class="card-title">ลงชื่อเข้าใช้บัญชีของคุณ</div>
                    <div class="form-group">
                        <label class="form-label">Username</label>
                        <input type="text" class="form-control" placeholder="กรอกชื่อผู้ใช้" value="{{old('username')}}" name="username">
                        @if($errors->any())<div class="invalid-feedback d-block">*ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง</div>@endif
                    </div>
                    <div class="form-group">
                        <label class="form-label">
                        Password
                        {{-- <a href="./forgot-password.html" class="float-right small">I forgot password</a> --}}
                        </label>
                        <input type="password" class="form-control" id="exampleInputPassword1" placeholder="รหัสผ่าน" value="" name="password">
                    </div>    
                    {{-- <div class="form-group">
                        <label class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input">
                        <span class="custom-control-label">Remember me</span>
                        </label>
                    </div> --}}
                    <div class="form-footer">
                        <button type="submit" class="btn btn-primary btn-block">เข้าสู่ระบบ</button>
                    </div>
                    </div>
                </form>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>
</body>
<script>
</script>

</html>