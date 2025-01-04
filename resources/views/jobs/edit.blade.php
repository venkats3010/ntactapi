<?php 

$user = $user['user'][0];
//print_r($user['id']);
?>
<div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                   
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
                            
                            <div class="form-group col-md-6 mb-3">
                                    <label for="phone" class="form-label">{{ __('Phone') }}</label>
                                        <div class="col-sm-12 col-md-12 input-group">
                                          <select class="form-select" name="countryCode" id="countryCode" style="max-width:80px;padding: 3px;" value="{{ $user['countrycode'] ?? 1 }}">
                                            <option value="">Select Country Code</option> 
                                            <option value="1" <?php if ($user['countrycode'] == 1) echo 'selected'; ?>>+1</option>
                                            <option value="91" <?php if ($user['countrycode'] == 91) echo 'selected'; ?>>+91 </option>
                                            
                                            <!--<option value="1" <?php if ($user['countrycode'] == 1) echo 'selected'; ?>>(+1) United States & Canada</option>
                                            <option value="44" <?php if ($user['countrycode'] == 44) echo 'selected'; ?>>(+44) United Kingdom </option>
                                            <option value="91" <?php if ($user['countrycode'] == 91) echo 'selected'; ?>>(+91) India </option>
                                            <option value="61" <?php if ($user['countrycode'] == 61) echo 'selected'; ?>>(+61) Australia </option>
                                            <option value="64" <?php if ($user['countrycode'] == 64) echo 'selected'; ?>>(+64) New Zealand </option>
                                            <option value="65" <?php if ($user['countrycode'] == 65) echo 'selected'; ?>>(+65) Singapore </option>
                                            <option value="971" <?php if ($user['countrycode'] == 971) echo 'selected'; ?>>(+971) United Arab Emirates </option>
                                            <option value="52" <?php if ($user['countrycode'] == 52) echo 'selected'; ?>>(+52) Mexico </option>
                                            <option value="966" <?php if ($user['countrycode'] == 966) echo 'selected'; ?>>(+966) Saudi Arabia </option>
                                            <option value="55" <?php if ($user['countrycode'] == 55) echo 'selected'; ?>>(+55) Brazil </option>
                                            <option value="7" <?php if ($user['countrycode'] == 7) echo 'selected'; ?>>(+7) Russia </option>
                                            <option value="86" <?php if ($user['countrycode'] == 861) echo 'selected'; ?>>(+86) China </option>
                                            <option value="34" <?php if ($user['countrycode'] == 34) echo 'selected'; ?>>(+34) Spain </option>
                                            <option value="39" <?php if ($user['countrycode'] == 39) echo 'selected'; ?>>(+39) Italy </option>-->
                                            
                                          </select>
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

                        <div class="row">
                           
                            <div class="form-group col-md-6">
                                <label class="form-label">{{ __('Role') }}</label>
                                <div class="col-sm-12 col-md-12">
                                    <select class="form-control" name="role" id="role" required>
                                        <option value="">Select</option>
                                        @if (isset($roles) && $roles['status'] == 200)
                                        @foreach ($roles['data'] as $key => $role)
                                        <option value="{{ $role['id'] }}" <?=($user['role']==$role['id'])?"selected":"" ?>>{{ $role['role'] }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                    <span id="erole_error_msg" class="text-danger font-weight-bold"></span>
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                
                            </div>

                        </div>


            <div class="row" id="role_base">
                <div id="role_based">
                    <h5> Privileges</h5>
                </div>
                @if ($modules['status'] == 200)
                <div class="row" style="margin-left: 15px;margin-bottom: 20px;">
<?php 
$permissions = json_decode($user['permissions'], true);
?>
                @foreach ($modules['modules'] as $key => $module)                    
                    <div class="col-md-3 module m-2">
                        <label class="form-check form-switch form-switch-sm form-check-custom form-check-solid">
                            <input 
                                class="form-check-input uncheckpri per_Checkbox_{{ $module['id'] }}" 
                                name="per_checkbox[]" 
                                type="checkbox" 
                                value="{{ $module['id'] }}" 
                                id="per_checkbox" 
                                @if(in_array($module['id'], is_array($permissions) ? $permissions : [])) checked @endif
                            >
                            <span class="form-check-label fw-bold text-gray-400" for="per_checkbox_{{ $module['id'] }}"></span>
                            {{ $module['title'] }}
                        </label>
                    </div>                   
                @endforeach


                </div>
                @endif
            </div>

                        <div class="row">
                            <div class="form-group col-md-12">
                                <label class="form-label"></label>
                                <div class="col-sm-12 col-md-12 ">
                                    <button class="btn btn-primary  mt-2 btn-submit updateUser"><span>{{ __('Save') }}</span></button>
                                </div>
                            </div>
                        </div>
                    
                </div>
            </div>
        </div>
    </div>

<script src="{{ my_asset('/assets/js/intlTelInput.js') }}"></script>
<script>

//var telInput = $("#phone");
//telInput.intlTelInput("destroy");


</script>

<script>

/*
            // Define a mapping from numeric country calling codes to ISO country codes
            var countryCodeMapping = {
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

            // Example user country calling code
            //const userCountryCode = '91'; // Replace this with your dynamic code
            var userCountryCode = "{{ $user['countrycode'] ?? 1 }}";
            console.log(userCountryCode);
            //telInput.intlTelInput("destroy");
            // Initialize the intl-tel-input plugin
            var telInput = $("#phone").intlTelInput({
                initialCountry: 'auto',
                preferredCountries: ['us', 'ca', 'gb', 'in', 'au', 'nz', 'sg', 'ae', 'mx', 'sa', 'br', 'ru', 'cn', 'es', 'it'],
                autoPlaceholder: 'aggressive',
                utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/12.1.6/js/utils.js",
                geoIpLookup: function(callback) {
                    fetch('https://ipinfo.io/json', { cache: 'reload' })
                        .then(response => {
                            if (response.ok) {
                                return response.json();
                            }
                            throw new Error('Failed: ' + response.status);
                        })
                        .then(ipjson => {
                            // Use the country code from the mapping or default to IP lookup
                            var mcountryCode = countryCodeMapping[userCountryCode] || ipjson.country;
                            callback(mcountryCode);
                        })
                        .catch(e => {
                            callback('us'); // Fallback to US
                        });
                }
            });

            // Optionally set the country based on userCountryCode after initialization
            if (userCountryCode) {
                var isoCountryCode = countryCodeMapping[userCountryCode];
                console.log(isoCountryCode);
                if (isoCountryCode) {
                    $('.selected-flag').attr('title', userCountryCode);
                    $('.iti-flag').addClass(isoCountryCode);
                    $('.flag-box').addClass('d-none');
                    //telInput.intlTelInput("setCountry", isoCountryCode);
                }
            }
    */
    </script>


    <script>
        $('.updateUser').on('click', function() {
            var rowid = $('#rowid').val();
            var firstname = $('#firstname').val();
            var lastname = $('#lastname').val();
            var phone = $('#phone').val();
            var email = $('#email').val();
            var gender = $('#gender').val();
            var status = $('#status').val();
            var role = $('#role').val();
            var orgid = 1;
            i = 0;
            var permissions = [];
            $('#per_checkbox:checked').each(function() {
                permissions[i++] = $(this).val();
            });
			if (firstname == "") {
				toastr.error("Please Enter First Name ");
				return false;
			}
	   		if (lastname == "") {
				toastr.error("Please Enter Lastname ");
				return false;
			}
			let countrycode = $('#countryCode').val();	
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
            if (role == "") {
				toastr.error("Please select role ");
				return false;
			}		
			if (status == "") {
				toastr.error("Please select status ");
				return false;
			}
           		
            $('.updateUser').prop('disabled', true).html('Please Wait...');

            $.ajax({
                    url: "{{ url('users/update') }}",
                    type: 'post',
                    data: {  _token: "{{ csrf_token() }}", firstname:firstname, lastname:lastname, phone:phone, email:email, gender:gender, role:role, status:status, permissions: permissions,rowid:rowid, orgid:orgid, countrycode:countrycode },
                    dataType: 'json',
                    success: function(res) {
                        console.log(res);
						if (res.result == "true") {
							toastr.success(res.message);
							$("#myoffcanvasRight .btn-close-icon").click();						
							location.reload();
						} else {
							toastr.error(res.message);
							$('.updateUser').prop('disabled', false);
							$('.updateUser').html('Save');
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