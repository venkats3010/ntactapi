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

/* Default styles for larger screens (laptops, desktops, etc.) */
.leftsidecard {
    background-color: #faf0f0;
    border-top-left-radius: 20px;
    border-bottom-left-radius: 20px;
    padding: 10px;
}

.rightsidecard {
    background-color: #fff;
    border-top-right-radius: 20px;
    border-bottom-right-radius: 20px;
    padding: 20px;
}

/* Mobile resolution (max-width: 480px) */
@media (max-width: 480px) {
    .leftsidecard {
        background-color: #faf0f0;
        border-top-left-radius: 20px;
        border-top-right-radius: 20px; 
        
        padding: 10px;
    }

    .rightsidecard {
        background-color: #fff;
        border-top-right-radius: 20px;
        border-bottom-left-radius: 20px; 
        border-bottom-right-radius: 20px;
        padding: 20px;
    }
}

/* Tablet resolution (max-width: 768px) */
@media (max-width: 768px) {
    .leftsidecard {
        background-color: #faf0f0;
        border-top-left-radius: 20px;
        border-top-right-radius: 20px; 
        border-bottom-left-radius: 0px;
        padding: 10px;
    }

    .rightsidecard {
        background-color: #fff;
        border-top-right-radius: 20px;
        border-bottom-left-radius: 20px;
        border-bottom-right-radius: 20px;
        border-top-right-radius: 0px;
        padding: 20px;
    }
}

/* Laptop resolution (min-width: 1024px) */
@media (min-width: 1024px) {
    .leftsidecard {
        background-color: #faf0f0;
        border-top-left-radius: 20px;
        border-bottom-left-radius: 20px;
        padding: 10px;
    }

    .rightsidecard {
        background-color: #fff;
        border-top-right-radius: 20px;
        border-bottom-right-radius: 20px;
        padding: 20px;
    }
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
                    <div class="col-12 col-sm-8">
                            <div class="card" style="border-radius: 20px;">
                                <div class="card-body p-0" style="background-color: #faf0f0; border-radius: 20px;">
                                    <div class="row">
                                        <!-- Left Column: Content like image, date, location -->
                                        <div class="col-md-5 leftsidecard" style="">
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
                                            <div class="text-center mt-6 mb-2" style="font-size: 11px;">
                                                <i class="fa fa-refresh" aria-hidden="true"></i> Last Sync <?=date('l M d, Y');?>
                                            </div>
                                        </div>

                                        <!-- Right Column: Employee Form -->
                                        <div class="col-md-7 rightsidecard" style="">
                                            <!-- Error Message -->
                                            <span class="text-danger font-weight-bold my-2">
                                                {{ session()->get('errorUser') }}
                                            </span>


                                            <!-- Step 3: Employee Search Input Section -->
                                            <div class="custom-login-form employeeSearch" style="padding: 20px 0px;" data-id="3">
                                                <div class="form-group mb-3">
                                                    <div class="input-group mb-3">
                                                        <input type="text" class="form-control" id="searchEmp" name="searchEmp" placeholder="{{ __('Search Employee') }}" required autocomplete="off">
                                                        <span style="position: relative; top:10px; left: -20px" class="mdi mdi-magnify" name="search" id="search-btn"></span>
                                                        <span id="searchEmpmsg" class="text-danger font-weight-bold"></span>
                                                    </div>
                                                </div>
                                                <div class="d-grid">
                                                    <button class="btn btn-primary mt-3 checkEmpvalidate" data-id="3" style="border-radius: 50px;">{{ __('Continue') }}</button>
                                                </div>
                                            </div>

                                            <!-- Step 4: Add Employee Section -->
                                            <div class="custom-login-form add-employee d-none" style="padding: 20px 0px;">
                                                
                                                <div class="border border-2 p-3">
                                                <div class="text py-3"><h5>Enter Employee Details</h5></div>
                                                    <div class="form-group mb-3">
                                                    <label for="firstname" class="emp-lable">First Name *</label>
                                                        <input type="text" class="form-control" id="firstname" name="firstname" placeholder="{{ __('Enter your first name') }}" required autocomplete="off">
                                                        <span id="firstnamemsg" class="text-danger font-weight-bold"></span>
                                                    </div>
                                                    <div class="form-group mb-3">
                                                    <label for="lastname" class="emp-lable">Last Name *</label>
                                                        <input type="text" class="form-control" id="lastname" name="lastname" placeholder="{{ __('Enter your last name ') }}" required autocomplete="off">
                                                        <span id="lastnamemsg" class="text-danger font-weight-bold"></span>
                                                    </div>
                                                    <div class="form-group mb-3">
                                                    <label for="empid" class="emp-lable"> ID *</label>
                                                        <input type="text" class="form-control" id="empid" name="empid" placeholder="{{ __('Enter your ID ') }}" required autocomplete="off">
                                                        <span id="empidmsg" class="text-danger font-weight-bold"></span>
                                                    </div>
                                                    <div class="d-grid">
                                                    <button class="btn btn-primary mt-3 createEmployee" style="border-radius: 50px;">{{ __('Continue') }}</button>
                                                    <button class="btn btn-outline-dark rounded-pill p-2 mt-3 backtosearchemp" style="border-radius: 50px;">{{ __('Cancel') }}</button>
                                                    </div>
                                                
                                                </div>
                                            </div>

                                            <!--  Final Step : Clock-in Clock-out Employee Section -->
                                            <div class="custom-login-form clockinout d-none" style="padding: 20px 0px;">
    
                                                <div class="border border-2 p-3">                                                
                                                    <div class="border-bottom border-2 p-3">
                                                        <div class="employeeData" style="display: ruby; align-items: center;">        
                                                            <img src="{{ my_asset('assets/images/mavatar.png') }}" alt="avatar" style="">        
                                                            <div class="about display: inline-block; vertical-align: middle; m-2">
                                                                <div class="text-center name">Venkat Brainymed</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="d-grid">
                                                    <button class="btn btn-primary mt-3 saveclockin" style="border-radius: 10px;"><i class="mdi mdi-watch-import"></i>  {{ __('Clock In') }}</button>
                                                    <button class="btn btn-primary mt-3 saveclockout" style="border-radius: 10px;"><i class="mdi mdi-watch-export"></i>  {{ __('Clock Out') }}</button>
                                                    </div>
                                                
                                                </div>
                                                <p class="text-center p-5">By pressing "Clock In," I pledge that I am accurately representing my identity and confirm that I am who I claim to be.</p>
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
    $('.checkEmpvalidate').on('click', function () {
        $('.employeeSearch').addClass('d-none');
        $('.add-employee').removeClass('d-none');
    });
    $('.backtosearchemp').on('click', function () {
        $('.employeeSearch').removeClass('d-none');
        $('.add-employee').addClass('d-none');
    });
    $('.createEmployee').on('click', function () {
        $('.employeeSearch').addClass('d-none');
        $('.add-employee').addClass('d-none');
        $('.clockinout').removeClass('d-none');
    });

    $('.saveclockinout').on('click', function () {
        $('.employeeSearch').addClass('d-none');
        $('.add-employee').removeClass('d-none');
    });
});


createEmployee
</script>
<script>
$(document).ready(function() {


});

</script>	
@endsection