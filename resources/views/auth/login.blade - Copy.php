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

<section class="bg-secondary" style="padding:50px 0; height:100vh">
    <div class="container py-5">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col col-xl-6">
                <div class="card" style="border-radius: 1rem;">
                    <div class="row g-0">
                        
                        <div class="d-flex align-items-center">
                            <div class="card-body p-4 p-lg-5 text-black">
                                <form method="post" action="#" autocomplete="off">
                                    <span class="text-danger font-weight-bold">
                                        {{ session()->get('errorUser') }}
                                    </span>
                                   
                                                                        
                                    <div class="d-flex justify-content-center">
                                        <div class="spinner-border" id="spinner" role="status" style="display:none !important;">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                    </div>

                                    
                                    <div id="userCredDiv" class="">
                                    <div class="alert alert-warning d-flex align-items-center d-none" id="loginFailedErrorMsgMainDiv" role="alert">                                         
                                        <i class="bi bi-exclamation-triangle fw-bold"></i> &nbsp;&nbsp;
                                        <h5 class="fw-bold text-danger text-center" id="loginFailedErrorMsg">Your error message here</h5>
                                    </div>

                                       
                                            <h5 class="fw-bold mb-3 pb-3 text-primary text-center" id="ulHeading" style="letter-spacing: 1px;">User Login</h5>
                                        
                                    
                                        <div class="form-outline mb-4">
                                            <label class="form-label" for="email">Username</label>
                                            <input type="text" class="form-control form-control-lg" name="username" id="username" placeholder="Enter Username">
                                            <span id="usernamemsg" class="text-danger font-weight-bold"></span>
                                        </div>
                                        <div class="form-outline mb-4">
                                            <label class="form-label" for="password">Password</label>
                                            <input type="password" class="form-control form-control-lg" placeholder="Enter Password" name="password" id="password">
                                            <span id="passwordmsg" class="text-danger font-weight-bold"></span>
                                        </div>
                                    
                                        <div class="pt-1 mb-4">
                                            <button class="btn btn-primary btn-lg btn-block w-100" type="button" id="checkLogin">Login</button>
                                        </div>
                                    </div>
                            
                                    <div id="skipOrUpdPwdDiv" class="d-none">
                                        
                                        <div class="alert alert-warning d-flex align-items-center" role="alert">                                         
                                            <i class="bi bi-exclamation-triangle fw-bold"></i> &nbsp;<h5 class="fw-bold text-danger text-center" id="noOfDaysText" style="margin-top: 10px;letter-spacing: 1px;letter-spacing: 1px;"></h5>
                                        </div>


                                        <div class="form-outline mb-4">
                                        </div>
                                    
                                        <div class="pt-1 mb-4">

                                        </div>
                                    </div>

                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>





</section>
@endsection

@section('jscontent')
<script>
   $("#checkLogin").click(function() {
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
@endsection