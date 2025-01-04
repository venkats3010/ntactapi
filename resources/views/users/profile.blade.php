@extends($page == 'tpurl' ? 'tpurl.layouts.app' : 'layouts.app')

@section('module-title')
    {{ __('Edit ') }}
@endsection
<?php 
$user = $user['user'][0];

?>
  <style>

    .user-profile-image {
      width: 100px;
      height: 100px;
      border-radius: 50%;
      object-fit: cover;
      margin-bottom: 10px; /* Add space between image and form */
    }
    
.custom-img {
    position: absolute;
    z-index: 9999;
    width: 100px;
      height: 100px;
      border-radius: 50%;
      object-fit: cover;
}
.profile-container {
    display: flex;
    justify-content: center; /* Horizontally center the image */
    align-items: center;     /* Vertically center the image */
    margin-top: -50px;       /* Optional: Adjust the margin if needed */
}
.intl-tel-input {
	width:100%; 
}
</style>
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/12.1.6/css/intlTelInput.css'/>
{{--
@if($page != 'tpurl')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ url('profile/edit', ['tpurl' => $page]) }}">{{ __('Profile') }}</a></li>
    <li class="breadcrumb-item">{{ __('Edit Profile') }}</li>
@endsection
@else
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('profile/edit', ['tpurl' => $page]) }}">{{ __('Profile') }}</a></li>
    @endsection
@endif --}}
@section('content')
<a href="{{ url('/dashboard') }}" class="btn btn-lg btn-primary btn-icon mb-2 fs-5" style="margin-top:-10px;">
    <i class="mdi mdi-home-circle"></i> {{ __('Home') }}
</a>
  <!--<div class="container">
     <div class="profile-container" style="text-align: center;margin: -55px 0px 10px 0px;">
      <img src="{{ my_asset('assets/images/profilePictures/' . $user['profilepicture']) }}" alt="Profile Image" class="user-profile-image">
    </div>-->
    

<div class="row" style="">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        
                        <div class="col-md-12 d-flex justify-content-center" style="margin-bottom: 30px;">
                            <div class="profile-container p-2" style="margin-top: -30px;">
                                <?php 
                                $srtImg = my_asset('assets/images/profilePictures/' . $user['profilepicture']);
                                if (file_exists($srtImg)) {
                                ?>
                                <img src="{{ $srtImg }}" alt="Profile Image" class="custom-img">
                                <?php }else{ ?>
                                <img src="{{ my_asset('assets/images/avatar.png' ) }}" alt="Profile Image" class="custom-img">
                                <?php } ?>
                            </div>
                        </div>
                        
                    </div>

                    <input type="hidden" name="rowid" id="rowid" class="form-control" value="{{$user['id']}}">
                        
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="form-label">{{ __('Firstname') }}</label>
                                <div class="col-sm-12 col-md-12">
                                    <input type="text" placeholder="{{ __('Firstname') }}" name="firstname" id="firstname" class="form-control" value="{{$user['firstname']}}" autofocus>
                                    <span class="firstnameErrmsg"></span>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                            <label class="form-label">{{ __('Lastname') }}</label>
                                <div class="col-sm-12 col-md-12">
                                    <input type="text" placeholder="{{ __('Lastname') }}" name="lastname" id="lastname" class="form-control" value="{{$user['lastname']}}" autofocus>
                                    <span class="lastnameErrmsg"></span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="form-label">{{ __('Phone') }}</label>
                                <div class="col-sm-12 col-md-12">
                                <input type="text" class="form-control" id="phone" name="phone" placeholder="Enter phone no." onkeypress="return isNumber(event)" onpaste="setTimeout(onlyNumbers.bind(null,this),100)" maxlength="10" value="{{$user['phone']}}">
                                <span id="phoneErrmsg" class="text-danger font-weight-bold"></span>                                    
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label class="form-label">{{ __('Email') }}</label>
                                <div class="col-sm-12 col-md-12">
                                    <input type="text" name="email" id="email"  placeholder="{{ __('Email') }}" class="form-control" value="{{$user['email']}}" required>
                                    <span class="emailErrmsg"></span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="form-label">{{ __('Gender') }}</label>
                                <div class="col-sm-12 col-md-12">
                                <select class="form-control field_type" name="gender" id="gender" required>
                                        <option value="M" <?=($user['gender']=="M")?"selected":"" ?>>{{ __('Male') }}</option>
                                        <option value="F" <?=($user['gender']=="F")?"selected":"" ?>>{{ __('Female') }}</option>
                                    </select>                                        
                                    <span class="statusErrmsg"></span>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label">{{ __('Status') }}</label>
                                <div class="col-sm-12 col-md-12">
                                    <select class="form-control field_type" name="status" id="status" required>
                                        <option value="A" <?=($user['status']=="A")?"selected":"" ?>>{{ __('Active') }}</option>
                                        <option value="I" <?=($user['status']=="I")?"selected":"" ?>>{{ __('Inactive') }}</option>
                                    </select>                                        
                                    <span class="statusErrmsg"></span>
                                </div>
                            </div>
                        </div>

                        
                        
                            <div class="col-lg-12">
                                <button class="btn btn-primary mt-2 btn-submit updateProfile"><span>{{ __('Save') }}</span></button>
                            </div>
                            
                    
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
     
								<div class="row">
                                <div class="col-lg-4 mt-2">
                                    <div class="form-group">
                                        <label class="form-control-label" for="currentpassword">
                                            <span class="required">Current Password</span>
                                        </label>
                                        <div class="input-group">
                                            <input type="password" id="currentpassword" name="currentpassword" class="form-control form-control-alternative" placeholder="Current Password" autocomplete="new-password" required>
                                            <div class="input-group-append">
                                                <span class="input-group-text h-100">
                                                    <i class="mdi mdi-eye" id="toggleCurrentPassword"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <span id="ecurrentpassword" class="text-danger font-weight-bold"></span>
                                    </div>
                                </div>

                                <div class="col-lg-4 mt-2">
                                    <div class="form-group">
                                        <label class="form-control-label" for="newpassword">
                                            <span class="required">New Password</span>
                                        </label>
                                        <div class="input-group">
                                            <input type="password" id="newpassword" name="newpassword" class="form-control form-control-alternative" placeholder="New Password" required>
                                            <div class="input-group-append">
                                                <span class="input-group-text h-100">
                                                    <i class="mdi mdi-eye" id="toggleNewPassword"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <span id="enewpassword" class="text-danger font-weight-bold"></span>
                                    </div>
                                </div>

                                <div class="col-lg-4 mt-2">
                                    <div class="form-group">
                                        <label class="form-control-label" for="confirmpassword">
                                            <span class="required">Confirm Password</span>
                                        </label>
                                        <div class="input-group">
                                            <input type="password" id="confirmpassword" name="confirmpassword" class="form-control form-control-alternative" placeholder="Current Password" required>
                                            <div class="input-group-append">
                                                <span class="input-group-text h-100">
                                                    <i class="mdi mdi-eye" id="toggleConfirmPassword"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <span id="econfirmpassword" class="text-danger font-weight-bold"></span>
                                    </div>

                                </div>
                            </div>
                        <!--end::row body-->

                    <div class="col-lg-12">
                        <span class="btn btn-primary updatePassword" type="submit" id="updatePassword">Update Password</span>
                    </div>

							
							
                </div>
            </div>
        </div>
    </div>

@endsection

@section('jscontent')
<script src="{{ my_asset('/assets/js/intlTelInput.js') }}"></script>
<script>
let telInput = $("#phone")
var userCountryCode = "{{ $user['countrycode'] ?? 1 }}";
    const countryCodeMapping = {
        '1': 'us',  // United States
        '1': 'ca',  // Canada
        '44': 'gb', // United Kingdom
        '91': 'in', // India
        '61': 'au', // Australia
        '64': 'nz', // New Zealand
        '65': 'sg', // Singapore
        '971': 'ae', // United Arab Emirates
        '52': 'mx', // Mexico
        '966': 'sa', // Saudi Arabia
        '55': 'br', // Brazil
        '7': 'ru',  // Russia
        '86': 'cn', // China
        '34': 'es', // Spain
        '39': 'it'  // Italy
    };
            
// initialize
telInput.intlTelInput({
    initialCountry: 'auto',
    preferredCountries: ['us','ca','gb','in','au','nz','sg','ae','mx','sa','br','ru','cn','es','it'],
    autoPlaceholder: 'aggressive',
    utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/12.1.6/js/utils.js",
    geoIpLookup: function(callback) {
        fetch('https://ipinfo.io/json', {
            cache: 'reload'
        }).then(response => {
            if ( response.ok ) {
                 return response.json()
            }
            throw new Error('Failed: ' + response.status)
        }).then(ipjson => {
            callback(ipjson.country)
        }).catch(e => {
            callback('us')
        })
    }
})
    if (userCountryCode) {
        const isoCountryCode = countryCodeMapping[userCountryCode];
        console.log(isoCountryCode);
        if (isoCountryCode) {
            $('.selected-flag').attr('title', userCountryCode);
            $('.iti-flag').addClass(isoCountryCode);
            $('.flag-box').addClass('d-none');
            //telInput.intlTelInput("setCountry", isoCountryCode);
        }
    }
</script>
<script>
        $('.updateProfile').on('click', function() {
            var rowid = $('#rowid').val();
            var firstname = $('#firstname').val();
            var lastname = $('#lastname').val();
            var phone = $('#phone').val();
            var email = $('#email').val();
            var gender = $('#gender').val();
            var status = $('#status').val();
            var orgid = 1;
           
			if (firstname == "") {
				toastr.error("Please Enter First Name ");
				return false;
			}
	   		if (lastname == "") {
				toastr.error("Please Enter Lastname ");
				return false;
			}
			let countrycode = $('.selected-flag').attr('title');	
            if (!countrycode) {
				toastr.error("Please select country code ");
				$('#phone').focus();
				return false;
            }
			if (phone == "" || phone.trim() == "" || phone.length != 10) {
				toastr.error("Please Enter Valid Phone No. ");
				$('#phone').focus();
				return false;
			}			
			var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
			if (email === "" || !regex.test(email)) {
				toastr.error("Please Enter Email  ");
				$('#email').focus();
				return false;
			}	
		
			if (status == "") {
				toastr.error("Please select status ");
				return false;
			}
           		
            $('.updateProfile').prop('disabled', true).html('Please Wait...');

            $.ajax({
                    url: "{{ url('profile/update') }}",
                    type: 'post',
                    data: {  _token: "{{ csrf_token() }}", firstname:firstname, lastname:lastname, phone:phone, email:email, gender:gender, status:status, rowid:rowid, orgid:orgid, countrycode:countrycode },
                    dataType: 'json',
                    success: function(res) {
                        console.log(res);
						if (res.result == 'true') {
							toastr.success(res.message);
                            $('.updateProfile').prop('disabled', false);
							$('.updateProfile').html('Save');
							
						} else {
							toastr.error(res.message);
							$('.updateProfile').prop('disabled', false);
							$('.updateProfile').html('Save');
						}
						
                    }
                });
        });

function isNumber(evt) {
	evt = (evt) ? evt : window.event;
	var charCode = (evt.which) ? evt.which : evt.keyCode;
	if (charCode > 31 && (charCode < 48 || charCode > 57)) {
		return false;
	}
  return true;
}

$('#role, #editrole').change(function() {
    $('.uncheckpri').prop("checked", false);
    if($(this).val() === "1"){
        $('.uncheckpri').prop("checked", true);
    } 
});


</script>
<script>
$('.updatePassword').on('click', function() {
		let userid = $('#rowid').val();
        let currentpassword = $('#currentpassword').val();
        let newpassword = $('#newpassword').val();
        let confirmpassword = $('#confirmpassword').val();
        if(currentpassword == ""){
            toastr.error("Please enter Current password.");
            return false;
        }
        if(newpassword == ""){
            toastr.error("Please enter New password.");
            return false;
        }
        if(confirmpassword == ""){
            toastr.error("Please enter Confirmation password.");
            return false;
        }
        if(newpassword != confirmpassword){
            toastr.error("The New password and confirmation password do not match.");
            return false;
        }

        $('#loader').show();
        $.ajax({
            type: "post",
            url: "{{ url('users/changepassword') }}",
            data: {
                _token: "{{ csrf_token() }}",
                "userid": userid,
                "currentpassword": currentpassword,
                "newpassword": newpassword,
                "confirmpassword": confirmpassword
            },
            success: function(response) {
                var res = jQuery.parseJSON(response);
                $('#loader').hide();
                if(res.status == 200){
                    toastr.success(res.message); 
                }else{
                    toastr.error(res.message); 
                }
            },
            error: function(response) {
                var res = jQuery.parseJSON(response);
                $('#loader').hide();
                toastr.error(res.message);
            }
        })

});        
</script>

<script>
    $(document).ready(function () {
        $('#toggleCurrentPassword').click(function () {
            const currentPasswordField = $('#currentpassword');
            const type = currentPasswordField.attr('type') === 'password' ? 'text' : 'password';
            currentPasswordField.attr('type', type);
            $(this).toggleClass('mdi-eye-off');
        });

        $('#toggleNewPassword').click(function () {
            const newPasswordField = $('#newpassword');
            const type = newPasswordField.attr('type') === 'password' ? 'text' : 'password';
            newPasswordField.attr('type', type);
            $(this).toggleClass('mdi-eye-off');
        });

        $('#toggleConfirmPassword').click(function () {
            const confirmPasswordField = $('#confirmpassword');
            const type = confirmPasswordField.attr('type') === 'password' ? 'text' : 'password';
            confirmPasswordField.attr('type', type);
            $(this).toggleClass('mdi-eye-off');
        });
    });
</script>
<script>
	function displayImage() {
		var preview = document.querySelector(".user-profile-image");
		
		var file    = document.querySelector('input[type=file]').files[0];
		var reader  = new FileReader();

		reader.addEventListener("load", function () {
			preview.src = reader.result;
		}, false);

		if (file) {
			reader.readAsDataURL(file);
			uploadprofileImage();
		}
	}

function uploadprofileImage() {
    var formData = new FormData($('#upload_profile_pic')[0]);
    $('#loader').show();
    $.ajax({
        type: 'POST',
        url: baseurl + '/profile/upload_profile_pic',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function (res) {
            $('#loader').hide();            
            //res = JSON.parse(res);
            console.log("IMAGE "+JSON.stringify(res));
            console.log("IMAGE "+res);
            console.log(typeof res);
            console.log("IMAGE "+res['status']);
            console.log(Array.isArray(res));
            if (res.status == 200) {
                toastr.success(res.message);
				$(".getProfilePicture").click();
            } else {
                toastr.error(res.message);
            }
        },
        error: function (res) {
            $('#loader').hide();
            toastr.error(res.message);
        }
    });
}
</script>
@endsection