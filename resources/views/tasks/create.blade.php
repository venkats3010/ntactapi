<style>
    #qSearchList ul {
        margin-top: 20px;
        background: #ddd;
        color: #000;
        border-radius: 4px;
    }

    #qSearchList ul > li {
        padding: 8px 12px;
        cursor: pointer;
        border-bottom: 1px solid #fff;
        font-size: 14px;
        transition: background 0.3s;
    }

    #qSearchList ul > li:hover {
        background: rgba(209, 232, 246, 1);
        color: #000;
    }

    #qSearchList {
        position: absolute;
        width: 100%;
        z-index: 9999;
    }

    .qsearch-styled {
        padding-left: 0;
        list-style: none;
    }

    .form-control {
        border-radius: 4px;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        font-weight: bold;
    }

/*    .btn-primary {
        background-color: #007bff;
        border: none;
        padding: 10px 15px;
        border-radius: 4px;
        color: #fff;
        font-weight: bold;
        transition: background 0.3s;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }
*/

    
.document-entry{
       display: flex; 
    justify-content: space-between; 
}
@media (max-width: 767.98px) {
    .document-entry {
        display: block; 
        text-align: left !important;
        
    }    
}    
</style>
<style>
.cke_notification_message, .cke_notification, .cke_notification_warning, .cke_notification_close {
	display:none;
}
</style>
<div class="row">
    <div class="col-12">
        <div class="card p-3">
            <div class="card-body p-0">
                <form role="form" id="create-task" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">     
                    <input type="hidden" name="userid" id="userid" value="{{ $response['userid'] }}">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">{{ __('Subject') }}</label>
                                <input type="text" placeholder="{{ __('Title') }}" name="subject" id="subject" class="form-control {{ $errors->has('subject') ? 'is-invalid' : '' }}" autofocus>
                                <div class="invalid-feedback">{{ $errors->first('subject') }}</div>
                            </div>
                        </div>


                        
                        <div class="col-md-6">
                            <label class="form-label col-md-3">{{ __('Category') }}</label>
                            <div class="form-group row align-items-center">
                                
                                <div class="col-md-10">
                                    <select class="form-control" name="category" id="category" required>
                                        <option value="">Select category</option>
                                        @if(isset($category['data']) && !empty($category['data']))
                                            @foreach($category['data'] as $cat)
                                                @if(isset($cat['status']) && $cat['status'] == 'A')
                                                    <option value="{{ $cat['id'] }}">{{ $cat['name'] }}</option>
                                                @endif
                                            @endforeach
                                        @endif
                                    </select>
                                    <span id="categoryAddTxt"></span>
                                    <div class="invalid-feedback">{{ $errors->first('category') }}</div>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-primary" id="addCategoryButton">
                                        <i class="fas fa-plus"></i> 
                                    </button>
                                </div>
                            </div>
                        </div>

                        

                        <div class="col-md-6">
                                <label class="form-label col-md-3">{{ __('Priority') }}</label>
                                <div class="form-group row align-items-center">
                                <div class="col-md-10">
                                <select class="form-control" name="severity" id="severity" required>
                                    <option value="">Select Priority</option>
                                        @if(isset($priorities['data']) && !empty($priorities['data'])>0)
                                            @foreach($priorities['data'] as $priority)
            									@if(isset($priority['status']) && $priority['status'] == 'A')
                                                    <option value="{{$priority['id']}}">{{$priority['priority']}}</option>
            									@endif
                                            @endforeach
    									@endif
                                </select>
                                <span id="priorityAddTxt"></span>
                                <div class="invalid-feedback">{{ $errors->first('priority') }}</div>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-primary" id="addPriorityButton">
                                        <i class="fas fa-plus"></i> 
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!--<div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">{{ __('Assigned to') }}</label>
                                <input type="hidden" class="form-control" name="assign_to" id="assign_to" >
                                <input type="hidden" class="form-control" name="assigncountrycode" id="assigncountrycode" >
                                <input type="hidden" class="form-control" name="assigntophone" id="assigntophone" >
                                <input type="text" class="form-control" id="assigntoname" placeholder="Start typing a user..." autocomplete="off">
                                <div id="qSearchList" class="my-1"></div>
                                <div class="invalid-feedback">{{ $errors->first('assignedto') }}</div>
                            </div>
                        </div>-->
                    

                    <div class="col-md-6">
                        <label class="form-label">{{ __('Assigned to') }}</label>
                        <div class="form-group">
                            <div class="multiSelect-dropdown w-100">
                                <div class="multiSelect-dropdown-toggle">Select options</div>
                                <div class="multiSelect-dropdown-menu">
                                    @if(isset($users['users']) && !empty($users['users'])>0)
                                        @foreach($users['users'] as $user)
                            				@if(isset($user['status']) && $user['status'] == 'A')
                                				@if($user['id'] != $created_by)
                                                    <label><input type="checkbox" name="assignto[]" value={{$user['id']}}> {{$user['firstname']}}</label>
                                                @endif
                            				@endif
                                        @endforeach
                            		@endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">{{ __('Comments') }}</label>
                        <textarea name="details" id="details" placeholder="{{ __('Comments') }}" class="textEditor form-control {{ $errors->has('details') ? 'is-invalid' : '' }}" required></textarea>
                        <div class="invalid-feedback">{{ $errors->first('details') }}</div>
                    </div>

                    <div class="form-group">
                        <button type="button" class="btn btn-warning AddDocs">{{ __('Add ') }} <i class="mdi mdi-attachment"></i></button>
                    </div>

                    <div id="documents" class="mb-3"></div>

                    <div class="">
                        <button type="submit" class="btn btn-primary mt-2 createTask">{{ __('Save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



<script src="{{ my_asset('/assets/js/tasks.js') }}" type="text/javascript"></script>
<script>

    $('#create-task').on('submit',(function(e) {
        e.preventDefault();
        var details = CKEDITOR.instances.details.getData();
        var formData = new FormData(this);
        formData.append('details', details);
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
                    $("#myoffcanvasRight .btn-close-icon").click();
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
    
 


</script>

<script>
$(document).ready(function() {
    $('.AddDocs').on('click', function(e) {
        e.preventDefault();
        // Create the new document fields HTML
        const newDocHtml = `
            <div class="document-entry p-2">
                <div class="col-md-3 p-1">
                    <input type="file" name="documents[files][]" placeholder="{{ __('File Attachment') }}" class="form-control" accept="image/jpeg,image/gif,image/png,application/pdf">
                </div>
                <!--<div class=" d-none">
                    <input type="text" name="documents[filename][]" placeholder="{{ __('File Name') }}" class="form-control" oninput="validateInput(event)">
                </div>-->
                <div class="col-md-6 p-1">
                    <input type="text" name="documents[filecomment][]" placeholder="{{ __('Comments') }}" class="form-control">
                </div>
                <div class="col-md-2 p-1">
                    <button type="button" class="btn btn-danger btn-sm remove-docs"><i class="mdi mdi-minus-circle-outline"></i></button>
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

/*$('body').on('click', '.qsearch-styled li', function() {    
    var id = $(this).attr('id').split('_')[0];
    var name = $(this).attr('id').split('_')[1];
    var countrycode = $(this).attr('id').split('_')[2];
    var phone = $(this).attr('id').split('_')[3];
    $("#assigntoname").val(name);
    $("#assignto").val(id);
    $("#assigncountrycode").val(countrycode);
    $("#assigntophone").val(phone);
    $("#qSearchList").fadeOut();
});*/


</script>

<script>
$('body').on('click', '#addCategoryButton', function(e) {    
    console.log("addbtn " );
    $('#category').addClass('d-none');
    $("#categoryAddTxt").html(`<input type="text" name="newcategory" id="newcategory" class="form-control">`);
    $("#addCategoryButton").replaceWith(`<button type="button" class="btn btn-primary" id="saveCategoryButton"><i class="mdi mdi-content-save"></i></button>`);
});
$('body').on('click', '#saveCategoryButton', function(e) {
    e.preventDefault();
    var name = $("#newcategory").val();
    if(name == ""){
        toastr.error("Please enter category");
        return false;
    }
    $.ajax({
        type: "POST",
        url: baseurl + "/category/create",
        dataType: "json",
        data: { _token: "{{ csrf_token() }}", name:name},
        cache: false,
        success: function (res) {
            console.log("data: " + JSON.stringify(res));
            if (res.status == 200) {
                    
                    //$('#category').val(); 
                    $('#category').removeClass('d-none');
                    $('#category option').attr('selected', false);
                    let newOption = $('<option></option>')
                        .val(res.data)
                        .text(name)
                        .attr('selected', true);
                    
                    console.log("data: " + JSON.stringify(newOption));
                    $('#category').append(newOption);
                    $('#category').val(res.data);
                                        
                    
                    $('#newcategory').remove();
                    $("#saveCategoryButton").replaceWith(`<button type="button" class="btn btn-primary" id="addCategoryButton"><i class="fas fa-plus"></i></button>`);
                
            } 
        },
        error: function(err) {
            console.error("Error fetching category: ", err);
        }
    });

});
</script>
<script>
$('body').on('click', '#addPriorityButton', function(e) {    
    console.log("addbtn " );
    $('#severity').addClass('d-none');
    $("#priorityAddTxt").html(`<input type="text" name="newseverity" id="newseverity" class="form-control">`);
    $("#addPriorityButton").replaceWith(`<button type="button" class="btn btn-primary" id="savePriorityButton"><i class="mdi mdi-content-save"></i></button>`);
});
$('body').on('click', '#savePriorityButton', function(e) {
    e.preventDefault();
    var name = $("#newseverity").val();
    if(name == ""){
        toastr.error("Please enter priority");
        return false;
    }
    $.ajax({
        type: "POST",
        url: baseurl + "/priority/create",
        dataType: "json",
        data: { _token: "{{ csrf_token() }}", name:name},
        cache: false,
        success: function (res) {
            console.log("data: " + JSON.stringify(res));
            if (res.status == 200) {
                    
                    //$('#severity').val(); 
                    $('#severity').removeClass('d-none');
                    $('#severity option').attr('selected', false);
                    let newOption = $('<option></option>')
                        .val(res.data)
                        .text(name)
                        .attr('selected', true);
                    
                    console.log("data: " + JSON.stringify(newOption));
                    $('#severity').append(newOption);
                    $('#severity').val(res.data);
                                        
                    
                    $('#newseverity').remove();
                    $("#savePriorityButton").replaceWith(`<button type="button" class="btn btn-primary" id="addPriorityButton"><i class="fas fa-plus"></i></button>`);
                
            } 
        },
        error: function(err) {
            console.error("Error fetching priority: ", err);
        }
    });

});
</script>
<script>

</script>
<script>
if (CKEDITOR.instances.details) {
    CKEDITOR.instances.details.destroy();
}
    CKEDITOR.replace('details', {
        toolbar: [
            { name: 'basicstyles', items: [] },
            { name: 'paragraph', items: [] },
            { name: 'insert', items: [] }
        ],
        removePlugins: 'elementspath',
        resize_enabled: false
    });
</script>