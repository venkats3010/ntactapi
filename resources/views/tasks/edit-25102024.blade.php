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
    

@media (min-width: 576px) {
    .offcanvas-body {
        max-height: 670px;
    }
}
@media (max-width: 576px) {
    .offcanvas-body {
        max-height: 560px;
    }
}


</style>
<style>
.cke_notification_message, .cke_notification, .cke_notification_warning, .cke_notification_close {
	display:none;
}
</style>
<?php //echo "<pre>"; print_r($response['data'][0]['cntdtls']);exit; ?>

  <div class="offcanvas-header p-3" style="background-color:#145388;color:#FFF;">
    <h3 id="myoffcanvasRightLabel" style="color:#FFF;"><span class="" style="color:#FFF;">
        
    {{--@if(isset($priorities['data']) && !empty($priorities['data'])>0)
        @foreach($priorities['data'] as $priority)
    		@if($response['data'][0]['currentPriority'] == $priority['id'])
                {{$priority['priority'] ?? ''}}
    		@endif
        @endforeach
    @endif--}}

    #{{$response['data'][0]['tid']}}
        
    </span>  - <?php $subject = $response['data'][0]['subject']; 
        echo $restrictedSubject = strlen($subject) > 30 ? substr($subject, 0, 25) . '...' : $subject;
        ?>
    </h3>
    <button type="button" class="btn-close-icon custom-btn" data-bs-dismiss="offcanvas" aria-label="Close" style="background-color:#FFF;border: 2px solid #FFF;"><span class="mdi mdi-close" style="color:#FFF;"></span></button>
    <!--<button type="button" class="btn-close custom-btn btn-danger" data-bs-dismiss="offcanvas" id="UserClose" aria-label="Close" style="border: 2px solid red;border-radius: 50%;background-color:#FF0000;color:#FFF;"></button>-->
  </div>
  <div class="offcanvas-body" >
    <div class="row editoffcanvas" style="">
        <div class="col-12">
                @if(isset($response['data'][0]['details']) && !empty($response['data'][0]['details'])>0)
                   @foreach($response['data'][0]['details'] as $key => $task)
                   <?php //print_r($task['assignedtoInitials']); ?>
                    <div class="card">
                      <div class="card-body p-3">
                        @if($key == 0)
                            <h5 class="card-title">{{$subject}}</h5>
                        @endif
                        <div class="card-text"><?php echo $task['details'];?></div>
                        
                                @php                        
                                $docpath = $task['docpath'];   
                                @endphp
                                @if (is_array($docpath) && count($docpath) > 0)
                                <div class="row">
                                @foreach($docpath as $doc)
                                <div class="col p-2">
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
                      <div class="card-footer p-1">
                          
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
        									
                            <?php 
                            $today = date('Y-m-d');
                            $taskcreatedon =  date('Y-m-d', strtotime($task['createdon']));
                            $diff = date_diff(date_create($today), date_create($taskcreatedon));
                            if($diff->format("%R%a") < 0){
                                $createdon = date('d M h:i A', strtotime($task['createdon']));
                            }else{
                                $createdon = date('h:i A', strtotime($task['createdon']));
                            }
                            
                           
                            
                            //echo '<p>'.$spriority. ' Priority, '.$tstatus.'</p>';
                            ?>

                        
                            @php 
                                $assignedtoname = "";
                                $createdbyname = "";
                                $createdbyinitials = "";
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
                                            $createdbyinitials = strtoupper(mb_substr($user['firstname'], 0, 1)).strtoupper(mb_substr($user['lastname'], 0, 1));
                                        @endphp
                                    @endif
                                @endforeach
                            @endif
                            
                            <!--<p>Assigned to: <?=$assignedtoname;?></p>
                            <p>Created By: <?=$createdbyname;?></p>-->
                        
                        
                        
                            <div class="footerLeft p-1"> 
                                <span class="p-2 mr-1" style="background-color: #3073f140;color:#3073f1;border-radius: 10px;height:45px;">{{ $tstatus }}</span>
                                <span class="p-2 ml-1" style="background-color: #e2a90740;color:#e2a907;border-radius: 10px;height:45px;">{{ $spriority }}</span>
                            </div>
                            <div class="footerRight d-flex justify-content-between p-1">
                                <div class="avatars p-2 mr-1">
                                    <div class="position-relative">
                                        <img src="{{my_asset('assets/images/avatar.png')}}" alt="{{$createdbyinitials}}" class="avatar">
                                        <div class="position-absolute top-50 start-25 translate-middle ml-1 text-black">
                                            {{$createdbyinitials}}
                                        </div>
                                    </div>    
                                        @if($task['assignedtoInitials'])
                                        @php
                                            $nameParts = explode(',', $task['assignedtoInitials']);
                                        @endphp
                                        @foreach($nameParts as $assignto)
                                        <div class="position-relative">
                                        <img src="{{my_asset('assets/images/avatar.png')}}" alt="{{$assignto}}" class="avatar">
                                        <div class="position-absolute top-50 start-25 translate-middle ml-1 text-black">
                                            {{$assignto}}
                                        </div>
                                        </div>
                                        @endforeach
                                        @endif
                                    
                                </div>
                                <div class="text-muted p-2 ml-1 mt-2">
                                    <span class="time-icon"> <i class="mdi mdi-alarm" style="font-size:16px;"></i>
                                            {{$createdon}}
                                    </span>
                                </div>
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
                    <input type="hidden" name="createdby" id="createdby" value="{{ $response['data'][0]['createdby'] }}">
                    <input type="hidden" name="assigned_to" id="assigned_to" value="{{ $response['data'][0]['assignedto'] }}">
                        <div class="row m-3">
                            
                                <div class="form-group col-md-12">
                                    <label class="form-label">{{ __('Comments') }}</label>
                                    <div class="form-icon-user">
                                        <textarea name="details" id="details"  placeholder="{{ __('Comments') }}" class="textEditor form-control {{ $errors->has('details') ? ' is-invalid': '' }}" required></textarea>
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
                                <label class="form-label">{{ __('Status') }}</label>
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
                                  
                                            <div class="multiSelect-dropdown w-100">
                                                <div class="multiSelect-dropdown-toggle">Select options</div>
                                                <div class="multiSelect-dropdown-menu">
                                                    @php 
                                                    $assigntoarr = explode(",", $response['data'][0]['assignedto']);
                                                    @endphp
                                                    @if(isset($users['users']) && !empty($users['users'])>0)
                                                        @foreach($users['users'] as $user)
                                            				@if(isset($user['status']) && $user['status'] == 'A')
                                                                <label><input type="checkbox" name="assignto[]" value=<?php echo (count($assigntoarr) > 0 && in_array($user['id'], $assigntoarr)) ? "selected" : ""; ?>> {{$user['firstname']}}</label>
                                            				@endif
                                                        @endforeach
                                            		@endif
                                                </div>
                                            </div>
                                      
                    
                    
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

<script src="{{ my_asset('/assets/js/tasks.js') }}" type="text/javascript"></script>

<!--<script src="{{ my_asset('/assets/js/tinymce/tinymce.js') }}" type="text/javascript"></script>-->
<script>

    $('#update-task').on('submit',(function(e) {
        e.preventDefault();
        var details = CKEDITOR.instances.details.getData();
        var formData = new FormData(this);
        formData.append('details', details);
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
<script>
  /* tinymce.init({
            selector: '#details'  
        });*/
</script>
<script>

$(document).ready(function() {
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

});
</script>