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
                            <div class="card-body w-100">

                                 <div class="text-center m-4">
                                    <!--<h2 class="mb-3 f-w-600">{{ __('User') }} <span class="text-primary">{{ __('to login!') }}</span></h2>-->
                                    <a href="#">
                                        <img src="{{ my_asset('assets/images/logo.png') }}" alt="{{ config('app.name', 'TicketGo Saas') }}" alt="logo" loading="lazy" class="logo" />
                                    </a>                                    
                                </div>
                                <div class="text-center mb-3 mt-3">
                                    <h2 class="f-w-600">{{ __('Create Your Password ?') }}</h2>
                                </div>
                                <form method="POST" action="{{ route('reset.password.post') }}" id="form_data">
                                    @csrf
                                    @if (Session::has('message'))
                                    <div class="alert alert-success" role="alert">
                                        {{ Session::get('message') }}
                                    </div>
                                    @elseif (Session::has('error'))
                                    <div class="alert alert-danger" role="alert">
                                        {{ Session::get('error') }}
                                    </div>
                                    @endif                                  

                                    @csrf
                            <input type="hidden" name="token" value="{{ $token }}">
                            <input type="hidden" name="flag_type" id="flag_type" value="create">
                            <span id="username" class="text-danger font-weight-bold">
                                {{ session()->get('errorUser') }}
                            </span>

                            <div class="d-flex justify-content-center">
                                <div class="spinner-border" id="spinner" role="status" style="display:none !important;">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>

                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-sm-6 mt-5">



                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">New Password</label>
                                                <div class="input-group mb-3">
                                                    <input type="hidden" name="hiddenValue" id="hiddenValue" value="2" />
                                                    <input type="password" class="form-control input-group" id="newPassword" name="newPassword" aria-describedby="emailHelp" placeholder="New Password">
                                                    <span class="input-group-text"><i class="fa fa-eye" id="newPwdIcon" aria-hidden="true" onclick="showNewPwd()"></i></span>
                                                </div>
                                                <small id="new-password-span" class="form-text text-danger"></small>
                                                @if ($errors->has('newPassword'))
                                                <span class="text-danger">{{ $errors->first('newPassword') }}</span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Confirm New Password</label>
                                                <div class="input-group mb-3">
                                                    <input type="password" class="form-control" id="confirmNewPassword" name="confirmNewPassword" placeholder="Confirm New Password">
                                                    <span class="input-group-text">
                                                        <i class="fa fa-eye" id="cnfmNewPwdIcon" aria-hidden="true" onclick="showCnfmNewPwd()"></i>
                                                    </span>
                                                </div>
                                                <small id="confirm-new-password-span" class="form-text text-danger"></small>
                                                @if ($errors->has('confirmNewPassword'))
                                                <span class="text-danger">{{ $errors->first('confirmNewPassword') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <ul class="list-group mt-3">
                                            <li class="list-group-item active">Password Requirements</li>
                                            <li class="list-group-item"><i class="fa fa-times text-danger" id="lengthIcon" aria-hidden="true"></i> &nbsp;Must contain at least 6 characters</li>
                                            <li class="list-group-item"><i class="fa fa-times text-danger" id="nmbrIcon" aria-hidden="true"></i> &nbsp;Must contain at least 1 numeric value (1-9)</li>
                                            <li class="list-group-item"><i class="fa fa-times text-danger" id="ucIcon" aria-hidden="true"></i> &nbsp;Must contain at least 1 uppercase character</li>
                                            <li class="list-group-item"><i class="fa fa-times text-danger" id="lcIcon" aria-hidden="true"></i> &nbsp;Must contain at least 1 lowercase character</li>
                                            <li class="list-group-item"><i class="fa fa-times text-danger" id="spCharIcon" aria-hidden="true"></i> &nbsp;Must contain at least 1 special character (!@#$%^&*<>+_-=?")</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <input style="margin-top: 33px;" class="btn btn-primary btn-block btn-lg mt-40 waves-effect waves-classic" type="submit" id="updateUserPassword" value="Confirm">
                                </form>

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
    $('#updateUserPassword').click(function() {

        let u_cnfrmPwd = $('#confirmNewPassword').val();
        let u_newPwd = $('#newPassword').val();

        document.getElementById("new-password-span").innerHTML = "";
        document.getElementById("confirm-new-password-span").innerHTML = "";
        $("#responseMsgId").html("");
        $("#responseMsgId").removeClass();


        if (u_newPwd == "") {
            document.getElementById("new-password-span").innerHTML = " Please fill the New Password field ";
            return false;
        }

        if (u_cnfrmPwd == "") {
            document.getElementById("confirm-new-password-span").innerHTML = "Please fill the Confirm New Password field ";
            return false;
        }


        if (u_newPwd == "") {
            document.getElementById("new-password-span").innerHTML = " Please fill the New Password field ";
            return false;
        } else {
            var number = /([0-9])/;
            var alphabets = /([A-Z])/;
            var ALPHABETS = /([a-z])/;
            var special_characters = /([~,!,@,#,$,%,^,&,*,-,_,+,=,?,>,<])/;
            var password = $('#newPassword').val().trim();
            $("#new-password-span").html("").css("color", "green");
            if (password.length < 6) {
                $('#new-password-span').removeClass();
                $('#new-password-span').addClass('text-danger');
                $('#lengthIcon').removeClass();
                $('#lengthIcon').addClass('fa fa-times text-success');
                $('#new-password-span').html("Weak");
                $("#responseMsgId").addClass("alert alert-danger");
                $("#responseMsgId").html("Password requirements are not met");
                return false;
            } else {
                if (password.match(number) && password.match(alphabets) && password.match(ALPHABETS) && password.match(special_characters)) {
                    $('#new-password-span').removeClass();
                    $('#new-password-span').addClass('text-success');
                    $('#new-password-span').html("Strong");
                } else {
                    $('#new-password-span').removeClass();
                    $('#new-password-span').addClass('text-info');
                    $('#new-password-span').html("Medium");
                    $("#responseMsgId").addClass("alert alert-danger");
                    $("#responseMsgId").html("Password requirements are not met");
                    return false;
                }
            }
            // document.getElementById("new-password-span").innerHTML = "";
        }
        if (u_cnfrmPwd == "") {
            document.getElementById("confirm-new-password-span").innerHTML = "Please fill the Confirm New Password field ";
            return false;
        } else {
            if (u_cnfrmPwd == u_newPwd) {
                document.getElementById("confirm-new-password-span").innerHTML = "";
            } else {
                document.getElementById("confirm-new-password-span").innerHTML = "New Password and Confirm New Password Mismatch";
                return false;
            }
        }

    });

    $("#newPassword").keyup(function(event) {
        var number = /([0-9])/;
        var ALPHABETS = /([A-Z])/;
        var alphabets = /([a-z])/;
        var special_characters = /([~,!,@,#,$,%,^,&,*,-,_,+,=,?,>,<])/;
        var password = $('#newPassword').val().trim();
        $("#new-password-span").html("").css("color", "green");

        $('#new-password-span').removeClass();
        // $('#new-password-span').addClass('text-danger');
        // $('#new-password-span').html("Weak");

        $('#nmbrIcon').removeClass();
        $('#ucIcon').removeClass();
        $('#lengthIcon').removeClass();
        $('#spCharIcon').removeClass();
        $('#lcIcon').removeClass();
        $('#nmbrIcon').addClass('fa fa-times text-danger');
        $('#lengthIcon').addClass('fa fa-check text-success');
        $('#ucIcon').addClass('fa fa-times text-danger');
        $('#lcIcon').addClass('fa fa-times text-danger');
        $('#spCharIcon').addClass('fa fa-times text-danger');
        $("#responseMsgId").html("");
        $("#responseMsgId").removeClass();
        
        if (password.match(ALPHABETS)) {
            $('#ucIcon').removeClass();
            $('#ucIcon').addClass('fa fa-check text-success');
            $('#new-password-span').html("Strong");
        }

        if (password.match(alphabets)) {
            $('#lcIcon').removeClass();
            $('#lcIcon').addClass('fa fa-check text-success');
        }

        if (password.match(special_characters)) {
            $('#spCharIcon').removeClass();
            $('#spCharIcon').addClass('fa fa-check text-success');
        }

        if (password.match(number)) {
            $('#nmbrIcon').removeClass();
            $('#nmbrIcon').addClass('fa fa-check text-success');
        }
        if (password.match(number) && password.match(alphabets) && password.match(ALPHABETS) && password.match(special_characters)) {
            $('#new-password-span').removeClass();
            $('#new-password-span').addClass('text-success');
            $('#new-password-span').html("Strong");
        }else{
            if (password.length < 6) {
            $('#lengthIcon').removeClass();
            $('#lengthIcon').addClass('fa fa-times text-danger');
            $('#new-password-span').removeClass();
            $('#new-password-span').addClass('text-danger');
            $('#new-password-span').html("Weak");
            }
            else{
                $('#new-password-span').removeClass();
                $('#new-password-span').addClass('text-info');
                $('#new-password-span').html("Medium");
            }
        }
    });


    function showNewPwd() {
        var passInput = $("#newPassword");
        if (passInput.attr('type') === 'password') {
            $('#newPwdIcon').addClass('fa fa-eye-slash');
            passInput.attr('type', 'text');

        } else {
            passInput.attr('type', 'password');
            $('#newPwdIcon').removeClass();
            $('#newPwdIcon').addClass('fa fa-eye');
        }
    }

    function showCnfmNewPwd() {
        var passInput = $("#confirmNewPassword");
        if (passInput.attr('type') === 'password') {
            $('#cnfmNewPwdIcon').addClass('fa fa-eye-slash');
            passInput.attr('type', 'text');

        } else {
            passInput.attr('type', 'password');
            $('#cnfmNewPwdIcon').removeClass();
            $('#cnfmNewPwdIcon').addClass('fa fa-eye');
        }
    }
</script>
@endsection