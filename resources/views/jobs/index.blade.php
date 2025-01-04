@extends('layouts.app')
<style>
@media (max-width: 768px) {
    #myoffcanvasRight {
        width: 100%;
    }
}

@media (min-width: 769px) {
    #myoffcanvasRight {
        width: 75%;
    }
}
</style>
<style>
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

.intl-tel-input {
	width:100%; 
}
</style>
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/12.1.6/css/intlTelInput.css'/>
{{--
@section('user-title')
    {{ __('Manage Users') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Users') }}</li>
@endsection
--}}
@section('multiple-action-button')
<a href="{{ url('/dashboard') }}" class="btn btn-lg fs-5 btn-primary btn-icon m-1">
    <i class="mdi mdi-home-circle"></i> {{ __('Home') }}
</a>
<button id="" class="btn btn-lg fs-5 btn-primary btn-icon  addUser" data-bs-toggle="offcanvas" href="#myoffcanvasRight" aria-describedby="tooltip">
  <i class="ti ti-user-check"></i>  {{ __('Add User') }}
</button>

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
                                    <th scope="col">#</th>
                                    <th scope="col">{{ __('Username') }}</th>
                                    {{--<th scope="col">{{ __('Gender') }}</th>--}}
                                    <th scope="col">{{ __('Email') }}</th>
                                    <th scope="col">{{ __('Phone') }}</th>                                    
                                    <th scope="col">{{ __('Status') }}</th>
                                    <th scope="col" class="text-center">{{ __('Action') }}</th>
                                {{-- </span> --}}
                            </tr>
                            </thead>
                            <tbody>           
                                @if(isset($users['users']) && count($users['users'])>0 )
                                @foreach($users['users'] as $index => $user)
								@if($index > 3)
                                    <tr>
                                        <th scope="row">{{++$index}}</th>
                                        <td><button class="btn btn-sm btn-primary d-inline-flex align-items-center editUser" id="{{$user['id']}}" data-toggle="tooltip" title="{{ __('Edit') }}" data-bs-toggle="offcanvas" href="#myoffcanvasRight" aria-describedby="tooltip"> {{$user['firstname'] }} {{ $user['lastname']}} <i class="mdi mdi-chevron-right"></i></button></td>
                                        {{--<td>{{$user['gender']}}</td>--}}
                                        <td>{{$user['email']}}</td>
                                        <td>{{$user['phone']}}</td>                                        
                                        <td><?php
                                        $status = '';
                                        if ($user['status'] == "A") {
                                            $status = 'Active';
                                            ?><i class="mdi mdi-checkbox-marked-circle" style="color:green;"></i><?php
                                        } elseif ($user['status'] == "D" || $user['status'] == "I") {
                                            $status = 'In-Active';
                                            ?><i class="mdi mdi-close-circle-outline" style="color:red;"></i> <?php
                                        } ?>
                                            </td>
                                        <td class="text-center">
                                        <div>
                                            <button class="btn btn-sm btn-green" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                Actions
                                                <span class="svg-icon svg-icon-5 m-0"> <i class="fa fa-chevron-down" aria-hidden="true"></i></span>
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">

                                                <li><a class="mx-3 btn btn-sm d-inline-flex align-items-center editUser" id="{{$user['id']}}" data-toggle="tooltip" title="{{ __('Edit') }}" data-bs-toggle="offcanvas" href="#myoffcanvasRight" aria-describedby="tooltip"><i class="ti ti-edit m-2"></i> Edit</a></li>

                                                <li><span class="btn btn-sm my-2 dropdown-item deleteUser" data-toggle="modal" data-id="{{$user['id']}}" type="button" id="{{$user['id']}}"><i class="fa fa-trash m-2"></i> Delete</span></li>
                                            </ul>
                                        </div>
                                        </td>
                                    </tr>
									@endif
                                @endforeach
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
  <div class="offcanvas-header p-3" style="background-color:#145388;color:#000;">
    <h3 id="myoffcanvasRightLabel" style="color:#FFF;"></h3>
    <button type="button" class="btn-close-icon custom-btn" data-bs-dismiss="offcanvas" id="UserClose" aria-label="Close" style="background-color:#FFF;border: 2px solid #FFF;"><span class="mdi mdi-close" style="color:#FFF;"></span></button>
    <!-- <button type="button" class="btn-close custom-btn" data-bs-dismiss="offcanvas" id="UserClose" aria-label="Close" style="border: 2px solid red;border-radius: 50%;"></button>-->
  </div>
  <div class="offcanvas-body" id="offcanvas_viewdata">
    <i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
  </div>
</div>
<!-- Off Canvas Right -->

@endsection

@section('jscontent')

<script>
$(document).ready(function() {
	$('#tblEmployee').DataTable();
});

$('body').on('click', '.addUser', function(){	
	$('#offcanvas_viewdata').html('');
	$('#myoffcanvasRightLabel').html('').html('Add User ');
	$.ajax({
	  type: 'GET',
      url: "{{ url('users/create') }}",
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

$('body').on('click', '.editUser', function(){
    let rowid = $(this).attr('id');	
	$('#offcanvas_viewdata').html('');
	$('#myoffcanvasRightLabel').html('').html('Edit User ');
	$.ajax({
	  type: 'GET',
      url: "{{ url('users/edit') }}/"+rowid,
	  data : { _token: "{{ csrf_token() }}" },
     // dataType: "json",
	  beforeSend: function()
	  {
		$("#offcanvas_viewdata").html('<i class="fa fa-spinner"></i> Please wait...');
	  },
	  success: function (result) {
		  if(result){ 
			    $('#offcanvas_viewdata').html('').html(result);
		  }
	  }
	});	
});


$('.deleteUser').click(function(event) {
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
                let url = "{{ url('/users/destroy') }}/" + itemid;
                    $.ajax({
                        type: 'DELETE',
                        url: url,
                        data : { _token: "{{ csrf_token() }}" },
                        dataType: "json",                      
                        success: function (res) {
                            if(res.result == "true"){                
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