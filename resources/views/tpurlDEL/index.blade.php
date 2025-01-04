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
<style>
        .badge-container {
            display: inline-block;
            margin:  5px;
        }
        .task {
            padding: 15px;
            border-radius: 8px;
            display: inline-block;
            position: relative;
        }
        .comment-badge {
            position: absolute;
            top: -10px;
            right: -10px;
            background-color: #ffd700;
            color: #000;
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
            font-weight: bold;
            border: 1px solid #000;
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
    <div class="">
        <div class="">
            <div class="">
                <div class="">
                    
                <div class="row">
                    @if(isset($tasks['data']) && $tasks['data'] != "")
                        @foreach($tasks['data'] as $index => $task)
                            <div class="col-md-3 mb-4">
                                
                                <div class="card">
                                    <div class="card-body">
                                        <div class="comment-badge">{{$task['cntdtls']}}</div>
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
                                            $nameParts = explode(' ', $task['details'][0]['assignedtoName']);
                                            $initials = '';
                                            foreach ($nameParts as $part) {
                                                $initials .= strtoupper(mb_substr($part, 0, 1));
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
                                                <span class="btn btn-sm btn-primary editTask badge-rectangle" style="background-color: #d291bc;" type="button" data-bs-toggle="offcanvas" href="#myoffcanvasRightEdit" aria-describedby="tooltip" data-id={{$task['id']}} data-tid={{$task['tid']}}>
                                                {{$task['tid']}} <i class="mdi mdi-chevron-right"></i>
                                                </span>
                                            </div>
                                            <div class="badge-container">
                                                <span class="badge-rectangle" style="background-color: #0d6efd;">{{ $priority }}</span>
                                            </div>
                                            <div class="badge-container">
                                                <span class="badge-rectangle" style="background-color: #ff7f50;">{{$cstatus}}</span>
                                            </div>
                                            <div class="badge-container">
                                                <span class="badge-rectangle" style="background-color: #17a2b8;">{{$initials}}</span>
                                            </div>
                                            <p class="card-text mt-2 mb-0">{{$task['subject']}}</p>
                                            
                                        </div>
                                    </div>
                                </div>
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



$('body').on('click', '.editTask', function(){
	$('#offcanvas_editview').html('');
	let rowid = $(this).attr('data-id');
	let rowtid = $(this).attr('data-tid');
	$.ajax({
	  type: 'POST',
      url: "{{ url('tpurl/edit') }}",
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


</script>
@endsection