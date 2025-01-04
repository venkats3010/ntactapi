@extends('tpurl.layouts.app')
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
</style>
<style>
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
<!--
<button id="" class="btn btn-sm btn-primary btn-icon  addTask" data-bs-toggle="offcanvas" href="#myoffcanvasRight" aria-describedby="tooltip">
   Add <i class="mdi mdi-ticket"></i>
</button>-->



<!--<a href="" class="btn btn-sm btn-primary btn-icon" title="{{ __('User Login History') }}" data-bs-toggle="tooltip" data-bs-placement="top">
<i class="ti ti-user-check"></i>
</a>-->

@endsection
@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table id="pc-dt-simple" class="table">
                            <thead class="thead-light">
                            <tr>
                                {{-- <span class="hide-mob ms-2"> --}}
                                    {{--<th scope="col">#</th>--}}
                                    <th scope="col">{{ __('Id') }}</th>
                                    <th scope="col">{{ __('Subject') }}</th>
                                    <th scope="col">{{ __('Priority') }}</th>
                                    <th scope="col">{{ __('Assigned to') }}</th>
                                    <th scope="col">{{ __('Status') }}</th>
                                    <th scope="col" class="">{{ __('Action') }}</th>
                                {{-- </span> --}}
                            </tr>
                            </thead>
                            <tbody>                    
                                @if(isset($tasks['data']) && $tasks['data'] != "" )
                                @foreach($tasks['data'] as $index => $task)
                                    <tr>
                                        <?php //print_r($task);exit;?>
                                        {{--<th scope="row">{{++$index}}</th>--}}
                                        <td><button type="button" class="btn btn-sm btn-primary editTask" data-bs-toggle="offcanvas" href="#myoffcanvasRightEdit" aria-describedby="tooltip" data-id={{$task['id']}} data-tid={{$task['tid']}}> {{$task['tid']}} <i class="mdi mdi-chevron-right"></i></button></td>
                                        <td>{{$task['subject']}}</td>
                                        <td>
                                            <?php
                                        if ($task['priority'] == 1) {
                                            $priority = 'High';
                                        } elseif ($task['priority'] == 2) {
                                            $priority = 'Medium';
                                        } elseif ($task['priority'] == 3) {
                                            $priority = 'Low';
                                        }
                                        ?>
                                        {{ $priority }}
                                        </td>
                                        <td>{{$task['details'][0]['assignedtoName']}}</td>
                                        <td>
                                        <?php
                                        //print_r($task);
                                        $cstatus ='';
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
                                        echo $cstatus;
                                        ?>
                                        </td>
                                        <td>
                                        <div>
                                            <button class="btn btn-sm btn-green" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                Actions
                                                <span class="svg-icon svg-icon-5 m-0"> <i class="fa fa-chevron-down" aria-hidden="true"></i></span>
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <li><button  class="btn btn-sm my-2 dropdown-item editTask" data-bs-toggle="offcanvas" href="#myoffcanvasRightEdit" aria-describedby="tooltip" data-id={{$task['id']}} data-tid={{$task['tid']}}><span class="mdi mdi-pencil m-2"></span> Edit</button></li>
                                                
                                                <li><span class="btn btn-sm my-2 dropdown-item deleteTask" id="{{$task['id']}}" type="button"><i class="fa fa-trash m-2"></i> Delete</span></li>

                                            </ul>
                                        </div>
                                        </td>
                                    </tr>
                                @endforeach
                                @else
                                <tr><td> No data available</td></tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
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