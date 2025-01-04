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
                                <div class="text-center m-4">
                                    <!--<h2 class="mb-3 f-w-600">{{ __('User') }} <span class="text-primary">{{ __('to login!') }}</span></h2>-->
                                    <a href="#">
                                        <img src="{{ my_asset('assets/images/logo.png') }}" alt="{{ config('app.name', 'TicketGo Saas') }}" alt="logo" loading="lazy" class="logo" />
                                    </a>                                    
                                </div>
								{{--<form class="mt-4" method="post" action="#" id="form_data" autocomplete="off">
                                    @csrf --}}
                                    <span class="text-danger font-weight-bold">
                                        {{ session()->get('errorUser') }}
                                    </span>

                                    <div class="custom-login-form">
                                        <div class="form-group mb-3 d-none">
                                            <label for="username" class="form-label d-flex">{{ __('Username') }}</label>
                                            <input type="username" class="form-control" id="username" name="username" placeholder="{{ __('Enter username') }}" required="" value="{{$uid}}">
                                                <span id="usernamemsg" class="text-danger font-weight-bold"></span>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label class="form-label d-flex">{{ __('MPIN') }}</label>
                                            <input type="password" class="form-control " id="password" name="password" placeholder="{{ __('Enter PIN') }}"  required="" >
                                            <span id="passwordmsg" class="text-danger font-weight-bold"></span>
                                        </div>


                                {{--</form>--}}
                                <div class="d-grid">
                                    <button class="btn btn-primary mt-2 login-do-btn"
                                        id="login_button">{{ __('Login') }}</button>
                                </div>

                                <div class="form-group mt-2 mb-4">
                                    <div class="d-flex flex-wrap align-items-center justify-content-between">
                                        {{--<span><a href="{{ url('forgotPassword') }}" tabindex="0">{{ __(' Password help?') }}</a></span>--}}
                                        <span><a href="{{ url('create-mpin', $cryptuid) }}" tabindex="0">{{ __(' Mpin help?') }}</a></span>
                                        <span><a href="" tabindex="0">{{ __('Support') }}</a></span>
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
    var urid = {{ $uid }};
   $("#login_button").click(function() {
        let ajaxResponse; 
        let protocol = window.location.protocol;
        let location = window.location.hostname;
        let port = window.location.port;
        $("#ulHeading").removeClass('d-none');
        $("#loginFailedErrorMsgMainDiv").addClass('d-none');

        let pathname = window.location.pathname.split('/');
        pathname.pop();
        let username = urid;//$('#username').val();
        let password = $('#password').val();
        let mpin = 1;
        if (username == "") {
            document.getElementById("usernamemsg").innerHTML =
                "<span style='text-align: left;margin-right: 150px;'> Please fill the username field </span>";
            return false;
        } else {
            document.getElementById("usernamemsg").innerHTML = "";
        }
        if (password == "") {
            document.getElementById("passwordmsg").innerHTML =
                "<span style='text-align: left;margin-right: 150px;'> Please fill the mpin field </span>";
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
                "password": password,
                "mpin": mpin
            },
            dataType: "json",
            success: function(res) {
                //var res = jQuery.parseJSON(res);
                console.log("LoginData"+JSON.stringify(res));
                if(res['result'] == "true"){
                    toastr.success(res['message']);
                    var url = '';
					if (port != '') {
						console.log(`${protocol}//${location}:${port}/${pathname[pathname.length-2]}tpurl`);
						url = `${protocol}//${location}:${port}/${pathname[pathname.length-2]}tpurl`;
					} else {
						console.log(`${protocol}//${location}/${pathname[pathname.length-2]}/tpurl`);
						url = `${protocol}//${location}${port}/${pathname[pathname.length-2]}/tpurl`;
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
@endsection