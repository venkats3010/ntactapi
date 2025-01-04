@extends('layouts.app')
@section('content')
<style>
    .col-xl-10 {
        flex: 0 0 auto;
        width: 74%;
    }

    .alert-warning i {
        font-size: 20px;
    }

    .alert-warning h5 {
        font-size: 15px;
        margin-top: 10px;
        letter-spacing: 1px;
    }

</style>
<style>
    .card {
        border-radius: 20px;
        max-width: 800px;
        width: 100%;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .card-body {
        background-color: #faf0f0;
        border-radius: 20px;
    }

    .col-md-6 {
        padding: 20px;
    }

    .custom-login-form {
        padding: 20px 30px;
    }

    .text-center i {
        margin-right: 10px;
    }

    .form-label {
        font-weight: bold;
    }

    .btn-primary {
        border-radius: 50px;
    }

    /* For better mobile responsiveness */
    @media (max-width: 768px) {
        .col-md-6 {
            padding: 15px;
        }
        .custom-login-form {
            padding: 20px;
        }
    }
</style>
<link rel="stylesheet" href="{{ my_asset('/assets/css/custom-auth.css') }}" />
<div class="custom-login">
        <!--<div class="bg-login bg-primary"></div>-->
        <div class="custom-login-inner">

            <main class="custom-wrapper">
                <div class="custom-row">
                <!-- <div class="col-xl-4 col-12"></div> -->
                <div class="row d-flex justify-content-center align-items-center h-100">
                            <div class="card" style="border-radius: 20px;">
                                <div class="card-body p-0" style="background-color: #faf0f0; border-radius: 20px;">
                                    <div class="row">
                                        <!-- Left Column: Content like image, date, location -->
                                        <div class="col-md-6" style="background-color: #faf0f0; border-top-left-radius: 20px; border-bottom-left-radius: 20px; padding: 10px;">
                                            <div class="text-center m-4">
                                                <img src="{{ my_asset('/assets/images/ntact-logo.png') }}" alt="login form" class="img-fluid" style="width: 171px; height: 117px;" />
                                            </div>
                                            <div class="text-center mt-5 mb-3">
                                                <i class="fa fa-calendar" aria-hidden="true"></i> <?=date('M d, Y');?>
                                            </div>
                                            <div class="text-center mb-3">
                                                <i class="fa fa-clock" aria-hidden="true"></i> <?=date('H:i A');?>
                                            </div>
                                            <div class="text-center mb-4">
                                                <i class="fa fa-map-marker" aria-hidden="true"></i> Location
                                            </div>
                                            <div class="text-center mt-5 mb-2" style="font-size: 11px;">
                                                <i class="fa fa-refresh" aria-hidden="true"></i> Last Sync <?=date('l M d, Y');?>
                                            </div>
                                        </div>

                                        <!-- Right Column: Login Form -->
                                        <div class="col-md-6" style="background-color: #fff; border-top-right-radius: 20px; border-bottom-right-radius: 20px; padding: 20px;">
                                            <!-- Error Message -->
                                            <span class="text-danger font-weight-bold my-2">
                                                {{ session()->get('errorUser') }}
                                            </span>


                                            <!-- Step 1: Phone Number Input Section -->
                                            <div class="custom-login-form fieldLoginS1" style="padding: 100px 0px;" data-id="1">
                                                <div class="form-group mb-3">
                                                    <label for="phoneno" class="form-label d-flex lblusername">{{ __('Enter Registered Phone Number') }}</label>
                                                    <input type="text" class="form-control" id="phoneno" name="phoneno" placeholder="{{ __('Enter Phone') }}" required value="{{ old('phoneno') }}" autocomplete="off">
                                                    <span id="phonenomsg" class="text-danger font-weight-bold"></span>
                                                </div>
                                                <div class="d-grid">
                                                    <button class="btn btn-primary mt-3 checkvalidate" data-id="1" style="border-radius: 50px;">{{ __('Continue') }}</button>
                                                </div>
                                            </div>

                                            <!-- Step 2: OTP Input Section -->
                                            <div class="custom-login-form fieldLoginS2 d-none" style="padding: 20px 0px;" data-id="2">
                                                <div class="form-group mb-3">
                                                    <label for="otp" class="form-label d-flex lblusername">{{ __('Enter OTP') }}</label>
                                                    <input type="text" class="form-control" id="otp" name="otp" placeholder="{{ __('Enter OTP') }}" required value="{{ old('otp') }}" autocomplete="off">
                                                    <span id="otpmsg" class="text-danger font-weight-bold"></span>
                                                </div>
                                                <div class="d-grid">
                                                    <button class="btn btn-primary mt-3 checkvalidate" data-id="2" style="border-radius: 50px;">{{ __('Continue') }}</button>
                                                </div>
                                            </div>

                                            <!-- Step 3: Employee Search Input Section -->
                                            <div class="custom-login-form fieldLoginS3 d-none" style="padding: 20px 0px;" data-id="3">
                                                <div class="form-group mb-3">
                                                    <div class="input-group mb-3">
                                                        <input type="text" class="form-control" id="searchEmp" name="searchEmp" placeholder="{{ __('Search Employee') }}" required autocomplete="off">
                                                        <span style="position: relative; top:10px; left: -20px" class="mdi mdi-magnify" name="search" id="search-btn"></span>
                                                        <span id="searchEmpmsg" class="text-danger font-weight-bold"></span>
                                                    </div>
                                                </div>
                                                <div class="d-grid">
                                                    <button class="btn btn-primary mt-3 checkvalidate" data-id="3" style="border-radius: 50px;">{{ __('Continue') }}</button>
                                                </div>
                                            </div>

                                            <!-- Step 4: Final Search Employee Section -->
                                            <div class="custom-login-form fieldLoginS4 d-none" style="padding: 20px 0px;" data-id="4">
                                                <div class="form-group mb-3">
                                                    <input type="text" class="form-control" id="searchEmp" name="searchEmp" placeholder="{{ __('Search Employee') }}" required autocomplete="off">
                                                    <span id="searchEmpmsg" class="text-danger font-weight-bold"></span>
                                                </div>
                                                <div class="d-grid">
                                                    <button class="btn btn-primary mt-3 checkvalidate" data-id="4" style="border-radius: 50px;">{{ __('Continue') }}</button>
                                                </div>
                                            </div>


                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </main>

        </div>
    </div>
    </div>
    
@endsection

@section('jscontent')

<script>
// $(document).ready(function() {

// str ='<div class="custom-login-form phonenoform" style="padding: 20px 0px;">'+
//     <!-- mobile number Input Field -->
//     '<div class="form-group mb-3">'+
//         '<label for="phoneno" class="form-label d-flex lblusername">Enter Registered Phone Number</label>'+
//         '<input type="text" class="form-control" id="phoneno" name="phoneno" placeholder="Enter Phone" required value="" autocomplete="off">'+
//         '<span id="phonenomsg" class="text-danger font-weight-bold"></span>'+
//     '</div>'+
//     <!-- Continue Button -->
//     '<div class="d-grid">'+
//         '<button class="btn btn-primary mt-3" id="checkphonevalidate" style="border-radius: 50px;">Continue</button>'+
//     '</div>'+
// '</div>';
// $('.field-login-form').html(str);
// });


    // $('#checkphonevalidate').on('click', function () {
    //     $('.phonenoform').addClass('d-none');
    //     $('.otpform').removeClass('d-none');
    // });
    // $('#checkotpvalidate').on('click', function () {
    //     $('.phonenoform').addClass('d-none');
    //     $('.otpform').removeClass('d-none');
    // });


$(document).ready(function () {
    $('.checkvalidate').on('click', function () {
        var currentStep = $(this).data('id');
        $('.fieldLoginS' + currentStep).addClass('d-none');
        var nextStep = currentStep + 1;
        $('.fieldLoginS' + nextStep).removeClass('d-none');
    });
});

</script>
<script>
   
</script>
<script>
$(document).ready(function() {
    
    $('#togglePassword').on('click', function () {
        const passwordField = $('#password');
        const eyeIcon = $('#eyeIcon');

        if (passwordField.attr('type') === 'password') {
            passwordField.attr('type', 'text');
            eyeIcon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            passwordField.attr('type', 'password');
            eyeIcon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });

   
    $('#password').on('keypress', function (e) {
        if (e.which === 13) { 
            $('#login_button').click(); 
        }
    });

});

</script>	
@endsection