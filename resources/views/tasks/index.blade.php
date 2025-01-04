@extends('layouts.app')
    <?php
    $session = Session::all();
//	print_r($session['auth']['data']['permissions']);exit;
	$role = $session['auth']['data']['role'];
	$permissions = json_decode($session['auth']['data']['permissions']);
 //echo gettype($permissions);
 /*if(isset($permissions)){
     echo "342".$permissions;
 };exit;*/
	?>
<style>
@media (max-width: 768px) {
    #myoffcanvasRight {
        width: 100%;
    }
    #myoffcanvasRightEdit {
        width: 100%;
    }
}

@media (min-width: 769px) {
    #myoffcanvasRight {
        width: 75%;
    }
    #myoffcanvasRightEdit {
        width: 75%;
    }    
}


.custom-btn {
    border: 2px solid red; 
    background-color: transparent;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    position: relative;
    transition: background-color 0.3s ease; 
}

.custom-btn::before, .custom-btn::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 70%;
    height: 2px;
    background-color: red !important; 
}

.custom-btn::before {
    transform: translate(-50%, -50%) rotate(45deg);
}

.custom-btn::after {
    transform: translate(-50%, -50%) rotate(-45deg);
}

.custom-btn:hover {
    background-color: red; 
}

.custom-btn:hover::before, 
.custom-btn:hover::after {
    background-color: white;
}

</style>
<style>

        .badge-container {
            /*display: inline-block;*/
            
            margin: 3px;
        }
        .task {
            padding: 15px 10px;
            border-radius: 8px;
            display: inline-block;
            position: relative;
            justify-content: space-between;
           /* display: flex;*/
        }
        .comment-badge {
            position: absolute;
            top: -10px;
            right: -10px;
            background-color: #ff0000; /*#ffd700;*/
            color: #FFF;
            border-radius: 50%;
            width: 25px;
            height: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .delete-badge {
            position: absolute;
            top: 90%;
            right: 3px;
            background-color: #ff0000; /*#ffd700;*/
            color: #FFF;
            border-radius: 50%;
            width: 25px;
            height: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .badge-rectangle {
            border-radius: 5px;
            padding: 5px 10px;
            color: #fff;
            /*font-size: 10px;*/
            font-weight: bold;
            border: 1px solid #000;
            min-width: 68px !important;
            display: inline-block;
            text-align:center;
        }
</style>
<style>
        .multiSelect-dropdown {
            position: relative;
            display: inline-block;
            width: 300px; /* Adjust width as needed */
        }

        .multiSelect-dropdown-toggle {
            padding: 10px;
            border: 1px solid #ccc;
            cursor: pointer;
            background-color: #fff;
            text-align: left; /* Align text to the left */
            display: flex;
            flex-wrap: wrap; /* Allow chips to wrap */
            gap: 5px; /* Space between chips */
        }

        .multiSelect-chip {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 16px;
            background-color: #e0e0e0;
            /*font-size: 14px;*/
            margin: 2px;
        }

        .multiSelect-chip .remove {
            margin-left: 5px;
            cursor: pointer;
            color: red;
        }

        .multiSelect-dropdown-menu {
            display: none;
            position: absolute;
            border: 1px solid #ccc;
            background-color: #fff;
            z-index: 1;
            max-height: 200px;
            overflow-y: auto;
            width: 100%;
        }

        .multiSelect-dropdown-menu label {
            display: block;
            padding: 5px 10px;
        }

        .multiSelect-dropdown-menu label:hover {
            background-color: #f0f0f0;
        }
    </style>
    
      <style>
    .task-card {
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    .avatars {
      display: flex;
    }
    .avatar {
      width: 35px;
      height: 35px;
      border-radius: 50%;
      margin-left: -10px;
      border: 2px solid white;
    }
    .time-icon {
      font-size: 12px;
      color: gray;
    }
    .three-dots {
      font-size: 20px;
      cursor: pointer;
    }
  </style>
<style>
.cke_notification_message, .cke_notification, .cke_notification_warning, .cke_notification_close {
	display:none;
}
</style>  
{{--
@section('module-title')
    {{ __('Manage Tasks') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Tasks') }}</li>
@endsection
--}}
@section('multiple-action-button')
<a href="{{ url('/dashboard') }}" class="btn btn-lg btn-primary btn-icon m-1 fs-5">
    <i class="mdi mdi-home-circle"></i> {{ __('Home') }}
</a>
<?php if (isset($permissions) && count($permissions) > 0 && in_array("6", $permissions)) { ?>
<button id="" class="btn btn-lg btn-primary btn-icon m-1 fs-5 addTask" data-bs-toggle="offcanvas" href="#myoffcanvasRight" aria-describedby="tooltip">
    <i class="mdi mdi-plus-circle"></i> {{ __('Create Task') }}
</button>
<?php } ?>
<?php if ($role != 2) { ?>
{{--<a href="{{ url('/tasks/archive') }}" class="btn btn-lg btn-primary btn-icon m-1 fs-5">
    <i class="mdi mdi-home-circle"></i> {{ __('Archive') }}
</a>--}}
<?php } ?>

<!--<a href="" class="btn btn-sm btn-primary btn-icon" title="{{ __('User Login History') }}" data-bs-toggle="tooltip" data-bs-placement="top">
<i class="ti ti-user-check"></i>
</a>-->

@endsection
@section('content')
                <div class="row">
                    @if(isset($tasks['data']) && $tasks['data'] != "")
                        @foreach($tasks['data'] as $index => $task)
                            <div class="col-md-3 mb-4">
                                
                                
                                <?php
                                if ($task['priority'] == 1) {
                                    $priority = 'High';
                                    $priorityClr = 'background-color: #FF000040;color:#FF0000;';
                                } elseif ($task['priority'] == 2) {
                                    $priority = 'Medium';
                                    $priorityClr = 'background-color: #FFA50040;color:#FFA500;';
                                } elseif ($task['priority'] == 3) {
                                    $priority = 'Low';
                                    $priorityClr = 'background-color: #3073f140;color:#3073f1;';
                                }
                                ?>
                                
                                <?php 
                                $initials = '';
                                
                                $nameParts = explode(',', $task['details'][0]['assignedtoName']);
                                $profpics = explode(',', $task['details'][0]['assignedprofilepic']);
                                
                                $result = array_map(function($item) {
                                    $item = ltrim($item);
                                    $words = explode(' ', $item); 
                                    $firstLetters = strtoupper(substr($words[0], 0, 1)); 
                                    $secondLetters = strtoupper(substr($words[1] ?? '', 0, 1)); 
                                    return $firstLetters . $secondLetters;
                                }, $nameParts);
                                
                            
                                if(count($result) > 0){
                                    $initials = implode(",", $result);
                                }
                                ?>
                                <?php
                                $cstatus = '';
                                if ($task['tstatus'] == 1) {
                                    $cstatus = 'New';
                                } elseif ($task['tstatus'] == 2) {
                                    $cstatus = 'In Progress';
                                } elseif ($task['tstatus'] == 3) {
                                    $cstatus = 'Ready for Test';
                                } elseif ($task['tstatus'] == 4) {
                                    $cstatus = 'Completed';
                                } elseif ($task['tstatus'] == 5) {
                                    $cstatus = 'Re Opened';
                                } elseif ($task['tstatus'] == 6) {
                                    $cstatus = 'Closed';
                                }
    
                                ?>

                                
                                
<div class="card mb-1 task-card editTask" style="height:100% !important;cursor:pointer;" data-bs-toggle="offcanvas" href="#myoffcanvasRightEdit" aria-describedby="tooltip" data-id={{$task['id']}} data-tid={{$task['tid']}}>
    <div class="d-flex justify-content-between align-items-center mb-3">
      <div><h5 class="mb-0"><?php 
        $subject = $task['subject']; 
        echo $restrictedSubject = strlen($subject) > 20 ? substr($subject, 0, 25) . '...' : $subject;
      ?></h5></div>
      <div class="p-1 btn-primary" style="border-radius: 10px;">{{$task['tid']}}&nbsp;({{$task['cntdtls']}})</div>  
      <!--<span class="three-dots">⋮</span>-->
    </div>
    <div class="d-flex text-left mb-2"> 
    {{--<span class="m-1 p-2" style="background-color: #3073f140;color:#3073f1;border-radius: 10px;">{{ $cstatus }}</span>--}}
    <span class="m-1 p-2" style="<?=$priorityClr;?>border-radius: 10px;">{{ $priority }}</span>
    </div>
    <div class="d-flex justify-content-between align-items-center mt-2 ml-2">
      <div class="avatars">
          <?php 
          if(count($profpics) > 0){
              $pic ='';
                foreach($profpics as $key =>  $pic){
                        $srtImg =my_asset('assets/images/avatar.png');
                        if ($pic) {
                            $srtImg = my_asset('assets/images/profilePictures/' . $pic);
                        }
                        ?>
                        <div class="position-relative">
                            <img src="{{ $srtImg }}" alt="{{$pic}}" class="avatar">
                            <div class="position-absolute top-50 start-25 translate-middle ml-1 text-black">
                                <?=$result[$key]?>
                            </div>
                        </div>
                        
                        
                    
                   <?php 
                   $pic ='';
                }
            }
            ?>
      </div>
      <div class="text-muted">
        <span class="time-icon"> <i class="mdi mdi-alarm" style="font-size:16px;"></i> 
            @php
                $today = \Carbon\Carbon::today();
                $taskCreatedOn = \Carbon\Carbon::parse($task['createdon']);
                $diff = $today->diffInDays($taskCreatedOn, false);
            
                if ($diff < 0) {
                    $createdOn = $taskCreatedOn->format('d M h:i A');
                } else {
                    $createdOn = $taskCreatedOn->format('h:i A');
                }
            @endphp
                {{ $createdOn }}
        </span>
      </div>
    </div>
</div>
                                
                                    
                                
                                <?php if($role != 2){?>
                                   <div class="delete-badge deleteTask" id={{$task['id']}} style="cursor:pointer"><i class="fa fa-archive" aria-hidden="true"></i></div>
                                <?php } ?>
                            </div>
                        @endforeach
                    @else
                        <div class="col-12">
                            <div class="alert alert-warning" role="alert">
                                No data available
                            </div>
                        </div>
                    @endif
                </div>
    

<!-- Off Canvas Right -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="myoffcanvasRight" aria-labelledby="offcanvasRightLabel" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="offcanvas-header p-3" style="background-color:#145388;color:#000;">
    <h3 id="myoffcanvasRightLabel" style="color:#FFF;"></h3>
    <button type="button" class="btn-close-icon custom-btn" data-bs-dismiss="offcanvas" aria-label="Close" style="background-color:#FFF;border: 2px solid #FFF;"><span class="mdi mdi-close" style="color:#FFF;"></span></button>
    <!--<button type="button" class="btn-close custom-btn" data-bs-dismiss="offcanvas" id="UserClose" aria-label="Close" style="border: 2px solid red;border-radius: 50%;"></button>-->
  </div>
  <div class="offcanvas-body" id="offcanvas_viewdata">
    <i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
  </div>
</div>
<!-- Off Canvas Right -->

<!--Edit screen Off Canvas Right -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="myoffcanvasRightEdit" aria-labelledby="offcanvasRightLabelEdit" data-bs-backdrop="static" data-bs-keyboard="false">

  <div  id="offcanvas_editview">
    <i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
  </div>
</div>
<!-- Edit screen Off Canvas Right -->

@endsection

@section('jscontent')

<script src="{{ my_asset('/assets/js/tasks.js') }}" type="text/javascript"></script>

<script>
$(document).ready(function() {

});

$('body').on('click', '.addTask', function(){
	$('#offcanvas_viewdata').html('');
	$('#myoffcanvasRightLabel').html('').html('Create Task ');
	$.ajax({
	  type: 'GET',
      url: "{{ url('tasks/create') }}",
	  data : { _token: "{{ csrf_token() }}" },
     // dataType: "json",
	  beforeSend: function()
	  {
		$("#offcanvas_viewdata").html('<i class="fa fa-spinner"></i> Please wait...');
	  },
	  success: function (result) {
		  if(result){                
			  $('#offcanvas_viewdata').html(result);
		  }
	  }

    });
});

$('body').on('click', '.editTask', function(){
	$('#offcanvas_editview').html('');
	let rowid = $(this).attr('data-id');
	let rowtid = $(this).attr('data-tid');
	$.ajax({
	  type: 'POST',
      url: "{{ url('tasks/edit') }}",
	  data : { _token: "{{ csrf_token() }}", rowid:rowid },
     // dataType: "json",
	  beforeSend: function()
	  {
		$("#offcanvas_editview").html('<i class="fa fa-spinner"></i> Please wait...');
	  },
	  success: function (result) {
		  if(result){                
			  $('#offcanvas_editview').html(result);
		  }
	  }

    });
});

$('.deleteTask').click(function(event) {
    event.preventDefault();
    let itemid = $(this).attr('id');
    swal({
            title: `Are you sure?`,
            //text: "This action can not be undone. Do you want to continue?",
            text: " Do you want to Archive?",
            icon: "warning",
            buttons: ["No", "Yes"],
            //   buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                let url = "{{ url('/tasks/destroy') }}/" + itemid;
                //$('#' + itemid).click();
                // if (typeof myFunctions[cmfn] === 'function') {
                //     myFunctions[cmfn](itemid); // Call the function with itemid as the argument
                // }
                    $.ajax({
                        type: 'DELETE',
                        url: url,
                        data : { _token: "{{ csrf_token() }}" },
                        dataType: "json",                      
                        success: function (res) {
                            if(res.status == 200){                
                                $('#loader').hide();
                                toastr.success(res.message);
                                location.reload(); 
                            }else{
                                $('#loader').hide();
                                toastr.error(res.message); 
                            }
                        },
                        error: function(res) {
                            $('#loader').hide();
                            toastr.error(res.message);
                        }
                    });
            }
        });
});

$('.custom-btn').click(function(event) {
    location.reload();    
});    
</script>
@endsection