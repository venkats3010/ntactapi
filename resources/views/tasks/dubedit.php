<?php // print_r($response);exit;
//print_r($response['data'][0]['details'][0]['docpath']);exit;
?>
<style>
    /* Ribbon Styles */
    .ribbon {
        width: 100px;
        height: 100px;
        overflow: hidden;
        position: absolute;
    }
    .ribbon::before,
    .ribbon::after {
        position: absolute;
        z-index: -1;
        content: '';
        display: block;
        border: 5px solid #2980b9;
    }
    .ribbon span {
        position: absolute;
        display: block;
        width: 200px;
        padding: 10px 0;
        background-color: #145388;
        box-shadow: 0 5px 10px rgba(0,0,0,.1);
        color: #fff;
        font: 700 18px/1 'Lato', sans-serif;
        text-shadow: 0 1px 1px rgba(0,0,0,.2);
        text-transform: uppercase;
        text-align: center;
    }
    .ribbon-top-left {
        top: -10px;
        left: -10px;
    }
    .ribbon-top-left::before,
    .ribbon-top-left::after {
        border-top-color: transparent;
        border-left-color: transparent;
    }
    .ribbon-top-left::before {
        top: 0;
        right: 0;
    }
    .ribbon-top-left::after {
        bottom: 0;
        left: 0;
    }
    .ribbon-top-left span {
        right: -25px;
        top: 5px;
        transform: rotate(-45deg);
    }

    /* Search List Styles */
    #qSearchList ul {
        margin-top: 20px;
        background: #ddd;
        color: #000;
    }
    #qSearchList ul > li {
        padding: 8px 12px;
        cursor: pointer;
        color: black;
        border-bottom: 1px solid #fff;
        font-size: 14px;
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

    /* Card Styles */
    .card {
        margin-bottom: 20px;
        border: 1px solid #ccc;
        border-radius: 8px;
        background-color: #f8f9fa;
    }
    .card-header {
        background-color: #2980b9;
        color: white;
        padding: 15px;
        border-top-left-radius: 8px;
        border-top-right-radius: 8px;
    }
    .card-body {
        padding: 20px;
    }
    .card-footer {
        background-color: #f1f1f1;
        padding: 10px 20px;
        border-bottom-left-radius: 8px;
        border-bottom-right-radius: 8px;
    }
    .form-label {
        font-weight: bold;
    }
    .form-control {
        border-radius: 4px;
        margin-bottom: 15px;
    }
    .btn-primary {
        background-color: #007bff;
        border: none;
        border-radius: 4px;
        color: #fff;
        padding: 10px 15px;
        font-weight: bold;
        transition: background 0.3s;
    }
    .btn-primary:hover {
        background-color: #0056b3;
    }
</style>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body p-0">
                @if(isset($response['data'][0]['details']) && !empty($response['data'][0]['details']))
                    @foreach($response['data'][0]['details'] as $task)
                        <div class="card">
                            <div class="card-header">
                                <span style="font-size: 1.25em; font-weight: bold;">{{$response['data'][0]['subject']}}</span>
                            </div>
                            <div class="card-body">
                                <p class="card-text">{{$task['details']}}</p>
                                @php $docpath = $task['docpath']; @endphp
                                @if (is_array($docpath) && count($docpath) > 0)
                                    <div class="row">
                                        @foreach($docpath as $doc)
                                            <div class="col-md-4">
                                                @php $ext = pathinfo($doc['path'], PATHINFO_EXTENSION); @endphp
                                                @if (in_array(strtolower($ext), ['jpeg', 'png', 'jpg', 'jfif']))
                                                    <a href="{{ url($doc['path']) }}" target="_blank" class="p-2">
                                                        <img src="{{ url($doc['path']) }}" alt="reply" class="file-preview d-block text-black-50" style="width:100%">
                                                    </a>
                                                @elseif ($ext == 'webm')
                                                    <span class="p-2">
                                                        <audio controls>
                                                            <source src="{{ url($doc['path']) }}" type="audio/webm">
                                                        </audio>
                                                    </span>
                                                @else
                                                    <a href="{{ url($doc['path']) }}" target="_blank" class="file-preview d-block text-black-50 p-2" style="width:100%">
                                                        <div style="display: flex; align-items: center;">
                                                            <i class="fa fa-file-pdf-o" style="font-size:30px;"></i>
                                                            <p style="font-size:14px; margin-left: 8px">{{ basename($doc['path']) }}</p>
                                                        </div>
                                                    </a>
                                                @endif
                                                @php $metadata = json_decode($doc['metadata']); @endphp
                                                <span>{{$metadata->givenName}}<br/></span>
                                                <span>{{$metadata->comment}}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            <div class="card-footer d-flex justify-content-between">
                                <div>
                                    <?php 
                                        echo date('M d, Y G:i A', strtotime($task['createdon']));
                                        $spriority = ['High', 'Medium', 'Low'][$task['priority'] - 1] ?? 'N/A';
                                        $tstatus = ['New', 'In Progress', 'Ready for Test', 'Completed', 'Re Opened', 'Closed'][$task['status'] - 1] ?? 'N/A';
                                        echo '<br>' . $spriority . ' Priority, ' . $tstatus;
                                    ?>
                                </div>
                                <div>
                                    
                         @php 
                                $assignedtoname = "";
                                $createdbyname = "";
                            @endphp
                            @if(!empty($users['users'] ?? []))
                                @foreach($users['users'] as $user)
                                    @if($task['assignedto'] == $user['id'])
                                        @php
                                            $assignedtoname = $user['firstname'];
                                        @endphp
                                    @endif
                                    @if($task['createdby'] == $user['id'])
                                        @php
                                            $createdbyname = $user['firstname'];
                                        @endphp
                                    @endif
                                @endforeach
                            @endif
                            
                                    <strong>Assigned to:</strong> <?= $assignedtoname; ?><br>
                                    <strong>Created By:</strong> <?= $createdbyname; ?>
                                </div>
                            </div>
                        </div>
                    @endforeach        
                @endif
                
                <form role="form" id="update-task" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="rowid" id="rowid" value="{{ $response['data'][0]['id'] }}">
                    <input type="hidden" name="tid" id="tid" value="{{ $response['data'][0]['tid'] }}">
                    
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label class="form-label">{{ __('Comments') }}</label>
                            <textarea name="details" id="details" placeholder="{{ __('Comments') }}" class="form-control {{ $errors->has('details') ? 'is-invalid' : '' }}" required></textarea>
                            <div class="invalid-feedback">{{ $errors->first('details') }}</div>
                        </div>

                        <div class="form-group col-md-12">
                            <button type="button" class="btn btn-primary mt-2 AddDocs">{{ __('Add Documents') }}</button>
                        </div>

                        <div class="row">
                            <div id="documents"></div>
                        </div>

                        <div class="form-group col-md-6">
                            <label class="form-label">{{ __('Priority') }}</label>
                            <select class="form-control" name="severity" id="severity" required>
                                <option value="">Select Priority</option>
                                <option value="1" {{ ($response['data'][0]['priority'] == "1") ? "selected" : "" }}>High</option>
                                <option value="2" {{ ($response['data'][0]['priority'] == "2") ? "selected" : "" }}>Medium</option>
                                <option value="3" {{ ($response['data'][0]['priority'] == "3") ? "selected" : "" }}>Low</option>
                            </select>
                            <div class="invalid-feedback">{{ $errors->first('priority') }}</div>
                        </div>

                        <div class="form-group col-md-6">
                            <label class="form-label">{{ __('Status') }}</label>
                            <select class="form-control" name="status" id="status" required>
                                <option value="">Select Status</option>
                                <option value="1" {{ ($response['data'][0]['status'] == "1") ? "selected" : "" }}>New</option>
                                <option value="2" {{ ($response['data'][0]['status'] == "2") ? "selected" : "" }}>In Progress</option>
                                <option value="3" {{ ($response['data'][0]['status'] == "3") ? "selected" : "" }}>Ready for Test</option>
                                <option value="4" {{ ($response['data'][0]['status'] == "4") ? "selected" : "" }}>Completed</option>
                                <option value="5" {{ ($response['data'][0]['status'] == "5") ? "selected" : "" }}>Re Opened</option>
                                <option value="6" {{ ($response['data'][0]['status'] == "6") ? "selected" : "" }}>Closed</option>
                            </select>
                            <div class="invalid-feedback">{{ $errors->first('status') }}</div>
                        </div>

                        <div class="form-group col-md-6">
                            <label class="form-label">{{ __('Assigned to') }}</label>
                            <input type="hidden" name="assignto" id="assignto" value="{{ $assign_to }}">
                            <input type="text" class="form-control" id="assigntoname" placeholder="Start typing a user..." value="{{ $assign_toname }}">
                            <div id="qSearchList" class="my-1"></div>
                            <div class="invalid-feedback">{{ $errors->first('assignedto') }}</div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-12">
                            <div class="text-end">
                                <button class="btn btn-primary btn-block mt-2 updateTask">{{ __('Save') }}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



<script>

    $('#update-task').on('submit',(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $('#loader').show();
        $.ajax({
            type:'POST',
            url:  baseurl + '/tasks/update',
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
                    <input type="text" name="documents[filename][]" placeholder="{{ __('File Name') }}" class="form-control" oninput="validateInput(event)" required>
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
                        str += '<li id="' + v.id + '_' + v.firstname + '">' + v.firstname + '</li>';
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
    $("#assigntoname").val(name);
    $("#assignto").val(id);
    $("#qSearchList").fadeOut();
});


</script>

