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
.employeeData img{
    width: 45px;
    height: 48px;
    border-radius: 50%;
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
                                        <div class="col-md-5" style="background-color: #faf0f0; border-top-left-radius: 20px; border-bottom-left-radius: 20px; padding: 10px;">
                                            <div class="text-center m-4">
                                                <img src="{{ my_asset('/assets/images/ntact-logo.png') }}" alt="login form" class="img-fluid" style="width: 171px; height: 117px;" />
                                            </div>
                                            <div class="p-3">
                                                <div class="employeeData" style="display: ruby; align-items: center;">        
                                                    <img src="{{ my_asset('assets/images/mavatar.png') }}" alt="avatar" style="">        
                                                    <div class="about display: inline-block; vertical-align: middle; m-2">
                                                        <div class="text-center name">Venkat Brainymed</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-center mt-1 mb-3">
                                                <i class="fa fa-calendar" aria-hidden="true"></i> <?=date('M d, Y');?>
                                            </div>
                                            <div class="text-center mb-3">
                                                <i class="fa fa-clock" aria-hidden="true"></i> <?=date('H:i A');?>
                                            </div>
                                            <div class="text-center mb-4">
                                                <i class="fa fa-map-marker" aria-hidden="true"></i> Location
                                            </div>
                                            <div class="text-center mt-6 mb-2" style="font-size: 11px;">
                                                <i class="fa fa-refresh" aria-hidden="true"></i> Last Sync <?=date('l M d, Y');?>
                                            </div>
                                        </div>

                                        <!-- Right Column: Employee Form -->
                                        <div class="col-md-7" style="background-color: #fff; border-top-right-radius: 20px; border-bottom-right-radius: 20px; padding: 20px;">
                                            <!-- Error Message -->
                                            <span class="text-danger font-weight-bold my-2">
                                                {{ session()->get('errorUser') }}
                                            </span>


                                            <!-- Step 1: Survay Section -->
                                            <div class="custom-login-form checkoutSurvayS1" style="padding: 20px 0px;" data-id="1">
                                                <div class="form-group mb-3">
                                                
                                                <label for="" class="question-lable">Were there any safety incidents today?</label>
                                                <div class="col-md-12" style="margin:10px;">
                                                    <div class="form-check p-2" style="">
                                                        <input class="form-check-input" type="radio" name="qanswer_1_1" id="form11" value="YES"  style="">
                                                        <label class="form-check-label" for="form61">YES</label>
                                                    </div>
                                                    <div class="form-check p-2" style="">
                                                        <input class="form-check-input" type="radio" name="qanswer_1_1" id="form12" value="NO"  style="">
                                                        <label class="form-check-label" for="">NO</label>
                                                    </div>
                                                </div>
                                                    
                                                </div>
                                                <div class="d-grid">
                                                    <button class="btn btn-secondary mt-3 next-btn" data-id="1" style="border-radius: 50px;">{{ __('Next') }} <span class="mdi mdi-arrow-right-bold"></span></button>
                                                </div>
                                            </div>

                                            <!-- Step 2: Survay Section -->
                                            <div class="custom-login-form checkoutSurvayS2 d-none" style="padding: 20px 0px;" data-id="2">
                                                <div class="form-group mb-3">
                                                    
                                                    <label for="" class="question-lable">Was today’s work on schedule according to the project timeline?</label>
                                                    <div class="col-md-12" style="margin:10px;">
                                                        <div class="form-check p-2" style="">
                                                            <input class="form-check-input" type="radio" name="qanswer_1_2" id="form21" value="YES"  style="">
                                                            <label class="form-check-label" for="form61">YES</label>
                                                        </div>
                                                        <div class="form-check p-2" style="">
                                                            <input class="form-check-input" type="radio" name="qanswer_1_2" id="form22" value="NO"  style="">
                                                            <label class="form-check-label" for="">NO</label>
                                                        </div>
                                                    </div>
                                                        
                                                    </div>
                                                    <div class="d-grid">
                                                        <button class="btn btn-secondary mt-3 previous-btn" data-id="2" style="border-radius: 50px;"><span class="mdi mdi-arrow-left-bold"></span> {{ __('Previous') }}</button>
                                                        <button class="btn btn-secondary mt-3 next-btn" data-id="2" style="border-radius: 50px;">{{ __('Next') }} <span class="mdi mdi-arrow-right-bold"></span></button>   
                                                    </div>
                                                </div>
                                            

                                            <!-- Step 3: Survay Section -->
                                            <div class="custom-login-form checkoutSurvayS3 d-none" style="padding: 20px 0px;" data-id="3">
                                                <div class="form-group mb-3">
                                                    
                                                    <label for="" class="question-lable">Is all safety equipment and signage properly stored or left in place for tomorrow?</label>
                                                    <div class="col-md-12" style="margin:10px;">
                                                        <div class="form-check p-2" style="">
                                                            <input class="form-check-input" type="radio" name="qanswer_1_3" id="form31" value="YES"  style="">
                                                            <label class="form-check-label" for="form61">YES</label>
                                                        </div>
                                                        <div class="form-check p-2" style="">
                                                            <input class="form-check-input" type="radio" name="qanswer_1_3" id="form32" value="NO"  style="">
                                                            <label class="form-check-label" for="">NO</label>
                                                        </div>
                                                    </div>
                                                        
                                                    </div>
                                                    <div class="d-grid">
                                                        <button class="btn btn-secondary mt-3 previous-btn" data-id="3" style="border-radius: 50px;"><span class="mdi mdi-arrow-left-bold"></span> {{ __('Previous') }}</button>
                                                        <button class="btn btn-secondary mt-3 next-btn" data-id="3" style="border-radius: 50px;">{{ __('Next') }} <span class="mdi mdi-arrow-right-bold"></span></button>
                                                    </div>
                                                </div>
                                            
                                            
                                            <div class="custom-login-form checkoutSurvayS4 d-none" style="padding: 20px 0px;" data-id="4">
                                                <div class="form-group mb-3">
                                                    
                                                    <label for="" class="question-lable">What tasks are prioritized for tomorrow, and is everyone clear on their responsibilities?</label>
                                                    <div class="col-md-12" style="margin:10px;">
                                                        <div class="form-check p-2" style="">
                                                            <input class="form-check-input" type="radio" name="qanswer_1_4" id="form41" value="YES"  style="">
                                                            <label class="form-check-label" for="form11">YES</label>
                                                        </div>
                                                        <div class="form-check p-2" style="">
                                                            <input class="form-check-input" type="radio" name="qanswer_1_4" id="form42" value="NO"  style="">
                                                            <label class="form-check-label" for="">NO</label>
                                                        </div>
                                                    </div>
                                                        
                                                    </div>
                                                    <div class="d-grid">
                                                        <button class="btn btn-secondary mt-3 previous-btn" data-id="4" style="border-radius: 50px;"><span class="mdi mdi-arrow-left-bold"></span> {{ __('Previous') }}</button>
                                                        <button class="btn btn-secondary mt-3 next-btn" data-id="4" style="border-radius: 50px;">{{ __('Submit') }}</button>
                                                    </div>
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

$(document).ready(function () {
    $('.next-btn').on('click', function () {
        var currentStep = $(this).data('id');
        $('.checkoutSurvayS' + currentStep).addClass('d-none');
        var nextStep = currentStep + 1;
        $('.checkoutSurvayS' + nextStep).removeClass('d-none');
    });
    $('.previous-btn').on('click', function () {
        var currentStep = $(this).data('id');
        $('.checkoutSurvayS' + currentStep).addClass('d-none');
        var nextStep = currentStep - 1;
        $('.checkoutSurvayS' + nextStep).removeClass('d-none');
    });
    $('.backtosearchemp').on('click', function () {
        $('.employeeSearch').removeClass('d-none');
        $('.add-employee').addClass('d-none');
    });

    $('.savecheckoutsurvay').on('click', function () {
       
    });
});


createEmployee
</script>
	
@endsection