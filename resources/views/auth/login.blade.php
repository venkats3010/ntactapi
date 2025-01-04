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
                <!-- <div class="col-xl-4 col-12"></div> -->
                <div class="row d-flex justify-content-center align-items-center h-100">
                        <div class="card">
                            <div class="card-body">
                                <div class="text-center m-4" style="font-weight:900;font-size:35px;">
								<img src="{{ my_asset('/assets/images/logo.png') }}" alt="login form" class="img-fluid" style="border-radius: 1rem 0 0 1rem;" />
								</div>
                                    <span class="text-danger font-weight-bold my-2">
                                        {{ session()->get('errorUser') }}
                                    </span>
                                    <div class="custom-login-form my-2">
                                        <div class="form-group mb-3">
                                            <label for="username" class="form-label d-flex lblusername">{{ __('Enter Registered Phone or Email') }}</label>
                                            <input type="username" class="form-control" id="username" name="username" placeholder="{{ __('Enter Phone ') }}" required="" value="{{ old('username') }}" autocomplete="off">
                                            <span id="usernamemsg" class="text-danger font-weight-bold"></span>
                                        </div>

										<div class="form-group mb-3 validateotpdiv">
											<label class="form-label d-flex lblpassword">{{ __('Password') }}</label>
											<div class="input-group">
												<input type="password" class="form-control" id="password" name="password" placeholder="{{ __('Enter Password') }}" required="" autocomplete="off">
												<button class="input-group-text" id="togglePassword" type="button">
													<i class="fas fa-eye" id="eyeIcon"></i>
												</button>
											</div>
											<span id="passwordmsg" class="text-danger font-weight-bold"></span>
										</div>

                                       <!-- <div class="form-group mb-3 validateotpdiv">
                                            <label class="form-label d-flex lblpassword">{{ __('Password') }}</label>
                                            <input type="password" class="form-control " id="password" name="password" placeholder="{{ __('Enter Password') }}" required="" autocomplete="off">
                                                <span id="passwordmsg" class="text-danger font-weight-bold"></span>
                                        </div>-->

                                
                                <div class="d-grid">
                                    <button class="btn btn-primary mt-2 send-otp-btn d-none" id="sendotp">{{ __('Send OTP') }}</button>
                                    <button class="btn btn-primary mt-2 validate-otp-btn d-none" id="checkotpvalidate">{{ __('Submit') }}</button>
                                    <button class="btn btn-primary mt-2 login-do-btn" id="login_button">{{ __('Login') }}</button>
                                </div>

                                <div class="form-group mt-2 mb-4">
                                    <div class="d-flex flex-wrap align-items-center justify-content-between">
                                        <span><a href="{{ url('forgotPassword') }}" tabindex="0">{{ __(' Password help?') }}</a></span>
                                        <!--<span><a href="<?php echo url('/forgotPasswordOtp') ?>" tabindex="0">{{ __(' Password help?') }}</a></span>-->
                                        <!--<span><a href="" tabindex="0">{{ __('Support') }}</a></span>-->
										{{--<span class="loginwithpwdbtn d-none"><a href="#">{{ __('Login with Password') }}</a></span>
                                        <span class="loginwithotpbtn"><a href="#">{{ __('Login with OTP') }}</a></span>--}}
                                        <span class=""><a href="<?php echo url('/field-login') ?>">{{ __('Field Login') }}</a></span>
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
    var generatedotp = "";
    $(".send-otp-btn").click(function() {
        let username = $('#username').val();
        if (username == "") {
            document.getElementById("usernamemsg").innerHTML =
                "<span style='text-align: left;margin-left:10px;'>Valid phone no.</span>";
            return false;
        } else {
            document.getElementById("usernamemsg").innerHTML = "";
        }
        let countrycode = 1;
        
        	$('#checkotpvalidate').removeClass('d-none');
            $('#sendotp').addClass('d-none');
        
        $('#spinner').show();
        $.ajax({
            url: "{{ url('sendLoginotp') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                "countrycode": countrycode,
                "phonenumber": username
            },
            success: function(response) {
                console.clear(); 
				if(response.status == 200){
				    let uotp = atob(response.otp);
				    generatedotp = uotp.slice(26);
				    //generatedotp = atob(response.otp);
				    //generatedotp = CryptoJS.SHA256(response.otp).toString(CryptoJS.enc.Hex);
				    //console.log(generatedotp);
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
        let validateotp = $('#password').val();
        console.log("Valid "+validateotp);
        console.log("Gen "+generatedotp);
        if (validateotp != generatedotp) {
            document.getElementById("passwordmsg").innerHTML =
                "<span style='text-align: left;margin-right: 150px;'> Please fill the valid OTP  </span>";
            return false;
        } else {
            document.getElementById("passwordmsg").innerHTML = "";
            //toastr.success("Successfully validate");
            $('.passworddtls').removeClass('d-none');
            $('.checkotpvalidate').addClass('d-none');
            $('#checkotpvalidate').addClass('d-none');
            $('.validateotpdiv').addClass('d-none');
            //$("#login_button").click();
            
                    let ajaxResponse; 
        let protocol = window.location.protocol;
        let location = window.location.hostname;
        let port = window.location.port;
        $("#ulHeading").removeClass('d-none');
        $("#loginFailedErrorMsgMainDiv").addClass('d-none');


        let pathname = window.location.pathname.split('/');
        pathname.pop();
        let username = $('#username').val();
        let password = $('#password').val();
        if (username == "") {
            document.getElementById("usernamemsg").innerHTML =
                "<span style='text-align: left;margin-right: 150px;'> Please fill the username field </span>";
            return false;
        } else {
            document.getElementById("usernamemsg").innerHTML = "";
        }
        var filter = /^(0|91)?[1-9][0-9]{9}$/;
        if (!(filter.test(username))) {
            document.getElementById("usernamemsg").innerHTML = " Please Enter Valid number";
            return false;
        }
        
        if (password == "") {
            document.getElementById("passwordmsg").innerHTML =
                "<span style='text-align: left;margin-right: 150px;'> Please fill the password field </span>";
            return false;
        } else {
            document.getElementById("passwordmsg").innerHTML = "";
        }
        $('#spinner').show();
        $.ajax({
            url: "{{ url('checkLogin') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                "username": username,
                "mpin": password
            },
            dataType: "json",
            success: function(res) {
                //var res = jQuery.parseJSON(res);
                console.log("LoginData"+JSON.stringify(res));
                if(res['result'] == "true"){
                    toastr.success(res['message']);
                    var url = '';
					if (port != '') {
						console.log(`${protocol}//${location}:${port}/${pathname[pathname.length-1]}dashboard`);
						url = `${protocol}//${location}:${port}/${pathname[pathname.length-1]}dashboard`;
					} else {
						console.log(`${protocol}//${location}/${pathname[pathname.length-1]}/dashboard`);
						url = `${protocol}//${location}${port}/${pathname[pathname.length-1]}/dashboard`;
					}
                    window.location.replace(url);
                    
                }else{
					$('#spinner').hide();
					toastr.error(res['message']);
				}                
            },
            error: function(response) {
                $('#spinner').hide();
                toastr.error("failed to login");
            }
        })

            
            
        }        
    });
    
</script>
<script>
    $(".loginwithotpbtn").click(function() {
        $('#sendotp').removeClass('d-none');
        $('.validateotpdiv').addClass('d-none');
        $('#login_button').addClass('d-none');
        $('.loginwithotpbtn').addClass('d-none');
        $('.loginwithpwdbtn').removeClass('d-none');
        $('.lblusername').html('Enter Registered Phone ');
        $('.lblpassword').html(' OTP ');
        $('#password').placeholder('Enter OTP ');
    });
    $(".loginwithpwdbtn").click(function() {
        $('#sendotp').addClass('d-none');
        $('.validateotpdiv').removeClass('d-none');
        $('#login_button').removeClass('d-none');
        $('.loginwithpwdbtn').addClass('d-none');
        $('.loginwithotpbtn').removeClass('d-none');
        $('.lblusername').html('Enter Registered Phone or Email');
        $('.lblpassword').html(' Password ');
        $('#password').placeholder('Enter Password ');
    });    
   $("#login_button").click(function() {
        let ajaxResponse; 
        let protocol = window.location.protocol;
        let location = window.location.hostname;
        let port = window.location.port;
        $("#ulHeading").removeClass('d-none');
        $("#loginFailedErrorMsgMainDiv").addClass('d-none');

        let pathname = window.location.pathname.split('/');
        pathname.pop();
        let username = $('#username').val();
        let password = $('#password').val();
        if (username == "") {
            document.getElementById("usernamemsg").innerHTML =
                "<span style='text-align: left;margin-right: 150px;'> Please fill the username field </span>";
            return false;
        } else {
            document.getElementById("usernamemsg").innerHTML = "";
        }
        if (password == "") {
            document.getElementById("passwordmsg").innerHTML =
                "<span style='text-align: left;margin-right: 150px;'> Please fill the password field </span>";
            return false;
        } else {
            document.getElementById("passwordmsg").innerHTML = "";
        }
        $('#spinner').show();
        $.ajax({
            url: "{{ url('checkLogin') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                "username": username,
                "password": password
            },
            dataType: "json",
            success: function(res) {
                //var res = jQuery.parseJSON(res);
                console.log("LoginData"+JSON.stringify(res));
                if(res['result'] == "true"){
                    toastr.success(res['message']);
                    var url = '';
					if (port != '') {
						console.log(`${protocol}//${location}:${port}/${pathname[pathname.length-1]}dashboard`);
						url = `${protocol}//${location}:${port}/${pathname[pathname.length-1]}dashboard`;
					} else {
						console.log(`${protocol}//${location}/${pathname[pathname.length-1]}/dashboard`);
						url = `${protocol}//${location}${port}/${pathname[pathname.length-1]}/dashboard`;
					}
                    window.location.replace(url);
                    
                }else{
					$('#spinner').hide();
					toastr.error(res['message']);
				}                
            },
            error: function(response) {
                $('#spinner').hide();
                toastr.error("failed to login");
            }
        })
    });
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