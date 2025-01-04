@extends('layouts.app')
    <?php
    $session = Session::all();
	//print_r($session['username']);//exit;
	$role = $session['auth']['data']['role'];
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
    width: 30px;
    height: 30px;
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
    background-color: red; 
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

<button id="" class="btn btn-sm btn-primary btn-icon  addTask" data-bs-toggle="offcanvas" href="#myoffcanvasRight" aria-describedby="tooltip">
    <i class="mdi mdi-plus-circle"></i> Tasks
</button>



<!--<a href="" class="btn btn-sm btn-primary btn-icon" title="{{ __('User Login History') }}" data-bs-toggle="tooltip" data-bs-placement="top">
<i class="ti ti-user-check"></i>
</a>-->

@endsection
@section('content')
                <div class="row">
                    @if(isset($tasks['data']) && $tasks['data'] != "")
                        @foreach($tasks['data'] as $index => $task)
                            <div class="col-md-3 mb-5">
                                
                                <div class="card editTask" style="height:100% !important;cursor:pointer;" data-bs-toggle="offcanvas" href="#myoffcanvasRightEdit" aria-describedby="tooltip" data-id={{$task['id']}} data-tid={{$task['tid']}}>
                                    <div class="card-body p-1">
                                        <!--<div class="comment-badge">{{$task['cntdtls']}}</div>-->
                                        
                                            <?php
                                            if ($task['priority'] == 1) {
                                                $priority = 'High';
                                            } elseif ($task['priority'] == 2) {
                                                $priority = 'Medium';
                                            } elseif ($task['priority'] == 3) {
                                                $priority = 'Low';
                                            }
                                            ?>
                                            
                                            <?php 
                                            $initials = '';
                                            
                                            $nameParts = explode(',', $task['details'][0]['assignedtoName']);
                                            
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
                                        
                                        
                                        <div class="task">
                                            <div class="badge-container">
                                                
                                                <span class="badge-rectangle" style="background-color: #d291bc;">
                                                    {{$task['tid']}} - {{$task['cntdtls']}}
                                                
                                                </span>
                                                
                                            </div>
                                            <div class="badge-container">
                                                <span class="badge-rectangle" style="background-color: #17a2b8;">{{$task['createdbyname']}} - {{$task['details'][0]['assignedtoName']}}</span>
                                            </div>
                                            <div class="badge-container">
                                                <span class="badge-rectangle" style="background-color: #0d6efd;">{{ $priority }}</span>
                                            </div>
                                            <div class="badge-container">
                                                <span class="badge-rectangle" style="background-color: #ff7f50;">{{$cstatus}}</span>
                                            </div>
                                            
                                        </div>
                                        <div class="px-3 text-center">
                                            <p class="card-text mt-2 mb-0"><?php echo date('M d,Y h:i a', strtotime($task['createdon'])); ?></p>
                                        </div>
                                        <div class="px-3 text-center">
                                            <p class="card-text mt-2 mb-0">{{$task['subject']}}</p>
                                        </div>
                                        
                                    </div>
                                    
                                </div>
                                <?php if($role != 2){?>
                                  {{--  <div class="delete-badge deleteTask" id={{$task['id']}} style="cursor:pointer"><i class="fa fa-trash" aria-hidden="true"></i></div>--}}
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
  <div class="offcanvas-header" style="background-color:#c1c1c1;color:#000;">
    <h5 id="myoffcanvasRightLabel"></h5>
    <!--<button type="button" class="btn-close text-reset btn-danger" data-bs-dismiss="offcanvas" id="UserClose" aria-label="Close"></button>-->
    <button type="button" class="btn-close custom-btn" data-bs-dismiss="offcanvas" id="UserClose" aria-label="Close" style="border: 2px solid red;border-radius: 50%;"></button>
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
            text: " Do you want to delete?",
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
</script>
@endsection