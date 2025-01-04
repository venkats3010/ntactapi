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
<link rel="stylesheet" href="{{ my_asset('/assets/css/custom-auth.css') }}" />
<div class="custom-login">
        <!--<div class="bg-login bg-primary"></div>-->
        <div class="custom-login-inner">

            <main class="custom-wrapper">
                <div class="custom-row">
               
                    <div class="row d-flex justify-content-center align-items-center h-100">
                        <div class="card">
                            <div class="card-body">

                                 <div class="text-center m-4">
                                    <!--<h2 class="mb-3 f-w-600">{{ __('User') }} <span class="text-primary">{{ __('to login!') }}</span></h2>-->
                                    <a href="#">
                                        <img src="{{ my_asset('assets/images/logo.png') }}" alt="{{ config('app.name', 'TicketGo Saas') }}" alt="logo" loading="lazy" class="logo" />
                                    </a>                                    
                                </div>
                                <div class="text-center mb-3 mt-3">
                                    <h2 class="f-w-600">{{ __('Forgot Your Password ?') }}</h2>
                                </div>
                               <!-- <form method="post" action="#" autocomplete="off">-->
                                  {{--  @csrf
                                    @if (Session::has('message'))
                                    <div class="alert alert-success" role="alert">
                                        {{ Session::get('message') }}
                                    </div>
                                    @elseif (Session::has('error'))
                                    <div class="alert alert-danger" role="alert">
                                        {{ Session::get('error') }}
                                    </div>
                                    @endif --}}                                 

                                    <div class="custom-login-form">
                                        <div class="">
                                            <div class="form-group mb-3">
                                                <label for="phone" class="form-label d-flex">{{ __('Phone') }}</label>
                                                <input type="phone" id="phonenumber" name="phonenumber" class="form-control" placeholder="Enter 10 digit mobile number" required="" onkeypress="return isNumber(event)" onpaste="setTimeout(onlyNumbers.bind(null,this),100)" maxlength="10" autocomplete="off">
											    <span id="phone_number" class="text-danger font-weight-bold"></span>
                                            </div>
                                        
                                            <div class="form-group mb-3 d-none validateotpdiv">
                                                <label for="validateotp" class="form-label d-flex">{{ __('OTP') }}</label>
                                                <input type="password" id="validateotp" name="validateotp" class="form-control" placeholder="Enter OTP" required="" autocomplete="off">
											    <span id="validotp" class="text-danger font-weight-bold"></span>
                                            </div>
                                            
                                        <div class="d-grid">
                                            <button class="btn btn-primary mt-2 send-otp-btn" id="sendotp">{{ __('Send OTP') }}</button>
                                            <button class="btn btn-primary mt-2 validate-otp-btn d-none" id="checkotpvalidate">{{ __('Validate OTP') }}</button>
                                        </div>
                                        <div class="passworddtls d-none">
                                            <div class="form-group mb-3">
                                                <label for="newPassword" class="form-label d-flex">{{ __('New Password') }}</label>
                                                <input type="password" class="form-control" id="newPassword" name="newPassword" onchange="validatePassword();" placeholder="{{ __('New Password') }}" required="" autocomplete="off">
                                                <span id="newPasswordmsg" class="text-danger font-weight-bold"></span>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label for="confirmPassword" class="form-label d-flex">{{ __('Confirm Password') }}</label>
                                                <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="{{ __('Confirm Password') }}" required="" autocomplete="off">
                                                <span id="confirmPasswordmsg" class="text-danger font-weight-bold"></span>
                                            </div>
                                            
                                            <div class="d-grid">
                                                <button class="btn btn-primary mt-2 update-Password-btn" > {{ __('Submit') }} </button>
                                            </div>
                                        </div>

                                        <p class="my-4 text-center d-flex">
                                            <a href="{{ url('/login') }}" tabindex="0">{{ __('Back to Login ?') }}</a>
                                        </p>
                                    </div>
                                <!--</form>-->

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
    var generatedotp = "";
    $(".send-otp-btn").click(function() {
        let phonenumber = $('#phonenumber').val();
        if (phonenumber == "") {
            document.getElementById("phone_number").innerHTML =
                "<span style='text-align: left;margin-left:10px;'>Valid phone no.</span>";
            return false;
        } else {
            document.getElementById("phone_number").innerHTML = "";
        }
        let countrycode = 1;
        
        	$('#checkotpvalidate').removeClass('d-none');
            $('#sendotp').addClass('d-none');
        
        $('#spinner').show();
        $.ajax({
            url: "{{ url('sendotp') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                "countrycode": countrycode,
                "phonenumber": phonenumber
            },
            success: function(response) {
                
				if(response.status == 200){
				    generatedotp = response.otp;
					toastr.success(response.message);
					$('#validateotp').removeClass('d-none');
                    $('#sendotp').addClass('d-none');
                    $('.validateotpdiv').removeClass('d-none');
                    
				}else{
					toastr.error(response.message);
					$('#validateotp').addClass('d-none');
                    $('#sendotp').removeClass('d-none');
                    $('#checkotpvalidate').addClass('d-none');
				}
				$('#spinner').hide();
            },
            error: function(response) {
                $('#spinner').hide();
                toastr.error("failed to signup");
            }
        })
    });


    $("#checkotpvalidate").click(function() {
        let validateotp = $('#validateotp').val();
        if (validateotp != generatedotp) {
            document.getElementById("validotp").innerHTML =
                "<span style='text-align: left;margin-right: 150px;'> Please fill the valid OTP  </span>";
            return false;
        } else {
            document.getElementById("validotp").innerHTML = "";
            toastr.success("Successfully validate");
            $('.passworddtls').removeClass('d-none');
            $('.checkotpvalidate').addClass('d-none');
            $('#checkotpvalidate').addClass('d-none');
            $('.validateotpdiv').addClass('d-none');
        }        
    });
    
    $(".update-Password-btn").click(function() {
        let phonenumber = $('#phonenumber').val();
        let validateotp = $('#validateotp').val();
        let newPassword = $('#newPassword').val();
        let confirmPassword = $('#confirmPassword').val();
        
        if (phonenumber == "") {
            document.getElementById("phone_number").innerHTML =
                "<span style='text-align: left;margin-left:10px;'>Valid phone no.</span>";
            return false;
        } else {
            document.getElementById("phone_number").innerHTML = "";
        }
        let countrycode = 1;
        
        if (newPassword == "") {
            document.getElementById("newPasswordmsg").innerHTML =
                "<span style='text-align: left;margin-right: 150px;'> Please fill the Password field </span>";
            return false;
        } else {
            let validatepwd = validatePassword();
            if(!validatepwd){
                return false;
            }
            document.getElementById("newPasswordmsg").innerHTML = "";
        }
        
        if (confirmPassword == "") {
            document.getElementById("confirmPasswordmsg").innerHTML =
                "<span style='text-align: left;margin-right: 150px;'> Please fill the Confirm Password </span>";
            return false;
        } else {
            document.getElementById("confirmPasswordmsg").innerHTML = "";
        }
        if(newPassword != confirmPassword){
            document.getElementById("confirmPasswordmsg").innerHTML =
                "<span style='text-align: left;margin-right: 150px;'> Please check Password Confirm Password mismatch </span>";
            return false;
        } else {
            document.getElementById("confirmPasswordmsg").innerHTML = "";
        }
        if (validateotp != generatedotp) {
            document.getElementById("newPasswordmsg").innerHTML =
                "<span style='text-align: left;margin-right: 150px;'> Please fill the valid OTP  </span>";
            return false;
        } else {
            document.getElementById("newPasswordmsg").innerHTML = "";
        }

        $('#spinner').show();
        $.ajax({
            url: "{{ url('update-Password') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                "countrycode": countrycode,
                "phonenumber": phonenumber,
                "newpassword":newPassword,
                "confirmpassword":confirmPassword
            },
            success: function(response) {
				if(response.status == 200){
					const baseUrl = "{{ url('/') }}";

                    //console.log(loginUrl);
                    toastr.success(response.message);
                    window.location.href = baseUrl;
                    
				}else{
					toastr.error(response.message);
				}
				$('#spinner').hide();
            },
            error: function(response) {
                $('#spinner').hide();
                toastr.error("failed to update");
            }
        })
    });
    
	function isNumber(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
      return true;
    }


</script>
<script>
function validatePassword() {
    const newPassword = document.getElementById("newPassword").value;
    const messageElement = document.getElementById("newPasswordmsg");
    let errorMessage = "";

    if (newPassword === "") {
        errorMessage = "Please fill the Password field.";
    } 

    else if (newPassword.length < 6) {
        errorMessage = "Password must be at least 6 characters long.";
    } 

    else if (!/[A-Z]/.test(newPassword)) {
        errorMessage = "Password must contain at least one uppercase letter.";
    } 

    else if (!/[a-z]/.test(newPassword)) {
        errorMessage = "Password must contain at least one lowercase letter.";
    } 

    else if (!/[0-9]/.test(newPassword)) {
        errorMessage = "Password must contain at least one number.";
    } 

    else if (!/[!@#$%^&*(),.?":{}|<>]/.test(newPassword)) {
        errorMessage = "Password must contain at least one special character.";
    }


    if (errorMessage) {
        messageElement.innerHTML = `<span style='text-align: left; margin-right: 150px;'>${errorMessage}</span>`;
        return false;
    } else {
        messageElement.innerHTML = "";
        return true;
    }
}

</script>
@endsection