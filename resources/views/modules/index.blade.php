@extends('layouts.app')

@section('module-title')
    {{ __('Manage Users') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Menu') }}</li>
@endsection

@section('multiple-action-button')

<a href="{{ url('/modules/create') }}"  class="mx-1" >    
    <div class="btn btn-sm btn-primary btn-icon" data-bs-toggle="tooltip" data-bs-placement="top"
        title="{{ __('Create User') }}">
        <i class="ti ti-plus text-white"></i>
    </div>
</a>


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
                                    <th scope="col">#</th>
                                    <th scope="col">{{ __('Title') }}</th>
                                    <th scope="col">{{ __('Sulg') }}</th>
                                    <th scope="col">{{ __('Content') }}</th>
                                    <th scope="col">{{ __('Meta Title') }}</th>
                                    <th scope="col">{{ __('Meta Description') }}</th>
                                    <th scope="col">{{ __('Status') }}</th>
                                    <th scope="col" class="text-end">{{ __('Action') }}</th>
                                {{-- </span> --}}
                            </tr>
                            </thead>
                            <tbody>                         
                                @if(isset($modules['modules']) && count($modules['modules'])>0 )
                                @foreach($modules['modules'] as $index => $module)
                                    <tr>
                                        <th scope="row">{{++$index}}</th>
                                        <td>{{$module['title']}}</td>
                                        <td>{{$module['slug']}}</td>
                                        <td>{{$module['content']}}</td>
                                        <td>{{$module['meta_title']}}</td>
                                        <td>{{$module['meta_description']}}</td>
                                        <td>{{$module['status']}}</td>
                                        <td>
                                        <div>
                                            <button class="btn btn-sm btn-green" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                Actions
                                                <span class="svg-icon svg-icon-5 m-0"> <i class="fa fa-chevron-down" aria-hidden="true"></i></span>
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <li><button  class="btn btn-sm my-2 dropdown-item" href="{{ url('modules/edit/'.$module['id']) }}" data-id='+v.id+'><span class="mdi mdi-pencil"></span> Edit</button></li>
                                                
                                                <li><span class="btn btn-sm my-2 dropdown-item deleteModule" id="{{$module['id']}}" type="button"><i class="fa fa-trash"></i> Delete</span></li>

                                                

                                            </ul>
                                        </div>
                                        </td>
                                    </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('jscontent')

<script>
$(document).ready(function() {

});

$('body').on('click', '.addModule', function(){
	$('#offcanvas_viewdata').html('');
	$('#myoffcanvasRightLabel').html('').html('Add Agency ');
	$.ajax({
	  type: 'POST',
      url: "{{ url('modules.create') }}",
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


$('.deleteModule').click(function(event) {
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
                let url = "{{ url('/modules/destroy') }}/" + itemid;
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