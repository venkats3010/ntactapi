<?php // print_r($response);exit;
//print_r($response['data'][0]['details'][0]['docpath']);exit;
?>
<style>
/* common */
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

/* top left*/
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
</style>

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
<?php // print_r($response);exit; ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-0">
                @if(isset($response['data'][0]['details']) && !empty($response['data'][0]['details'])>0)
                   @foreach($response['data'][0]['details'] as $task)
                   
                    <div class="card">
                      <div class="card-header">
                        <!--<div class="ribbon ribbon-top-left"><span>{{ $response['data'][0]['tid'] }}</span></div>-->
                            <span STYLE="font-size: 1.25em;margin-top: 1.67em;margin-bottom: 1.67em;margin-left:20px;margin-right: 0;Font-weight: bold;">{{$response['data'][0]['subject']}}</span>
                        </div>
                      <div class="card-body">
                        <h5 class="card-title"></h5>
                        <p class="card-text">{{$task['details']}}</p>
                        
                                @php                        
                                $docpath = $task['docpath'];   
                                @endphp
                                @if (is_array($docpath) && count($docpath) > 0)
                                <div class="row">
                                @foreach($docpath as $doc)
                                <div class="col">
                                   <?php //print_r($doc['path']);exit;?>
                                    @php
                                        $ext = pathinfo($doc['path'], PATHINFO_EXTENSION);
                                    @endphp
                                
                                    @if (in_array(strtolower($ext), ['jpeg', 'png', 'jpg', 'jfif']))
                                        <!-- Display image -->
                                        
                                        <a href="{{ url( $doc['path']) }}" target="_blank" class="p-2">
                                            <img src="{{ url( $doc['path']) }}" alt="reply" class="file-preview d-block text-black-50" style="width:200px">
                                        </a>
                                        
                                    @elseif ($ext == 'webm')
                                        <!-- Display audio -->
                                        <span class="p-2">
                                            <audio controls>
                                                <source src="{{ url( $doc['path']) }}" type="audio/webm">
                                            </audio>
                                        <.span>
                                    @else
                                        <!-- Display other file types -->
                                        <a href="{{ url( $doc['path']) }}" target="_blank" class="file-preview d-block text-black-50 p-2" style="width:100px;padding: 0px;color: #fff;">
                                            <div style="display: flex;">
                                                <i class="fa fa-file-pdf-o" style="font-size:30px;"></i>
                                                <p style="font-size:14px; margin-left: 8px">{{ $doc['path'] }}</p>
                                            </div>
                                        </a>
                                    @endif
                                    <?php 
                                    $metadata = json_decode($doc['metadata']); ?>
                                    <span>{{$metadata->givenName}}<br/></span>
                                    <span>{{$metadata->comment}}</span>
                                    </div>
                                    @endforeach
                                    </div>
                                @endif                        
                       
                      </div>
                      <div class="card-footer d-flex justify-content-between">
                          <div class="footerLeft">
                            <?php 
                            echo date('M d, Y G:i A', strtotime($task['createdon']));
                            $spriority = '';
                            if($task['priority'] == 1){
                               $spriority = 'High';
                            }else if($task['priority'] == 2){
                                $spriority = 'Medium';
                            }else if($task['priority'] == 3){
                               $spriority  = 'Low';
                            }
                            
                            $tstatus = '';
                            if($task['status'] == 1){
                               $tstatus = 'New';
                            }else if($task['status'] == 2){
                                $tstatus = 'In Progress';
                            }else if($task['status'] == 3){
                               $tstatus  = 'Ready for Test';
                            }else if($task['status'] == 4){
                               $tstatus  = 'Completed';
                            }else if($task['status'] == 5){
                               $tstatus  = 'Re Opened';
                            }else if($task['status'] == 6){
                               $tstatus  = 'Closed';
                            }
                            
                            echo '<br>'.$spriority. ' Priority, '.$tstatus;
                            ?>
                        </div>
                        <div class="footerRight">
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
                            
                            <div>Assigned to: <b><?=$assignedtoname;?></b></div>
                            <div>Created By: <b><?=$createdbyname;?></b></div>
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
                                    <div class="form-icon-user">
                                        <textarea name="details" id="details"  placeholder="{{ __('Comments') }}" class="form-control {{ $errors->has('details') ? ' is-invalid': '' }}" required></textarea>
                                        <div class="invalid-feedback">
                                            {{ $errors->first('details') }}
                                        </div>
                                    </div>
                                </div>


                            
                                <div class="form-group col-md-12">
                                    {{--<label class="form-label">{{ __('File Attachment') }}</label>
                                    <div class="form-icon-user">
                                        <input type="file" name="files[]" id="files"  placeholder="{{ __('File Attachment') }}" class="form-control" multiple>
                                        
                                    </div>--}}
                                    
                                <div class="form-group col-md-12">
                                    <button type="button" class="btn btn-primary mt-2 AddDocs"><span>{{ __('Add Documnets') }}</span></button>
                                </div>
                                <div class="row">
                                        <div id="documents"></div>
                                </div>
                                    
                                </div>
                                
                                
                                
                            {{--<div class="form-group col-md-6">
                                <label class="form-label">{{ __('Subject') }}</label>
                                <div class="form-icon-user">
                                    <input type="text" placeholder="{{ __('Title') }}" name="subject" id="subject" class="form-control" value="{{ $response['data'][0]['subject'] }}" autofocus>
                                    <div class="invalid-feedback">
                                        {{ $errors->first('subject') }}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label">{{ __('Category') }}</label>
                                <div class="form-icon-user">
                                    <input type="text" placeholder="{{ __('Category') }}" name="category" id="category" class="form-control {{ $errors->has('category') ? ' is-invalid' : '' }}" value="{{ $response['data'][0]['subject'] }}">
                                    <div class="invalid-feedback">
                                        {{ $errors->first('category') }}
                                    </div>
                                </div>
                            </div>--}}
    
    
                            <div class="form-group col-md-6">
                                <label class="form-label">{{ __('Priority') }}</label>
                                <div class="form-icon-user">
                                    <select class="form-control field_type" name="severity" id="severity" required>
                                            <option value="">Select Priority</option>
                                            <option value="1" <?=($response['data'][0]['priority']=="1")?"selected":"" ?>>High</option>
                                            <option value="2" <?=($response['data'][0]['priority']=="2")?"selected":"" ?>>Medium</option>
                                            <option value="3" <?=($response['data'][0]['priority']=="3")?"selected":"" ?>>Low</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        {{ $errors->first('priority') }}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label class="form-label">{{ __('Status') }}</label>
                                <div class="form-icon-user">
                                    <select class="form-control field_type" name="status" id="status" required>
                                            <option value="">Select Priority</option>
                                            <option value="1" <?=($response['data'][0]['status']=="1")?"selected":"" ?>>New</option>
                                            <option value="2" <?=($response['data'][0]['status']=="2")?"selected":"" ?>>In Progress</option>
                                            <option value="3" <?=($response['data'][0]['status']=="3")?"selected":"" ?>>Ready for Test</option>
                                            <option value="4" <?=($response['data'][0]['status']=="4")?"selected":"" ?>>Completed</option>
                                            <option value="5" <?=($response['data'][0]['status']=="5")?"selected":"" ?>>Re Opened</option>
                                            <option value="5" <?=($response['data'][0]['status']=="6")?"selected":"" ?>>Closed</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        {{ $errors->first('priority') }}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label class="form-label">{{ __('Assigned to') }}</label>
                                    <div class="form-icon-user">
                                       {{-- <select class="form-control field_type" name="assignto" id="assignto" required>
                                            <option value="">Select Assigned to</option>
												@if(isset($users['users']) && !empty($users['users'])>0)
                                                @foreach($users['users'] as $user)
												@if(isset($user['status']) && $user['status'] == 'A')
                                                <option value="{{$user['id']}}" <?=($response['data'][0]['assignedto']==$user['id'])?"selected":"" ?>>{{$user['firstname']}}</option>
												@endif
                                                @endforeach
												@endif
                                        </select> --}} 
                                        @php 
                                            $assign_to = "";
                                            $assign_toname = "";
                                        @endphp
                                        
                                        @if(!empty($users['users'] ?? []))
                                            @foreach($users['users'] as $user)
                                                @if($response['data'][0]['assignedto'] == $user['id'])
                                                    @php
                                                        $assign_to = $user['id'];
                                                        $assign_toname = $user['firstname'];
                                                    @endphp
                                                @endif
                                            @endforeach
                                        @endif

                                        
                                        <input type="hidden" class="form-control" name="assignto" id="assignto" value={{$assign_to}}>
                                        <input type="text" class="form-control" id="assigntoname" placeholder="Start typing a user..." value={{$assign_toname}}>
                                        <div id="qSearchList" style="color: #000;" class="my-1"></div>
                                            
                                        <div class="invalid-feedback">
                                            {{ $errors->first('assignedto') }}
                                        </div>
                                    </div>
                            </div>



                            

                        </div>

                        <div class="row">
                            <div class="form-group col-md-12">
                                <label class="form-label"></label>
                                <div class="form-icon-user text-end">
                                    <button class="btn btn-primary btn-block mt-2 btn-submit updateTask"><span>{{ __('Save') }}</span></button>
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

