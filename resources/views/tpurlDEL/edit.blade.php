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
.card-footer {
    display: flex; 
    justify-content: space-between;
    
}
.card-footer p {
        font-weight:900;
    }
/* Mobile styling */
@media (max-width: 767.98px) {
    .card-footer {
        display: block; 
        text-align: left !important;
        
    }

    .card-footer p {
        margin-bottom: 10px;
        font-weight:900;
    }
}
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
<style>
    .image-container {
        display: flex;
        flex-wrap: wrap; /* Allow wrapping to the next line if needed */
        gap: 2px; /* Space between items */
    }
    .image-item {
        width: 100px; /* Fixed width for images */
        height: 80px; /* Fixed height for images */
    }
    .audio-item {
        padding: 2px; /* Padding for audio items */
    }
</style>
<?php //echo "<pre>"; 
//echo $response['data'][0]['status'];
//print_r($response['data'][0]);exit; ?>

  <div class="offcanvas-header" style="background-color:#c1c1c1;color:#000;">
    <h5 id="myoffcanvasRightLabel">

        <div class="task">
            <div class="badge-container">
                <span class=" badge-rectangle" style="background-color: #d291bc;">
                    #{{$response['data'][0]['tid']}}
                </span>
            </div>
            <div class="badge-container">
                <span class="badge-rectangle" style="background-color: #0d6efd;">
                    @if(isset($priorities['data']) && !empty($priorities['data'])>0)
                        @foreach($priorities['data'] as $priority)
                    		@if($response['data'][0]['priority'] == $priority['id'])
                                {{$priority['priority'] ?? ''}}
                    		@endif
                        @endforeach
                    @endif
                </span>
            </div>
            <div class="badge-container">
                <span class="badge-rectangle" style="background-color: #ff7f50;">
            		@if(isset($statustypes['data']) && !empty($statustypes['data'])>0)
                        @foreach($statustypes['data'] as $stat)
							@if($response['data'][0]['status'] == $stat['id'])
							    {{$stat['name']}}
							@endif
                        @endforeach
					@endif
                </span>
            </div>
            <div class="badge-container">
                <span class="badge-rectangle" style="background-color: #17a2b8;">
                     @if(!empty($users['users'] ?? []))
                        @foreach($users['users'] as $user)
                            @if($response['data'][0]['assignedto'] == $user['id'])
                                @php
                                    $assignedtoname = $user['firstname'].' '.$user['lastname'];
                                    $assigninitials = strtoupper(mb_substr($user['firstname'], 0, 1)).' '.strtoupper(mb_substr($user['lastname'], 0, 1));
                                @endphp
                                {{$assigninitials}}
                            @endif
                            @if($response['data'][0]['createdby'] == $user['id'])
                                @php
                                    $createdbyname = $user['firstname'].' '.$user['lastname'];
                                    $createdinitials = strtoupper(mb_substr($user['firstname'], 0, 1)).' '.strtoupper(mb_substr($user['lastname'], 0, 1));
                                @endphp
                            @endif
                        @endforeach
                    @endif
                </span>
            </div>
            
        </div>
        
         - {{$response['data'][0]['subject']}}
        <button type="button" class="text-end"  style="border: 2px solid red;border-radius: 50%;background-color:#c1c1c1;">{{$response['data'][0]['cntdtls']}}</button>
    </h5>
    <button type="button" class="btn-close custom-btn" data-bs-dismiss="offcanvas" id="UserClose" aria-label="Close" style="border: 2px solid red;border-radius: 50%;"></button>
  </div>
  <div class="offcanvas-body" >
    <div class="row" style="max-height:650px;">
        <div class="col-12">
                <?php 
                $taskstatus = $response['data'][0]['status'];
                $taskpriority = $response['data'][0]['priority'];
                $taskassignedto = $response['data'][0]['assignedto'];
                ?>
                @if(isset($response['data'][0]['details']) && !empty($response['data'][0]['details'])>0)
                   @foreach($response['data'][0]['details'] as $task)
                       @php
                        $taskstatus = $task['status'];
                        $taskpriority = $task['priority'];
                        $taskassignedto = $task['assignedto'];
                   @endphp
                    <div class="card">
                      <div class="card-body">
                            @php                        
                                $docpath = $task['docpath'];   
                                @endphp
                                @if (is_array($docpath) && count($docpath) > 0)
                                <div class="row">
                                @php 
                                    $spriority = '';
                                    $tstatus = '';
                              @endphp
                            @if(isset($priorities['data']) && !empty($priorities['data'])>0)
                                @foreach($priorities['data'] as $priority)
									@if($task['priority'] == $priority['id'])
									    @php
                                            $spriority  = $priority['priority'];
                                        @endphp
									@endif
                                @endforeach
							@endif
							
							@if(isset($statustypes['data']) && !empty($statustypes['data'])>0)
                                @foreach($statustypes['data'] as $stat)
									@if($task['status'] == $stat['id'])
									    @php
                                            $tstatus  = $stat['name'];
                                        @endphp
									@endif
                                @endforeach
							@endif
                                 
                            
                            @php 
                                $assignedtoname = $createdbyname = "";
                                $assigninitials = $createdinitials = "";
                            @endphp
                            @if(!empty($users['users'] ?? []))
                                @foreach($users['users'] as $user)
                                    @if($task['assignedto'] == $user['id'])
                                        @php
                                            $assignedtoname = $user['firstname'].' '.$user['lastname'];
                                            $assigninitials = strtoupper(mb_substr($user['firstname'], 0, 1)).' '.strtoupper(mb_substr($user['lastname'], 0, 1));
                                        @endphp
                                    @endif
                                    @if($task['createdby'] == $user['id'])
                                        @php
                                            $createdbyname = $user['firstname'].' '.$user['lastname'];
                                            $createdinitials = strtoupper(mb_substr($user['firstname'], 0, 1)).' '.strtoupper(mb_substr($user['lastname'], 0, 1));
                                        @endphp
                                    @endif
                                @endforeach
                            @endif
                            

                            <?php 
                                echo '<p style="color: #ff0000;font-weight: bold;">'.date('M d, Y G:i A', strtotime($task['createdon'])).'</p>';
                            ?>
                            <p>{{$task['details']}}</p>
                            <div class="image-container">
                                @foreach($docpath as $doc)
                                        <div class="form-group p-2">
                                           <?php //print_r($doc['path']);exit;?>
                                            @php
                                                $ext = pathinfo($doc['path'], PATHINFO_EXTENSION);
                                            @endphp
                                        
                                            @if (in_array(strtolower($ext), ['jpeg', 'png', 'jpg', 'jfif']))
                                                <!-- Display image -->
                                                <a href="{{ url( $doc['path']) }}" target="_blank" class="">
                                                    <img src="{{ url( $doc['path']) }}"  class="file-preview d-block text-black-50" alt="Thumbnail" style="width: 100px; height: 80px;">
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
                                                <a href="{{ url( $doc['path']) }}" target="_blank" class="file-preview d-block text-black-50" style="width: 100px; height: 80px;">
                                                    <div style="display: flex;">
                                                        <i class="fa fa-file-pdf-o" style="font-size:30px;"></i>
                                                        <p style="font-size:14px; margin-left: 8px">{{ $doc['path'] }}</p>
                                                    </div>
                                                </a>
                                            @endif
                                            <?php 
                                            $metadata = json_decode($doc['metadata']); ?>
                                            <span>{{$metadata->comment}}</span>
                                        </div>
                                        @endforeach
                                    </div>
                                @endif                        
                       
							</div>
						</div>
					</div>
                    @endforeach        
                   @endif
				   <div class="card mt-5">
                    <form role="form" id="update-task" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="rowid" id="rowid" value="{{ $response['data'][0]['id'] }}">
                    <input type="hidden" name="tid" id="tid" value="{{ $response['data'][0]['tid'] }}">
                        <div class="row m-3">
                            
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
                                    
                                <div class="form-group">
                                    <button type="button" class="btn btn-warning mt-2 AddDocs"><span>{{ __('Add ') }} </span><i class="mdi mdi-attachment"></i></button>
                                </div>
                                <div class="row">
                                        <div id="documentsdiv"></div>
                                </div>
                                    
                                </div>
                                
                                

    
    
                            <div class="form-group col-md-6 d-none">
                                <label class="form-label">{{ __('Priority') }}</label>
                                <div class="form-icon-user">
                                    <select class="form-control field_type" name="severity" id="severity" required>
                                            <option value="">Select Priority</option>
                                            @if(isset($priorities['data']) && !empty($priorities['data'])>0)
                                                @foreach($priorities['data'] as $priority)
                									@if(isset($priority['status']) && $priority['status'] == 'A')
                                                        <option value="{{$priority['id']}}" <?=($response['data'][0]['priority'] == $priority['id'])?"selected":"" ?>>{{$priority['priority']}}</option>
                									@endif
                                                @endforeach
        									@endif
                                    </select>
                                    <div class="invalid-feedback">
                                        {{ $errors->first('priority') }}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-md-6 d-none">
                                <label class="form-label">{{ __('Status') }} </label>
                                <div class="form-icon-user">
                                    <select class="form-control field_type" name="status" id="status" required>
                                            <option value="">Select Status</option>
                                            @if(isset($statustypes['data']) && !empty($statustypes['data'])>0)
                                                @foreach($statustypes['data'] as $stat)
                									@if(isset($stat['status']) && $stat['status'] == 'A')
                                                        <option value="{{$stat['id']}}" <?=($response['data'][0]['status'] == $stat['id'])?"selected":"" ?>>{{$stat['name']}}</option>
                									@endif
                                                @endforeach
        									@endif
                                    </select>
                                    <div class="invalid-feedback">
                                        {{ $errors->first('priority') }}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-md-6 d-none">
                                <label class="form-label">{{ __('Assigned to') }}</label>
                                    <div class="form-icon-user">
                                       {{-- <select class="form-control field_type" name="assignto" id="assignto" required>
                                            <option value="">Select Assigned to</option>
												@if(isset($users['users']) && !empty($users['users'])>0)
                                                @foreach($users['users'] as $user)
												@if(isset($user['status']) && $user['status'] == 'A')
                                                <option value="{{$user['id']}}" <?=($taskassignedto == $user['id'])?"selected":"" ?>>{{$user['firstname']}}</option>
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
                                                @if($taskassignedto == $user['id'])
                                                    @php
                                                        $assign_to = $user['id'];
                                                        $assign_toname = $user['firstname'];
                                                    @endphp
                                                @endif
                                            @endforeach
                                        @endif

                                        
                                        <input type="hidden" class="form-control" name="assignto" id="assignto" value={{$assign_to}}>
                                        <input type="text" class="form-control" id="assigntoname" placeholder="Start typing a user..." value={{$assign_toname}} autocomplete="off">
                                        <div id="qSearchList" style="color: #000;" class="my-1"></div>
                                            
                                        <div class="invalid-feedback">
                                            {{ $errors->first('assignedto') }}
                                        </div>
                                    </div>
                            </div>



                            

                        </div>

                        
                            <div class="form-group col-md-12">
                                <label class="form-label"></label>
                                <div class="form-icon-user">
                                    <button class="btn btn-primary mt-2 btn-submit updateTask"><span>{{ __('Save') }}</span></button>
                                </div>
                            </div>
                        
                        </div>
                    </form>
			
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
            <div class=" document-entry p-2">
                <div class="col-md-3 p-1">
                    <input type="file" name="documents[files][]" placeholder="{{ __('File Attachment') }}" class="form-control" accept="image/jpeg,image/gif,image/png,application/pdf">
                </div>
                <!--<div class="  p-1 d-none">
                    <input type="text" name="documents[filename][]" placeholder="{{ __('File Name') }}" class="form-control" oninput="validateInput(event)" required>
                </div>-->
                <div class="col-md-6 p-1">
                    <input type="text" name="documents[filecomment][]" placeholder="{{ __('Comments') }}" class="form-control" required>
                </div>
                <div class="col-md-2 p-1">
                    <button type="button" class="btn btn-danger btn-sm remove-docs"><i class="mdi mdi-minus-circle-outline"></i></button>
                </div>
            </div>
        `;

        // Append the new document fields to the #documents div
        $('#documentsdiv').append(newDocHtml);
    });
    
    $('#documentsdiv').on('click', '.remove-docs', function() {
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

