<!DOCTYPE html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css?v={{config('app.css_version')}}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,300i,400,400i,500,500i,600,600i,700,700i&amp;subset=latin-ext">
    <link href="https://fonts.googleapis.com/css?family=Prompt&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Kanit&display=swap" rel="stylesheet">
    <script src="{{ asset('assets/js/require.min.js') }}?v={{config('app.js_version')}}"></script>
    <script>
      requirejs.config({
          baseUrl: '{{ asset("") }}'
      });
    </script>
    <!-- Dashboard Core -->
    <link href="{{ asset('assets/css/dashboard.css') }}?v={{config('app.css_version')}}" rel="stylesheet" />
    <link href="{{ asset('assets/css/custom.css') }}?v={{config('app.css_version')}}" rel="stylesheet" />
    <script src="{{ asset('assets/js/dashboard.js') }}?v={{config('app.js_version')}}"></script>
    <!-- c3.js Charts Plugin -->
    <link href="{{ asset('assets/plugins/charts-c3/plugin.css') }}?v={{config('app.css_version')}}" rel="stylesheet" />
    <script src="{{ asset('assets/plugins/charts-c3/plugin.js') }}?v={{config('app.js_version')}}"></script>
    <!-- Google Maps Plugin -->
    <link href="{{ asset('assets/plugins/maps-google/plugin.css') }}?v={{config('app.css_version')}}" rel="stylesheet" />
    <script src="{{ asset('assets/plugins/maps-google/plugin.js') }}?v={{config('app.js_version')}}"></script>
    <!-- Input Mask Plugin -->
    <script src="{{ asset('assets/plugins/input-mask/plugin.js') }}?v={{config('app.js_version')}}"></script>
    <!-- Datatables Plugin -->
    <script src="{{ asset('assets/plugins/datatables/plugin.js') }}?v={{config('app.js_version')}}"></script>
    <script src="{{ asset('assets/plugins/jqueryForm/plugin.js') }}?v={{config('app.js_version')}}"></script>

    <link rel="stylesheet" href="{{ asset('assets/plugins/sweet-alert2/sweet-alert2.min.css') }}?v={{config('app.css_version')}}">
    <script src="{{ asset('assets/plugins/sweet-alert2/plugin.js') }}?v={{config('app.js_version')}}"></script>
    <!-- Select2 Plugin -->
    <link href="{{ asset('assets/plugins/select2/select2.min.css') }}?v={{config('app.css_version')}}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/select2-bootstrap4/select2-bootstrap4.min.css') }}?v={{config('app.css_version')}}" rel="stylesheet" />
    <script src="{{ asset('assets/plugins/select2/plugin.js') }}?v={{config('app.js_version')}}"></script>

    <!-- Date Picker -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/datepicker/datepicker3.css') }}?v={{config('app.css_version')}}">
    <script src="{{ asset('assets/plugins/datepicker/plugin.js') }}?v={{config('app.js_version')}}"></script>
    <!-- Moment -->
    <script src="{{ asset('assets/plugins/moment/plugin.js') }}?v={{config('app.js_version')}}"></script>
    <!-- Clipboard -->
    <script src="{{ asset('assets/plugins/clipboard/plugin.js') }}?v={{config('app.js_version')}}"></script>
    @yield('css')
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
        z-index:1048;
    }
    .loader-center{
        position: fixed;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        z-index:1049;
        
    }
    .select2-container--default .select2-selection--single{
        border: 1px solid rgba(0, 40, 100, 0.12);
        border-radius: 3px;
        height: 2.375rem;
        padding: 0.375rem 0.75rem;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        top:7px;
    }
    .select2-container--default .select2-search--dropdown .select2-search__field{
        border: 1px solid rgba(0, 40, 100, 0.12);
        border-radius: 2px;
    }
    .select2-results {
        font-family: 'Kanit', 'Prompt', 'Athiti', sans-serif;
    }
</style>
<body>
    <div class="page d-block">
        <div class="page-main">
            @include('layouts.header')
            @include('layouts.menu')
            <div class="my-3 my-md-5">
                <div class="container">
                    @yield('content')
                </div>
            </div>
        </div>
        {{-- @include('layouts.footer') --}}
    </div>
</body>
<script>
    require(['input-mask']);
    require(['jquery','datepicker'], function($,datepicker) {
        $(document).on('keypress keyup blur','.number-only',function(event){
            $(this).val($(this).val().replace(/\D/g, ""));
            if ((event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
        });
            
        function pricceFormat(text) {
            return text.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
        }
        function readURL(input, element) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $(element).attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        pricceFormat = {
            init: function(data){
                return text.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
            }
        }
        loader = {
            init: function(e){
                let loading = `
                <div class="loader loader-center"></div>
                <div class="loader-page"></div>
            `;
            $('body').append(loading)
            },
            close: function(e){
                $('.loader').remove();
                $('.loader-page').remove();
            }
        }

        utilities = {
            numberFormat:function(n,digit=2){
                if (n === '') {
                    return '';
                }
                else if (n == 0 || isNaN(n) || n == null || n == undefined) {
                    return (digit == undefined) ? '0' : parseFloat('0').toFixed(digit);
                }

                if (digit == undefined) {
                    return (parseFloat(n) + '').replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
                }
                return parseFloat(n).toFixed(digit).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
            },
        }

            $.fn.datepicker.dates['th'] = {
                days: ["อาทิตย์", "จันทร์", "อังคาร", "พุธ", "พฤหัส", "ศุกร์", "เสาร์", "อาทิตย์"],
                daysShort: ["อา", "จ", "อ", "พ", "พฤ", "ศ", "ส", "อา"],
                daysMin: ["อา", "จ", "อ", "พ", "พฤ", "ศ", "ส", "อา"],
                months: ["มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"],
                monthsShort: ["ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค."],
                today: "วันนี้"
            };
        

    });
      
</script>
@yield('js')
</html>