<!DOCTYPE html>
<html class="no-js css-menubar" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="Encloud">
    <meta name="author" content="Encloud">
    <meta http-equiv="cache-control" content="no-cache" />
    <meta http-equiv="expires" content="0" />
    <meta http-equiv="pragma" content="no-cache" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport"
        content="user-scalable=yes, initial-scale=1, maximum-scale=2, minimum-scale=0.5, width=device-width, height=device-height, target-densitydpi=device-dpi" />
    <title>Encloud</title>

    <link rel="apple-touch-icon" href="{{ my_asset('assets/images/favicon.png')}}">
    <link rel="shortcut icon" href="{{ my_asset('assets/images/favicon.png')}}">
    <link rel="shortcut icon" href="{{ my_asset('assets/images/favicon.png')}}">
    <link rel="icon" href="{{ my_asset('assets/images/favicon.png')}}" type="image">
    <!-- GET SERVER NAME -->
    <?php
    /*  $ASSET_PATH = !str_contains($_SERVER['HTTP_HOST'], '127.0.0.1') ? '/public': '';
        echo $ASSET_PATH; */
    ?>
    <!--START: STYLESHEET -->
    <!-- GOOGLE FONTS -->
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Manrope:wght@200;300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@200;300;400;500;600&display=swap"
        rel="stylesheet">
    <!-- <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@200;300;400;700;900;display=swap" rel="stylesheet"> -->

    <!-- ICON CSS -->
    <link rel="stylesheet" href="{{ my_asset('assets/fonts/tabler-icons.min.css') }}">
    <link rel="stylesheet" href="{{ my_asset('assets/fonts/feather.css') }}">
    <link rel="stylesheet" href="{{ my_asset('assets/fonts/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ my_asset('assets/fonts/material.css') }}">
    <link rel="stylesheet" href="{{ my_asset('/assets/css/materialdesignicons.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- BOOTSTRAP -->
    <link rel="stylesheet" href="{{ my_asset('/assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ my_asset('/assets/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ my_asset('/assets/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}">

    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

    <!--  STYLES -->
    <link rel="stylesheet" type="text/css" href="{{ my_asset('/assets/css/custom-styles.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ my_asset('/assets/css/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ my_asset('/assets/css/custom.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ my_asset('/assets/css/customizer.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ my_asset('/assets/css/toastr.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ my_asset('/assets/css/sweet_alert.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ my_asset('/assets/css/common.css') }}">
    <!-- END: STYLESHEETS -->
    <link rel="stylesheet" href="{{ my_asset('/assets/css/jsRapCalendar.css') }}">


    <!--  STYLES -->

    <!--START: JAVASCRIPT -->

    <script src="{{ my_asset('/assets/js/jquery-3.6.0.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>

    <script src="{{ my_asset('/assets/js/bootstrap.5.0.js') }}"></script>
    
    <script src="{{ my_asset('/assets/js/picker.js') }}"></script>

    <link rel="stylesheet" href="{{ my_asset('/assets/css/dataTable-design.css') }}" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.8/css/jquery.dataTables.min.css">

    <!-- Select2 -->
    <link rel="stylesheet" href="{{ my_asset('/assets/bower_components/select2/dist/css/select2.min.css') }}" />

    <!-- <script src="{{ my_asset('/assets/js/jquery-3.6.0.min.js') }}"></script>  -->
    <script src="{{ my_asset('/assets/vendor/jquery/jquery.js') }}"></script>
    <script src="{{ my_asset('/assets/js/picker.date.js') }}"></script>
    <script src="{{ my_asset('/assets/js/daterangepicker.min.js') }}"></script>
    <script src="{{ my_asset('/assets/vendor/breakpoints/breakpoints.js') }}"></script>

    <script type="text/javascript">
        var baseurl = {!! json_encode(url('/')) !!};        
        Breakpoints();
        var static_url = {!! json_encode(config('app.static_url')) !!};
        var voice_url = {!! json_encode(config('app.voice_url')) !!};
    </script>
    <script src="{{ my_asset('/assets/js/toastr.js') }}"></script>
    <script>
        toastr.options = {
            positionClass: 'toast-top-center'
        };
    </script>
    <script type="text/javascript" src="{{ my_asset('/assets/js/chart/highcharts.js') }}"></script>
    <script type="text/javascript" src="{{ my_asset('/assets/js/chart/exporting.js') }}"></script>
    <script type="text/javascript" src="{{ my_asset('/assets/js/chart/export-data.js') }}"></script>
    <script type="text/javascript" src="{{ my_asset('/assets/js/chart/accessibility.js') }}"></script>


    <script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js" defer></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js" defer></script>


    <script src="{{ my_asset('/assets/js/daterangepicker.min.js') }}"></script>



    <script type="text/javascript" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap.min.js"></script>


    <!-- Select2 -->
    <script type="text/javascript" src="{{ my_asset('assets/bower_components/select2/dist/js/select2.full.min.js') }}">
    </script>




</head>

<body class="theme-4">
    <div id="loader" class="loader" style="z-index:99999999999;display:none">
        <div class="spinner"></div>
    </div>
    <?php if(session('auth')){ ?>
            @include('tpurl.layouts.topnav')

       <div class="dash-container">
        <div class="dash-content">
            <div class="page-header">
            <?php } ?>
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="row page-header-title">
                            <div class="col-md-6">
                                @if (trim($__env->yieldContent('page-title')))
                                    <h4 class="m-0">@yield('page-title')</h4>
                                @endif
                                <ul class="breadcrumb">
                                    @yield('breadcrumb')
                                </ul>
                            </div>
                            <div class="col-md-6 text-right">
                                @if (trim($__env->yieldContent('action-button')))
                                    <div class="all-button-box float-end mb-3">
                                        @yield('action-button')
                                    </div>
                                @elseif(trim($__env->yieldContent('multiple-action-button')))
                                    @yield('multiple-action-button')
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <?php if(session('auth')){ ?>
            </div>

            @yield('content')

        </div>
    </div>  
    </div>
    <?php }else{
        ?>@yield('content')<?php
    } ?>

    <footer class="site-footer text-center my-3 fixed-bottom">&copy; <strong>Encloud</strong> {{ date('Y') }} All rights
        reserved.
    </footer> 
    <script src="{{ my_asset('/assets/js/plugins/feather.min.js') }}"></script>
    <script src="{{ my_asset('/assets/js/common.js') }}"></script>
    <script src="{{ my_asset('/assets/js/dash.js') }}"></script>
    <script src="{{ my_asset('assets/js/plugins/simplebar.min.js') }}"></script>
    <script src="{{ my_asset('/assets/js/sweet_alert.js') }}"></script>
    <script src="{{ my_asset('/assets/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>


    @yield('jscontent')
 

 
<?php if(session('auth')){ ?>
    <script>
        $(document).ready(function() {

        }); 
    </script>
<?php } ?>
</body>

</html>