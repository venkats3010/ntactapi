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
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />	
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
            background-color: #008000;
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
.cke_notification_message, .cke_notification, .cke_notification_warning, .cke_notification_close {
	display:none;
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
@media (max-width: 768px) {
  .small-screen {
    display: none;
  }
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




<!--<div class="container-fluid btn-group-container shadow-sm p-2">-->
<div>
    <form class="mb-0" method="GET" action="{{ route('tasks.archive') }}" id="dateRangeForm">
    <div class="d-flex justify-content-between align-items-center">
        <div class="d-flex justify-content-between">
            {{--<span>
                <input type="text" id="date_range" name="date_range" class="form-control" placeholder="Select date range" value="{{$date_range}}">
            </span>--}}
            
            <span class="m-0">
              <select class="form-control" name="createduser" id="createduser">
                    <option value="">---- Select User ---- &nbsp;&nbsp;&emsp;</option>
                        @if(isset($users['users']) && !empty($users['users'])>0)
                            @foreach($users['users'] as $user)
    							@if(isset($user['status']) && $user['status'] == 'A')
                                    <option value="{{$user['id']}}" <?=($createduser==$user['id'])?"selected":"" ?>>{{$user['firstname']}}</option>
    							@endif
                            @endforeach
    					@endif
                </select>  
            </span>
            
            <!--<span class="btn btn-white txt-14 me-2 btn-bold custom-item hide-div reportFilter" data-bs-toggle="modal"
                data-bs-target="#fmyModal"><i class="mdi mdi-filter"> </i> </span>-->
                <button type="submit" class="btn btn-lg btn-primary btn-icon mx-2" style="border-radius: 20%;"><i class="mdi mdi-filter"> </i></button>
                <?php 
                $showhd = "d-none";
                if($createduser){
                    $showhd = "";
                 } ?>
                    <button class="btn btn-lg btn-danger btn-icon mx-2  clearFilter <?=$showhd;?>" id="1" style="border-radius: 20%;"><i class="fa fa-eraser"></i> Clear</button>
                
        </div>
    
        <a href="{{ url('/dashboard') }}" class="btn btn-lg btn-primary btn-icon m-1 fs-5">
            <i class="mdi mdi-home-circle"></i> <span class="small-screen"> {{ __('Home') }} </span>
        </a>
    </div> 
    </form>
</div>



<!--<a href="" class="btn btn-sm btn-primary btn-icon" title="{{ __('User Login History') }}" data-bs-toggle="tooltip" data-bs-placement="top">
<i class="ti ti-user-check"></i>
</a>-->

@endsection
@section('content')
                <div class="row">
                    @if(isset($tasks['data']) && $tasks['data'] != "")
                        @foreach($tasks['data'] as $index => $task) 
                        @if($task['status'] == 0 || $task['status'] == 6)
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
                                
                                    
                                
                                <?php // if($role != 2){?>
                                   <div class="delete-badge movetoindex" id={{$task['id']}} style="cursor:pointer"><i class="mdi mdi-inbox" aria-hidden="true"></i></div>
                                <?php // } ?>
                            </div>
                            @endif
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
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.js"></script>
<script type="text/javascript">
$(function() {
    /*var today = moment();
    var sevenDaysAgo = moment().subtract(7, 'days');
    var endOfWeek = moment().endOf('week');
    $('#date_range').daterangepicker({
        opens: 'left',
        startDate: sevenDaysAgo,
        endDate: today,
        locale: {
            format: 'YYYY-MM-DD'
        }
    }, function(start, end) {
        $('#date_range').val(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
        $('.clearFilter').removeClass('d-none');
        $('#dateRangeForm').submit();
    });*/

    $('#createduser').on('change', function() {
        $('.clearFilter').removeClass('d-none');
        $('#dateRangeForm').submit();
    });
    
    $('.clearFilter').on('click', function() {
        //$('#date_range').val('');
        $('#createduser').val('');
        $('.clearFilter').addClass('d-none');
        $('#dateRangeForm').submit();
    });
    
    if ($('#date_range').val()) {
       // $('.clearFilter').removeClass('d-none');
    }
    
});

</script>


<script type="text/javascript">
$(document).ready(function() {

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
			  $('#update-task').addClass("d-none");
		  }
	  }

    });
});


$('.movetoindex').click(function(event) {
    event.preventDefault();
    let rowid = $(this).attr('id');
    let status = 2;
    swal({
            title: `Are you sure?`,
            //text: "This action can not be undone. Do you want to continue?",
            text: " Do you want to move it to Tasks?",
            icon: "warning",
            buttons: ["No", "Yes"],
            //   buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                let url = "{{ url('/tasks/statusupdate') }}";
                    $.ajax({
                        type: 'POST',
                        url: url,
                        data : { _token: "{{ csrf_token() }}", status:status,rowid:rowid },
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
</script>
@endsection