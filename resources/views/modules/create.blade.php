@extends('layouts.app')

@section('module-title')
    {{ __('Create Module') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ url('modules') }}">{{ __('Module') }}</a></li>
    <li class="breadcrumb-item">{{ __('Create') }}</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                   
                        
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="form-label">{{ __('Title') }}</label>
                                <div class="col-sm-12 col-md-12">
                                    <input type="text" placeholder="{{ __('Title') }}" name="title" id="title" class="form-control {{ $errors->has('title') ? ' is-invalid' : '' }}" autofocus>
                                    <div class="invalid-feedback">
                                        {{ $errors->first('name') }}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label">{{ __('Slug') }}</label>
                                <div class="col-sm-12 col-md-12">
                                    <input type="text" placeholder="{{ __('Slug') }}" name="slug" id="slug" class="form-control {{ $errors->has('slug') ? ' is-invalid' : '' }}">
                                    <div class="invalid-feedback">
                                        {{ $errors->first('slug') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="form-label">{{ __('Content') }}</label>
                                <div class="col-sm-12 col-md-12">
                                    <input type="text" name="content" id="content"  placeholder="{{ __('Content') }}" class="form-control {{ $errors->has('content') ? ' is-invalid': '' }}" required>
                                    <div class="invalid-feedback">
                                        {{ $errors->first('content') }}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label class="form-label">{{ __('Status') }}</label>
                                    <div class="col-sm-12 col-md-12">
                                        <select class="form-control select-field field_type" name="status" id="status" required>
                                            <option value="A">{{ __('Active') }}</option>
                                            <option value="I">{{ __('Inactive') }}</option>
                                        </select>                                        
                                        <div class="invalid-feedback">
                                            {{ $errors->first('status') }}
                                        </div>
                                    </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label class="form-label">{{ __('Meta Title') }}</label>
                                    <div class="col-sm-12 col-md-12">
                                        <input type="text" name="meta_title" id="meta_title" placeholder="{{ __('Meta Title') }}"  class="form-control {{ $errors->has('meta_title') ? ' is-invalid': '' }}" required>
                                        <div class="invalid-feedback">
                                            {{ $errors->first('meta_title') }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="form-label">{{ __('Meta Description') }}</label>
                                    <div class="col-sm-12 col-md-12">
                                        <input type="text" name="meta_description" id="meta_description" placeholder="{{ __('Meta Description') }}"  class="form-control {{ $errors->has('meta_description') ? ' is-invalid': '' }}" required>
                                        <div class="invalid-feedback">
                                            {{ $errors->first('meta_description') }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6">

                                </div>
                                <div class="form-group col-md-6">

                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="form-group col-md-12">
                                <label class="form-label"></label>
                                <div class="col-sm-12 col-md-12 text-end">
                                    <button class="btn btn-primary btn-block mt-2 btn-submit createModule"><span>{{ __('Save') }}</span></button>
                                </div>
                            </div>
                        </div>
                    
                </div>
            </div>
        </div>
    </div>

@endsection

@section('jscontent')
<script>
        $('.createModule').on('click', function() {
            let title = $('#title').val();
            let slug = $('#slug').val();
            let content = $('#content').val();
            let meta_title = $('#meta_title').val();
            let meta_description = $('#meta_description').val();
            let status = $('#status').val();
            
            $('.createModule').prop('disabled', true).html('Please Wait...');

            $.ajax({
                    url: "{{ url('/modules/store') }}",
                    type: 'post',
                    data: {  _token: "{{ csrf_token() }}", title:title, slug:slug, content:content, status:status, meta_title:meta_title, meta_description:meta_description  },
                    dataType: 'json',
                    success: function(res) {
                        console.log(res);
						if (res.status == '200') {
							toastr.success(res.message);					
							location.reload();
						} else {
							toastr.error(res.message);
							//toastr.error('Oops! Somthing went wrong.');
							$('.createModule').prop('disabled', false);
							$('.createModule').html('Save');
						}
						
                    }
                });
        });

function isNumber(evt) {
	evt = (evt) ? evt : window.event;
	var charCode = (evt.which) ? evt.which : evt.keyCode;
	if (charCode > 31 && (charCode < 48 || charCode > 57)) {
		return false;
	}
  return true;
}

</script>
@endsection