<style>
    #qSearchList ul {
        margin-top: 20px;
        background: #ddd;
        color: #000;
    }

    #qSearchList ul>li {
        padding: 6px 10px;
        cursor: pointer;
        color: black;
        border-bottom: 0.5px solid #fff;
        font-size: 14px;
    }

    #qSearchList ul>li:hover {
        background: rgba(209, 232, 246, 1);
        color: #000;
    }

    #qSearchList {
        position: absolute;
        width: 100%;
		z-index:9999;
    }

    .qsearch-styled {
        padding-left: 0;
        list-style: none;
    }
</style>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-0">
                   
                    <form role="form" id="create-task" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">     
                    <input type="hidden" name="userid" id="userid" value="<?php echo $response['userid'];?>">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="form-label">{{ __('Subject') }}</label>
                                <div class="form-icon-user">
                                    <input type="text" placeholder="{{ __('Title') }}" name="subject" id="subject" class="form-control {{ $errors->has('subject') ? ' is-invalid' : '' }}" autofocus>
                                    <div class="invalid-feedback">
                                        {{ $errors->first('subject') }}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label">{{ __('Category') }}</label>
                                <div class="form-icon-user">
                                    <select class="form-control field_type" name="category" id="category" placeholder="{{ __('Category') }}" required>
                                            <option value="">Select </option>
                                            <option value="1">Design</option>
                                            <option value="2">Development</option>
                                            <option value="3">HR</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        {{ $errors->first('category') }}
                                    </div>
                                </div>
                            </div>
    
    
                            <div class="form-group col-md-6">
                                <label class="form-label">{{ __('Priority') }}</label>
                                <div class="form-icon-user">
                                    <select class="form-control field_type" name="severity" id="severity" required>
                                            <option value="">Select Priority</option>
                                            <option value="1">High</option>
                                            <option value="2">Medium</option>
                                            <option value="3">Low</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        {{ $errors->first('priority') }}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label class="form-label">{{ __('Assigned to') }}</label>
                                    <div class="form-icon-user">
                                        {{--<select class="form-control field_type" name="assignto" id="assignto" required>
                                            <option value="">Select Assigned to</option>
												@if(isset($users['users']) && !empty($users['users'])>0)
													@php
														$isSelected = (count($users['users']) == 1)?'selected':'';
													@endphp
                                                @foreach($users['users'] as $user)
												@if(isset($user['status']) && $user['status'] == 'A')
                                                <option value="{{$user['id']}}" {{$isSelected}}>{{$user['firstname']}}</option>
												@endif
                                                @endforeach
												@endif
                                        </select> --}}
                                        
                                            <input type="hidden" class="form-control" name="assignto" id="assignto" >
                                            <input type="hidden" class="form-control" name="assigncountrycode" id="assigncountrycode" >
                                            <input type="hidden" class="form-control" name="assigntophone" id="assigntophone" >
                                            <input type="text" class="form-control" id="assigntoname" placeholder="Start typing a user..." autocomplete="off">
                                            <div id="qSearchList" style="color: #000;" class="my-1"></div>

                                        <div class="invalid-feedback">
                                            {{ $errors->first('assignedto') }}
                                        </div>
                                    </div>
                            </div>

                            

                            
                                <div class="form-group col-md-12">
                                    <label class="form-label">{{ __('Comments') }}</label>
                                    <div class="form-icon-user">
                                        <textarea name="details" id="details"  placeholder="{{ __('Comments') }}" class="form-control {{ $errors->has('details') ? ' is-invalid': '' }}" required></textarea>
                                        <div class="invalid-feedback">
                                            {{ $errors->first('details') }}
                                        </div>
                                    </div>
                                </div>
                            
                            </div>

                                <div class="form-group col-md-12">
                                    <button type="button" class="btn btn-primary mt-2 AddDocs"><span>{{ __('Add Documnets') }}</span></button>
                                </div>
                            
                                <div class="row">
                                    
                                        <div id="documents"></div>
                                  
                                </div>
                                {{--<!--<div class="form-group col-md-12">
                                    <label class="form-label">{{ __('File Attachment') }}</label>
                                    <div class="form-icon-user col-md-6">
                                        <input type="file" name="files[]" id="files"  placeholder="{{ __('File Attachment') }}" class="form-control">
                                    </div>
                                    <div class="form-icon-user col-md-6">
                                        <input type="text" name="filename[]" id="filename"  placeholder="{{ __('File Name') }}" class="form-control">
                                    </div>
                                    <div class="form-icon-user col-md-6">
                                        <input type="text" name="filecomment[]" id="filecomment"  placeholder="{{ __('Comments') }}" class="form-control">
                                    </div>
                                </div>-->--}}
                            

                        

                        <div class="row">
                            <div class="form-group col-md-12">
                                <label class="form-label"></label>
                                <div class="form-icon-user text-end">
                                    <button class="btn btn-primary btn-block mt-2 btn-submit createTask"><span>{{ __('Save') }}</span></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


<script>

    $('#create-task').on('submit',(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $('#loader').show();
        $.ajax({
            type:'POST',
            url:  baseurl + '/tasks/store',
            data:formData,
            cache:false,
            contentType: false,
            processData: false,
            success:function(res){
                console.log("Task_Create 1 "+JSON.stringify(res));
                console.log("Task_Create 2"+JSON.stringify(res.message));
                $('#loader').hide();
                //res = JSON.parse(res);
                 console.log("Task_Create 3"+JSON.stringify(res.message));
                if(res.status == 200){
                    console.log("success");   
                    toastr.success(res.message);
                    $("#myoffcanvasRight .btn-close").click();
                    location.reload();
                }else{
                    toastr.error(res.message);
                }
            },
            error: function(res){
                $('#loader').hide();
                toastr.error('Failed');
            }
        });
    }));
    
 
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
$(document).ready(function() {
    $('.AddDocs').on('click', function(e) {
        e.preventDefault();
        // Create the new document fields HTML
        const newDocHtml = `
            <div class="row document-entry m-2 p-2">
                <div class="col-md-3">
                    <input type="file" name="documents[files][]" placeholder="{{ __('File Attachment') }}" class="form-control" accept="image/jpeg,image/gif,image/png,application/pdf">
                </div>
                <!--<div class="col-md-3 d-none">
                    <input type="text" name="documents[filename][]" placeholder="{{ __('File Name') }}" class="form-control" oninput="validateInput(event)">
                </div>-->
                <div class="col-md-3">
                    <input type="text" name="documents[filecomment][]" placeholder="{{ __('Comments') }}" class="form-control" required>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-danger remove-docs">Remove</button>
                </div>
            </div>
        `;

        // Append the new document fields to the #documents div
        $('#documents').append(newDocHtml);
    });
    
    $('#documents').on('click', '.remove-docs', function() {
        $(this).closest('.document-entry').remove();
    });
    
});

function validateInput(event) {
    const regex = /^[^'",.;:?<>!@#$%^&*()_+=-]*$/; // Disallow quotes and special characters
        if (!regex.test(event.target.value)) {
            event.target.value = event.target.value.replace(/['",.;:?<>\\!@#$%^&*()_+=-]/g, ''); // Remove invalid characters
        }
            
    /*const regex = /^[^,\s.]*$/; // Disallow commas, spaces, and dots
    if (!regex.test(event.target.value)) {
        event.target.value = event.target.value.replace(/[,\s.]/g, ''); // Remove invalid characters
    }*/
}
</script>
<script>
var users = [];

$("#assigntoname").on("keyup", function() {
    var str = '';
    var name = $(this).val();
    $.ajax({
        type: "POST",
        url: baseurl + "/tasks/getuser",
        dataType: "json",
        data: { _token: "{{ csrf_token() }}", name:name},
        cache: false,
        success: function (res) {
            console.log("data: " + JSON.stringify(res));
            if (res.users.length > 0) {
                str += '<ul class="qsearch-styled">';
                $.each(res.users, function(k, v) {
                    console.log(v);
                    if (v.firstname) {
                        str += '<li id="' + v.id + '_' + v.firstname + '_' + v.countrycode + '_' + v.phone + '">' + v.firstname + '</li>';
                    }
                })
                str += '</ul>';
                $("#qSearchList").html(str);
                $("#qSearchList").fadeIn();
            } else {
                str += '<ul class="qsearch-styled"><li>No Data Available</li></ul>';
                $('#qSearchList').html(str);
                $("#qSearchList").fadeIn();
            }
        },
        error: function(err) {
            console.error("Error fetching users: ", err);
        }
    });
})

$('body').on('click', '.qsearch-styled li', function() {    
    var id = $(this).attr('id').split('_')[0];
    var name = $(this).attr('id').split('_')[1];
    var countrycode = $(this).attr('id').split('_')[2];
    var phone = $(this).attr('id').split('_')[3];
    $("#assigntoname").val(name);
    $("#assignto").val(id);
    $("#assigncountrycode").val(countrycode);
    $("#assigntophone").val(phone);
    $("#qSearchList").fadeOut();
});


</script>
